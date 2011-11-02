<?php
header("Content-Type: text/html;charset=utf-8");
ignore_user_abort(true);
set_time_limit(0);
require_once("./config.inc.php");
if (DEBUG == false) {
	error_reporting(0);
}
else {
	error_reporting(E_ALL ^ E_NOTICE);
}
// GET MAIN VARIABLES
$folder = dirname($_SERVER['PHP_SELF']);
if ($folder == "/") {
	$requested_page = $_SERVER['REQUEST_URI'];
}
else {
	$requested_page = strlen($folder);
	$requested_page = substr($_SERVER['REQUEST_URI'], $requested_page);
}
$pagekeyword = str_replace("-", " ", $requested_page);
$pagekeyword = trim(str_replace("/", "", $pagekeyword));
$keywords = file(FILE_KEYWORDS);
switch ($requested_page):
case '/':
	define('THIS_PAGE', 'index.php');
	define('THIS_PAGE_KEYWORD', trim($keywords[0]));
	break;
case '/sitemap':
	define('THIS_PAGE', 'sitemap.php');
	define('THIS_PAGE_KEYWORD', 'Sitemap');
	break;
case '/contact-us':
	define('THIS_PAGE', 'contact-us.php');
	define('THIS_PAGE_KEYWORD', 'Contact Us');
	break;
default:
	define('THIS_PAGE', 'page.php');
	define('THIS_PAGE_KEYWORD', $pagekeyword);
endswitch;
require_once("./thesarus.inc.php");
require_once(LOCAL_HOOKS."main.php");
if (CACHE == true) {
	if (THIS_PAGE != 'contact-us.php' && THIS_PAGE != 'sitemap.php' && THIS_PAGE != 'index.php') {
		$cachefile_path = str_replace("/", "", $requested_page);
		if ($cachefile_path != '') {
		$cachefile_path = $cachefile_path.".html";
		$cachefile_path = LOCAL_CACHE.$cachefile_path;
		$cachefile_name = str_replace(LOCAL_CACHE, "", $cachefile_path);
		if (file_exists($cachefile_path) && (time() - CACHE_TIME < filemtime($cachefile_path))) {
			$cache = loadcache($cachefile_name);
			print $cache;
			if (DEBUG == true) {
				print "\n"."<!-- Cached on ".date('F jS, Y H:i', filemtime($cachefile_path))." -->";
			}
			die();
		}
		}
	}
}
else {
	if (DEBUG == true) {
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$start_time = $time;
	}
}
ob_start("indenter");
// REQUIRE ALL THE FILES ON LOCAL_HOOKS
if (is_dir(LOCAL_HOOKS)) {
	if ($dh = opendir(LOCAL_HOOKS)) {
		while (($file = readdir($dh)) !== false) {
			if ($file == "." || $file == ".." || empty($file)) {
				$my_dump[] = $file;
			}
			elseif (substr($file,  - 4) == '.php') {
				require_once(LOCAL_HOOKS.$file);
			}
		}
		closedir($dh);
	}
}
// CHECK IF FILE_KEYWORDS EXISTS
$keywords = @file_get_contents(FILE_KEYWORDS);
if ($keywords == false) {
	if (DEBUG == true) {
		print KEYWORDS_ERROR_1;
	}
	die();
}
else {
	// IF FILE_KEYWORDS EXISTS, CHECK FOR INTEGRITY
	if (preg_match("/[^\w\s]/", $keywords)) {
		if (DEBUG == true){
			print KEYWORDS_ERROR_2;
		}
		die();
	}
}
// CHECK FOR FILE PERMISSIONS
$files = array("./config.inc.php", LOCAL_CACHE, FILE_KEYWORDS, "./feed.xml", "./sitemap.xml", FILE_BOTS);
foreach ($files as $file) {
	perm($file);
}
// CHECK TO SEE IF PAGE IS IN KEYWORD FILE OR ELSE GIVE 404 ERROR
if (THIS_PAGE != '/' && THIS_PAGE != 'sitemap.php' && THIS_PAGE != 'contact-us.php') {
	if (!preg_match("/$pagekeyword/i", $keywords)) {
		error404();
	}
}
// LOAD THE TEMPLATES AND LET THEM TAKE OVER FROM HERE
require_once(LOCAL_TEMPLATE.THIS_PAGE);
if (CACHE == true) {
	if (THIS_PAGE != 'contact-us.php' && THIS_PAGE != 'sitemap.php' && THIS_PAGE != 'index.php') {
		$cachefile_data = indenter(ob_get_contents());
		savecache($cachefile_data, $cachefile_name);
	}
}
else {
	if (DEBUG == true) {
		$time = microtime();
		$time = explode(" ", $time);
		$time = $time[1] + $time[0];
		$end_time = $time;
		$total_time = ($end_time - $start_time);
		print "\n"."<!-- Generated in ".$total_time." seconds -->";
	}
}
?>