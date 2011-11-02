<?php //MAIN ENGINE

// REQUIRE CONFIG.INC.PHP AND ALL THE FILES ON LOCAL_HOOKS
require_once("./config.inc.php");
if (is_dir(LOCAL_HOOKS)) {
	if ($dh = opendir(LOCAL_HOOKS)) {
		while (($file = readdir($dh)) !== false) {
			if ($file == "." || $file == ".." || empty($file)) {
				$my_dump[] = $file;
			} elseif (substr($file,  - 4) == '.php') {
				require_once(LOCAL_HOOKS.$file);
			}
			if ($i >= $nr_files) {
				$i = 0;
			} elseif ($i < $nr_files) {
				++$i;
			}
		}
		closedir($dh);
	}
}

// GET DOMAIN NAME, REQUESTED PAGE AND OTHER INFORMATION
define('_IP', $_SERVER["REMOTE_ADDR"]);
define('FOLDERNAME', dirname($_SERVER['PHP_SELF']));
if (FOLDER == true) {
	$requested_page = str_replace(FOLDERNAME, "", $_SERVER['REQUEST_URI']);
	$domain_name = $_SERVER['HTTP_HOST'].FOLDERNAME;
} else {
	$requested_page = $_SERVER['REQUEST_URI'];
	$domain_name = $_SERVER['HTTP_HOST'];
}
if (DOMAIN_TYPE == true) {
	if (preg_match("/www\./", $domain_name)) {
		define('THIS_DOMAIN', $domain_name);
	} else {
		ignore_user_abort(true);
		header("Pragma: no-cache");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: http://www.$domain_name$requested_page");
		header("Connection: close");
		exit;
	}
} else {
	define('THIS_DOMAIN', $domain_name);
}

// GET THE MAIN KEYWORD FOR EACH PAGE
$keywords = file(FILE_KEYWORDS);
$pagekeyword = trim(str_replace("/", "", str_replace("-", " ", $requested_page)));

switch ($requested_page): case '/':
	define('THIS_PAGE', 'index.php');
	define('THIS_PAGE_KEYWORD', trim($keywords[0]));
	break;
case '/sitemap':
	define('THIS_PAGE', 'sitemap.php');
	define('THIS_PAGE_KEYWORD', 'Sitemap');
	break;
default:
	define('THIS_PAGE', 'results.php');
	define('THIS_PAGE_KEYWORD', $pagekeyword);
	endswitch;

// CHECK FOR CORRECT PERMISSIONS
perm(LOCAL_CACHE);
perm(FILE_KEYWORDS);
perm("./feed.xml");
perm("./sitemap.xml");
perm("./ips.txt");

// CHECK IF FILE_KEYWORDS EXISTS
$test = @file_get_contents(FILE_KEYWORDS);
if ($test == false) {
	echo'Error! <strong>keywords.txt</strong> doesn\'t exist!';
	exit;
}
else {
	if (preg_match("/[^\w\s]/", $test)) {
		echo'Error with <strong>keywords.txt</strong>! Keywords can only contain letters &amp; numbers!';
		exit;
	}
}

// CHECK TO SEE IF PAGE IS IN KEYWORD FILE OR ELSE GIVE 404 ERROR
if(THIS_PAGE != '/' && THIS_PAGE != 'sitemap.php'){
	if (!preg_match("/$pagekeyword/", @file_get_contents(FILE_KEYWORDS))){
		give404($requested_page);
	}
}

// LOAD THE TEMPLATES AND LET THEM TAKE OVER FROM HERE
require_once(LOCAL_TEMPLATE.THIS_PAGE);
?>