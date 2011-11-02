<?php // BASIC FUNCTIONS
if (DEBUG == false) {
	error_reporting(0);
}
function error404() {
	header("HTTP/1.0 404 Not Found");
	die(ERROR_404);
}
function setpointer(&$array, $set_key) {
	reset($array);
	while ($set_key != key($array)) {
		next($array);
	}
}
function savecache($data, $file) {
	if (CACHE == true) {
		$file = str_replace(" ", "-", $file);
		$fp = fopen(LOCAL_CACHE.$file, "w+");
		fwrite($fp, $data);
		fclose($fp);
	}
}
function loadcache($file_path) {
	$file_path = LOCAL_CACHE.str_replace(" ", "-", $file_path);
	if (file_exists($file_path) && (time() - CACHE_TIME < filemtime($file_path))) {
		$cache = @file_get_contents($file_path);
		return $cache;
	}
	else {
		return false;
	}
}
function perm($path) {
	clearstatcache();
	$configmod = substr(sprintf('%o', fileperms($path)), - 4);
	if ($configmod !== '0777') {
		chmod($path, 0777);
		clearstatcache();
		$configmod = substr(sprintf('%o', fileperms($path)), - 4);
		if ($configmod !== '0777') {
			if (DEBUG == true) {
				print CHMOD_ERROR_1;
				die();
			}
		}
	}
}
function fetch($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
	if (PROXY == true) {
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP.":".PROXY_PORT);
 	}
	$response = curl_exec($ch);
	curl_close($ch);
	return $response; 
}
function title($keyword = THIS_PAGE) {
	switch ($keyword): 
		case 'index.php':
			print "<title>".SITE_NAME."</title>\n";
		break;
		case 'sitemap.php':
			print "<title>".SITE_NAME." &raquo; Sitemap</title>\n";
		break;
		default:
			print "<title>".SITE_NAME." &raquo; ".THIS_PAGE_KEYWORD."</title>\n";
	endswitch;
}
function metakeywords($keyword = THIS_PAGE) {
	switch ($keyword): 
		case 'index':
			$firstKey = SITE_NAME;
		break;
		case 'sitemap':
			$firstKey = SITE_NAME;
		break;
		default:
			$firstKey = THIS_PAGE_KEYWORD;
	endswitch;
	print '<meta name="keywords" content="'.$firstKey.'" />'."\n";
}
function metadescription() {
	$html = '<meta name="description" content="';
	$html .= markov(3, 25, 65, true);
	$html .= '." />'."\n";
	print $html;
}
function links($items = '999999', $ord = 'ASCE') {
	$keywords = file(FILE_KEYWORDS);
	$keywords = array_map('trim', $keywords);
	$n = 0;
	switch ($ord): 
	case 'RAND':
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
	setpointer($keyword_file, $key);
	$prev = prev($keyword_file);
	setpointer($keyword_file, $key);
	$next = next($keyword_file);
	$navigation = "";
	if ($prev !== false) {
		$navigation .= "\n".'<div style="float:left;text-align:left;">&laquo; <a href="http://'.THIS_DOMAIN.'/'.str_replace(" ", "-", $prev).'" title="'.$prev.'">'.$prev.'</a></div>';
	}
	if ($next !== false) {
		$navigation .= "\n".'<div style="float:right;text-align:right;"><a href="http://'.THIS_DOMAIN.'/'.str_replace(" ", "-", $next).'" title="'.$next.'">'.$next.'</a> &raquo;</div>';
	}
	print $navigation;
}
function get_src($string, $strict=false) {
	$inner = $strict?'[a-z0-9:?=&@/._-]+?':'.+?';
	preg_match_all("|src\=([\"'`])(".$inner.")\\1|i", $string, &$matches);
	$image["src"] = $matches[2];
	return $image;
}
function replacer($text) {
	$check = "@:@s";
	$replace = "&#58;";
	return preg_replace($check, $replace, $text[0]);
}
function sanitize_xhtml($source) {
	$exceptions = "text-align|text-decoration";
	$source = "<parse>".$source."</parse>";
	$source = preg_replace_callback("@>(.*)<@Us", "replacer", $source);
	$source = preg_replace('@([^;"]+)?(?<!'.$exceptions.')(?<!\>\w):(?!\/\/(.+?)\/|<|>)((.*?)[^;"]+)(;)?@is', '', $source);
	$source = preg_replace('@[a-z]*=""@is', '', $source);
	$source = preg_replace('/class="(.*?)"/', '', $source);
	$source = preg_replace('/id="(.*?)"/', '', $source);
	$source = preg_replace('/usemap="(.*?)"/', '', $source);
	$source = preg_replace('/name="(.*?)"/', '', $source);
	$source = preg_replace('/xml:lang="(.*?)"/', '', $source);
	$source = preg_replace('/lang="(.*?)"/', '', $source);
	$source = preg_replace('/\s\s+/', ' ', $source);
	$source = preg_replace('/ >/','>', $source);
	$source = preg_replace('/<b>/', '<strong>', $source);
	$source = preg_replace('/<\/b>/', '</strong>', $source);
	$source = preg_replace('/<br>/', '<br />', $source);
	$source = preg_replace('/<span><\/span>/', '', $source);
	$source = preg_replace('/<p><\/p>/', '', $source);
	$source = preg_replace('/<a><\/a>/', '', $source);
	$source = str_replace(array('<parse>', '</parse>'), '', $source);
	return $source;
}
function indenter($buffer) {
	if (INDENT == true) {
	$indenter = '  ';
	$buffer = str_replace("\n", '', $buffer);
	$buffer = str_replace("\r", '', $buffer);
	$buffer = str_replace("\t", '', $buffer);
	$buffer = ereg_replace(">( )*", ">", $buffer);
	$buffer = ereg_replace("( )*<", "<", $buffer);
	$level = 0;
	$buffer_len = strlen($buffer);
	$pt = 0;
	while ($pt < $buffer_len) {
		if ($buffer{$pt} === '<') {
			$started_at = $pt;
			$tag_level = 1;
			if ($buffer{$pt+1} === '/') {
				$tag_level = -1;
			}
			if ($buffer{$pt+1} === '!') {
				$tag_level = 0;
			}
			while ($buffer{$pt} !== '>') {
				$pt++;
			}
			if ($buffer{$pt-1} === '/') {
				$tag_level = 0;
			}
			$tag_lenght = $pt+1-$started_at;
			if ($tag_level === -1) {
				$level--;
			}
			$array[] = str_repeat($indenter, $level).substr($buffer, $started_at, $tag_lenght);
			if ($tag_level === 1) {
				$level++;
			}
		}
		if (($pt+1) < $buffer_len) {
			if ($buffer{$pt+1} !== '<') {
				$started_at = $pt+1;
				while ($buffer{$pt} !== '<' && $pt < $buffer_len) {
					$pt++;
				}
				if ($buffer{$pt} === '<') {
					$tag_lenght = $pt-$started_at;
					$array[] = str_repeat($indenter, $level).substr($buffer, $started_at, $tag_lenght);
				}
			} else {
				$pt++;
			}
		} else {
			break;
		}
	}
	$buffer = implode($array, "\n");
	$buffer = str_replace("<!--","<!--\n",$buffer);
	$buffer = str_replace("//-->","\n//-->",$buffer);
	preg_match_all('/<a(.*?)>\\s*/m', $buffer, $result, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($result[0]); $i++) {
		$buffer = str_replace($result[0][$i],trim($result[0][$i]),$buffer);
	}
	preg_match_all('%\\s*</a>%m', $buffer, $result, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($result[0]); $i++) {
		$buffer = str_replace($result[0][$i],trim($result[0][$i]),$buffer);
	}
	preg_match_all('/<textarea(.*?)>\\s*/m', $buffer, $result, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($result[0]); $i++) {
		$buffer = str_replace($result[0][$i],trim($result[0][$i]),$buffer);
	}
	preg_match_all('%\\s*</textarea>%m', $buffer, $result, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($result[0]); $i++) {
		$buffer = str_replace($result[0][$i],trim($result[0][$i]),$buffer);
	}
	preg_match_all('/<title>\\s*/m', $buffer, $result, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($result[0]); $i++) {
		$buffer = str_replace($result[0][$i],trim($result[0][$i]),$buffer);
	}
	preg_match_all('%\\s*</title>%m', $buffer, $result, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($result[0]); $i++) {
		$buffer = str_replace($result[0][$i],trim($result[0][$i]),$buffer);
	}
	return $buffer;
	}
	else {
		return $buffer;
	}
}
function domain() {
	print 'http://'.THIS_DOMAIN.'/';
}
function description() {
	print SITE_DESCRIPTION;
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
