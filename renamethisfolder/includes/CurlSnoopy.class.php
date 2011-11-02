<?php

/**
 * Snoopy with Curl - a dropin replacement for Snoopy
 * 
 * @author Arnold Daniels <arnold@jasny.net>
 * @author Monte Ohrt <monte@ispi.net> (original Snoopy)
 * @version: 0.01
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
class Snoopy
{
	/* *** Public variables *** */
	
	/* user definable vars */

	var $scheme			=   "http";				// http, https, other things like ftp may works as well
	var $host			=	"www.php.net";		// host name we are connecting to
	var $port			=	80;					// port we are connecting to
	var $path			=	"";					// uri path as /path
	var $query			=	array();			// $query["arg"]="value"
	var $postdata		=	"";					// data of post request
    var $fragment		=	null;				// anchor
    
	var $proxy_host		=	"";					// proxy host to use
	var $proxy_port		=	"";					// proxy port to use
	var $proxy_user		=	"";					// proxy user to use
	var $proxy_pass		=	"";					// proxy password to use
	
	var $httpmethod		=   "";					// http request method, "GET", "POST", "HEAD"
	var $httpversion	=   null;				// http request version, eg. "HTTP/1.1"

	var $formvars		=   null;				// variables for POST
	var $formfiles		=	null;				// files for POST
	
	var $agent			=	"Mozilla/5.0 (Windows; U; Windows NT 5.2; en; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9";	// agent we masquerade as
	var	$referer		=	"";					// referer info to pass
	var $cookies		=	array();			// array of cookies to pass
												// $cookies["username"]="joe";
	var	$rawheaders		=	array();			// array of raw headers to send
												// $rawheaders["Content-type"]="text/html";

	var $maxredirs		=	5;					// http redirection depth maximum. 0 = disallow
	var $lastredirectaddr	=	"";				// contains address of last redirected address
	var $expandlinks	=	true;				// expand links to fully qualified URLs.
												// this only applies to fetchlinks()
												// submitlinks(), and submittext()

	var	$offsiteok		=	true;				// DOESN'T WORK! Always follows redirect offsite
	var $maxframes		=	0;					// DOESN'T WORK! No frame support
	var $passcookies	=	true;				// DOESN'T WORK! Follows normal browser (curl) behaviour
	
	var	$user			=	"";					// user for http authentication
	var	$pass			=	"";					// password for http authentication
	
	// http accept types
	var $accept			=	"text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
	
	var $results		=	"";					// where the content is put
		
	var $error			=	"";					// error messages sent here
	var	$response_code	=	"";					// response code returned from server
	var	$headers		=	array();			// headers returned from server sent here
	var	$maxlength		=	500000;				// max return data length (body)
	var $read_timeout	=	0;					// timeout on read operations, in seconds
												// set to 0 to disallow timeouts
	var $timed_out		=	false;				// if a read operation timed out
	var	$status			=	0;					// http request status

	var $temp_dir		=	null;				// temporary directory that the webserver
												// has permission to write to.
												// under Windows, this should be C:\temp

	var	$curl_path		=	"";					// Do not use 

	/**
	 * Fetch the contents of a web page.
	 * Results are stored in $this->results.
	 * 
	 * @param sring $uri The location of the page to fetch
	 * @return boolean
	 */
	function fetch($uri=null)
	{
		$this->error = ""; 
		
		// parse uri
		if (isset($uri)) {
			foreach (parse_url($uri) as $part=>$value) {
				if (isset($value) && $value !== "") $this->$part = $value;
			}
		} else {
			$uri = $this->buildurl(array("scheme"=>$this->scheme, "host"=>$this->host, "port"=>$this->port
			 , "path"=>$this->path, "query"=>$this->query, "fragment"=>$this->fragment
			 , "user"=>$this->user, "pass"=>$this->pass));
		}
		
		// create a new cURL resource
		$ch = curl_init();
		
		// set URL
		curl_setopt($ch, CURLOPT_URL, $uri);
		
		//Tell curl to write the response to a variable
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		/*SSL FIX*/
		if ($this->scheme == 'https') {
			curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}
		/*SSL FIX*/
				
		//register a callback function which will process the headers
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HEADERFUNCTION, array(&$this, '_readheader'));		

		//Some servers (like Lighttpd) will not process the curl request without this header and will return error code 417 instead. 
		//Apache does not need it, but it is safe to use it there as well.
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));

		//Response will be read in chunks of 64000 bytes
		curl_setopt($ch, CURLOPT_BUFFERSIZE, $this->maxlength);

		//Response will be read in chunks of 64000 bytes
		if ($this->read_timeout > 0) curl_setopt($ch, CURLOPT_TIMEOUT, $this->read_timeout);
		
		//Tell curl to use the specified request method and version
		if (!empty($this->httpmethod)) curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->httpmethod);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		
		/*POSTDATA FIX*/
		if ($this->httpmethod == "POST") {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postdata);
		}
		/*POSTDATA FIX*/
		
		//Set user agent and referer
		if (!empty($this->agent)) curl_setopt($ch, CURLOPT_USERAGENT, $this->agent);
		if (!empty($this->referer)) curl_setopt($ch, CURLOPT_REFERER, $this->referer);

		//Set user and password for authentication
		if (!empty($this->user)) curl_setopt($ch, CURLOPT_USERPWD, $this->proxy_user . (!empty($this->pass) ? ':' . $this->pass : '')); 
		
		//Set additional headers
		$headers = null;
		if (!empty($this->accept) && !isset($headers['accept'])) $headers[] = "Accept: {$this->accept}"; 
		foreach ($this->rawheaders as $key=>$value) $headers[] = "$key: $value";
		if (!empty($headers)) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		//Set cookies
		if (!empty($this->cookies)) curl_setopt($ch, CURLOPT_COOKIE, http_build_query($this->cookies, '', ';'));
		
		//Set curl to use a proxy
		if (!empty($this->proxy_host)) {
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_host);
			if (!empty($this->proxy_host)) curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxy_port);
			if (!empty($this->proxy_user)) curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_user . (!empty($this->proxy_pass) ? ':' . $this->proxy_pass : '')); 
		}
		
		//Configure redirect
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->maxredirs > 0);
		curl_setopt($ch, CURLOPT_MAXREDIRS, $this->maxredirs);

		//grab URL and get content (headers are set by callback)
		$result = curl_exec($ch);
		
		//check if fetch was succesful
		if ($result === false) {
			$this->error = curl_error($ch) . ".\n";
			if (curl_errno($ch) == CURLE_OPERATION_TIMEOUTED) $this->result = -100; 
			return false;
		}
		
		$this->results = $result;
		
		//grab other info
		$this->lastredirectaddr = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		$this->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		//close connection
		curl_close($ch);
    return true;
	}

	
	/**
	 * Fetch the links from a web page.
	 * Results are stored in $this->results.
	 * 
	 * @param string $uri Where you are fetching from
	 * @return boolean
	 */
	function fetchlinks($uri)
	{
		if (!$this->fetch($uri)) return false;
					
		$this->results = $this->_striplinks($this->results);
		if ($this->expandlinks) $this->results = $this->_expandlinks($this->results, $uri);
		return true;
	}	
	
	/**
	 * Fetch the form elements from a web page.
	 * Results are stored in $this->results.
	 * 
	 * @param string $uri Where you are fetching from
	 * @return boolean
	 */
	function fetchform($uri)
	{
		if (!$this->fetch($uri)) return false;

		$this->results = $this->_stripform($this->results);
		return true;
	}

	/**
	 * Fetch the text from a web page, stripping the links.
	 * Results are stored in $this->results.
	 * 
	 * @param string $uri Where you are fetching from
	 * @return boolean
	 */
	function fetchtext($uri)
	{
		if (!$this->fetch($uri)) return false;

		$this->results = $this->_striptext($this->results);
		return true;
	}
	

	/**
	 * Submit an http form and fetch the contents.
	 * Results are stored in $this->results.
	 * 
	 * @param sring $uri       The location of the page to fetch
	 * @param array $formvars  An array of files to submit as in $formvars["var"] = "value"
	 * @param array $formfiles An array of files to submit as in $formfiles["var"] = "/dir/filename.ext";
	 * @return boolean
	 */
	function submit($uri, $formvars=null, $formfiles=null)
	{
		$this->formvars = $formvars;
		$this->formfiles = $formfiles;
		$this->httpmethod = "POST";
		
		return $this->fetch($uri);
	}

	/**
	 * Submit an http form and fetch the contents.
	 * Results are stored in $this->results.
	 * 
	 * @param sring $uri       The location of the page to fetch
	 * @param array $formvars  An array of files to submit as in $formvars["var"] = "value"
	 * @param array $formfiles An array of files to submit as in $formfiles["var"] = "/dir/filename.ext";
	 * @return boolean
	 */
	function submittext($uri, $formvars=null, $formfiles=null)
	{
		if ($this->lastredirectaddr) $uri = $this->lastredirectaddr;
		
		if (!$this->submit($uri, $formvars, $formfiles)) return false;
		
		$this->results = $this->_striptext($this->results);
		if ($this->expandlinks) $this->results = $this->_expandlinks($this->results, $uri);
		
		return true;
	}

	
	/**
	 * Set the form submission content type to 'multipart/form-data'
	 */
	function set_submit_multipart()
	{
		$this->_submit_type = "multipart/form-data";
	}

	/**
	 * Set the form submission content type to 'application/x-www-form-urlencoded'
	 */
	function set_submit_normal()
	{
		$this->_submit_type = "application/x-www-form-urlencoded";
	}

	/**
	 * Does nothing: only here to not break the original snoopy API.
	 */
	function setcookies()
	{
	}
	
	
	/**
	 * Create a url from its components (referce of parse_url)
	 * 
	 * @param array $uri_parts
	 * @return string
	 */
	function buildurl($uri_parts) {
		extract($uri_parts);
		
		if (!isset($scheme)) $scheme = 'http';
		if (!isset($path)) $path = '';
		if (!isset($query) && is_array($query)) $query = http_build_query($query);
		
		return "$scheme://" 
		 . (!empty($user) ? $user . (!empty($password) ? ":$password" : '') . '@' : '') 
		 . $host . (!empty($port) ? ":$port" : '') . $path
		 . (!empty($query) ? "?$query" : '') . (!empty($fragment) ? "#$fragment" : '');
	}
	
	
	/**
	 * Callback function for CURL: do not use
	 * @access private
	 * 
	 * @param reference $ch
	 * @param string    $header
	 * @return int
	 */
	function _readheader($ch, $header) {
		if ($header !== "\r\n") $this->headers[] = $header;
		if (preg_match("|^HTTP/|", $header)) $this->response_code = $header;
		
		return strlen($header);
	}

	/**
	 * Strip the hyperlinks from an html document
	 * @access private
	 * 
	 * @param string $document document to strip.
	 * @return array
	 */
	function _striplinks($document)
	{
		preg_match_all("'<\s*a\s.*?href\s*=\s*			# find <a href=
						([\"\'])?					# find single or double quote
						(?(1) (.*?)\\1 | ([^\s\>]+))		# if quote found, match up to next matching
													# quote, otherwise match up to next space
						'isx", $document, $links);

		// catenate the non-empty matches from the conditional subpattern
		while(list($key,$val) = each($links[2])) {
			if(!empty($val)) $match[] = $val;
		}
		
		while(list($key,$val) = each($links[3])) {
			if(!empty($val)) $match[] = $val;
		}		
		
		return $match;
	}

	/**
	 * Strip the form elements from an html document
	 * @access private
	 * 
	 * @param string $document document to strip.
	 * @return string
	 */
	function _stripform($document)
	{	
		preg_match_all("'<\/?(FORM|INPUT|SELECT|TEXTAREA|(OPTION))[^<>]*>(?(2)(.*(?=<\/?(option|select)[^<>]*>[\r\n]*)|(?=[\r\n]*))|(?=[\r\n]*))'Usi", $document, $elements);
		return implode("\r\n", $elements[0]);
	}

	/**
	 * Strip the text from an html document
	 * @access private
	 *  
	 * @param string $document document to strip.
	 * @return string
	 */
	function _striptext($document)
	{
		$search = array("'<script[^>]*?>.*?</script>'si",	// strip out javascript
						"'<[\/\!]*?[^<>]*?>'si",			// strip out html tags
						"'([\r\n])[\s]+'"					// strip out white space
						);
		$replace = array("", "", "\\1", );
		
		return html_entity_decode(preg_replace($search, $replace, $document));
	}

	/**
	 * Expand each link into a fully qualified URL
	 * @access private
	 *  
	 * @param array $links The links to qualify
	 * @param strin $uri   The full URI to get the base from
	 * @return array
	 */
	function _expandlinks($links, $uri)
	{
		preg_match("/^[^\?]+/", $uri, $match);
		$match = preg_replace(array("|/[^\/\.]+\.[^\/\.]+$|", "|/$|"), "", $match[0]);

		$match_part = parse_url($match);
		$match_root = $match_part["scheme"]. "://" . $match_part["host"];
				
		$search = array("|^http://".preg_quote($this->host)."|i",
						"|^(\/)|i",
						"|^(?!http://)(?!mailto:)|i",
						"|/\./|",
						"|/[^\/]+/\.\./|"
					);
						
		$replace = array("",
						$match_root."/",
						$match."/",
						"/",
						"/"
					);			
				
		return preg_replace($search, $replace, $links);
	}	
}

// For PHP 4 only
if (!function_exists('http_build_query')) {
	/**
	 * Generate URL-encoded query string
	 * @see http://www.php.net/http_build_query
	 * 
	 * @param array  $formdata
	 * @param string $numeric_prefix
	 * @param string $arg_separator
	 * @param string $parent_key      DO NOT USE!
	 * @return string
	 */
    function http_build_query($formdata, $numeric_prefix=null, $arg_separator=null, $parent_key=null) {
		$args = array();
        if(!isset($arg_separator)) $arg_separator = ini_get("arg_separator.output");
		
		foreach((array)$formdata as $key=>$value) {
			if(is_int($key) && $numeric_prefix != null) $key = $prefix . $key;
			if(!empty($parent_key)) $key = "$parent_key[$key]";
			$key = urlencode($key);
			
			$args[] = is_array($value) || is_object($value) ? http_build_query($value, '', $arg_separator, $key) : "$key=" . urlencode($value);
		}

        return implode($arg_separator, $args);
    }
}

?>