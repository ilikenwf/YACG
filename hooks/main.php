<?php //BASIC FUNCTIONS
if (DEBUG == false) {
	error_reporting(0);
}
function give404($page) {
	header("HTTP/1.0 404 Not Found");
	die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
	<html><head><title>404 Not Found</title></head><body>
	<h1>Not Found</h1>
	<p>The requested URL '.$page.' was not found on this server.</p>
	<hr>
	<address>Apache/2.2.3 (Unix) Server at '.THIS_DOMAIN.' Port 80</address>
	</body></html>');
}

function setPointer(&$array, $set_key) {
	reset($array);
	while ($set_key != key($array)) {
		next($array);
	}
}

function savedata($data, $file) {
	$file = str_replace(" ", "-", $file);
	$fp = fopen(LOCAL_CACHE.$file, "w+");
	fwrite($fp, $data);
	fclose($fp);
}

function perm($path) {
	clearstatcache();
	$configmod = substr(sprintf('%o', fileperms($path)),  - 4);
	if ($configmod !== '0777') {
		chmod($path, 0777);
		clearstatcache();
		$configmod = substr(sprintf('%o', fileperms($path)),  - 4);
		if ($configmod !== '0777') {
		 	if (DEBUG == true) {
			print 'Error with <strong>'.$path.'</strong>. Please chmod -c 777 this file/directory!';
			die();
			}
		}
	}
}

function fetch($url) {
	if (function_exists('curl_exec')) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1)Gecko/20061010 Firefox/2.0');
		return curl_exec($ch);
		curl_close($ch);
	}
	else {
		$snoop = new Snoopy;
		$snoop->agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1)Gecko/20061010 Firefox/2.0';
		$snoop->fetch($url);
		return $snoop->results;
	}
}

function title($keyword = THIS_PAGE) {
	switch ($keyword): case 'index.php':
		print "<title>".SITE_NAME."</title>\n";
		break;
	case 'sitemap.php':
		print "<title>".SITE_NAME." &raquo; Sitemap</title>\n";
		break;
	default:
		print "<title>".SITE_NAME." &raquo; ".THIS_PAGE_KEYWORD."</title>\n";
		endswitch;
}

function metakeywords($keyword=THIS_PAGE) {
	switch ($keyword): case 'index':
		$firstKey = SITE_NAME;
		break;
	case 'sitemap':
		$firstKey = SITE_NAME;
		break;
	default:
		$firstKey = THIS_PAGE_KEYWORD;
		endswitch;
		echo'<meta name="keywords" content="'.$firstKey.'" />'."\n";
}
	
function metadescription() {
		echo'<meta name="description" content="';
		markov(3, 25);
		echo'." />'."\n";
}

function links($items = '999999', $ord = 'ASCE') {
	$keywords = file(FILE_KEYWORDS);
	$keywords = array_map('trim', $keywords);
	switch ($ord): case 'RAND':
		@shuffle($keywords);
		foreach($keywords as $keyword) {
			print "\n".'<a href="http://'.THIS_DOMAIN.'/'.str_replace(" ", "-", $keyword).'" title="'.$keyword.'">'.$keyword.'</a><br />';
			$n++;
			if ($n >= $items) {
				break;
			}
		}
		print "\n";
	break;
	case 'DESC':
		arsort($keywords);
		foreach($keywords as $keyword) {
			print "\n".'<a href="http://'.THIS_DOMAIN.'/'.str_replace(" ", "-", $keyword).'" title="'.$keyword.'">'.$keyword.'</a><br />';
			$n++;
			if ($n >= $items) {
				break;
			}
		}
		print "\n";
	break;
	default:
		asort($keywords);
		foreach($keywords as $keyword) {
			print "\n".'<a href="http://'.THIS_DOMAIN.'/'.str_replace(" ", "-", $keyword).'" title="'.$keyword.'">'.$keyword.'</a><br />';
			$n++;
			if ($n >= $items) {
				break;
			}
		}
	print "\n";
	endswitch;
}

function navigation($keyword = THIS_PAGE_KEYWORD) {
	$keyword_file = file(FILE_KEYWORDS);
	$keyword_file = array_map('trim', $keyword_file);
	asort($keyword_file);
	$key = array_search($keyword, $keyword_file);
	setPointer($keyword_file, $key);
	$prev = prev($keyword_file);
	setPointer($keyword_file, $key);
	$next = next($keyword_file);
	if($prev !== false) {
		$navigation = "\n".'<div style="float:left;text-align:left;">&laquo; <a href="http://'.THIS_DOMAIN.'/'.str_replace(" ", "-", $prev).'" title="'.$prev.'">'.$prev.'</a></div>';
	}
	if($next !== false) {
		$navigation .= "\n".'<div style="float:right;text-align:right;"><a href="http://'.THIS_DOMAIN.'/'.str_replace(" ", "-", $next).'" title="'.$next.'">'.$next.'</a> &raquo;</div>';
	}
	$navigation .= "\n".'<div style="clear:both"></div>'."\n";
	print $navigation;
}

function domain() {
	print 'http://'.THIS_DOMAIN.'/';
}

function template() {
	print LOCAL_TEMPLATE;
}

function sitename() {
	print SITE_NAME;
}

function keyword() {
	print THIS_PAGE_KEYWORD;
}	
?>