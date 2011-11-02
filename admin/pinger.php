<?php //AUTO XML-RPC PINGER
require_once('weblog_pinger.php');
require_once("admin-hooks.php"); 
require_once("../config.inc.php");

// GET DOMAIN NAME AND OTHER INFORMATION
if(FOLDER == true){
	$requested_page = str_replace(FOLDERNAME,"",$_SERVER['REQUEST_URI']);
	$domain_name = $_SERVER['HTTP_HOST'].FOLDERNAME;
} else{
// Thanks to ngkong for the fix
	$requested_page	= $_SERVER['REQUEST_URI'];
	$domain_name	= $_SERVER['HTTP_HOST'];
}
if(DOMAIN_TYPE == true){
	if(preg_match("/www\./", $domain_name)) : 
	define('THIS_DOMAIN', $domain_name); 
	else: 
		ignore_user_abort(true);
		header("Pragma: no-cache");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: http://www.$domain_name$requested_page"); 
		header("Connection: close");
		exit;
	endif;
} else{
	define('THIS_DOMAIN', $domain_name); 	
}

$keywords = @file("../".FILE_KEYWORDS."");
$title = ucwords(strtolower(htmlentities(trim($keywords[0]))));
$url = "http://".THIS_DOMAIN;
$pingomatic = "http://pingomatic.com/ping/?title=".urlencode($title)."&blogurl=".urlencode($url)."&rssurl=".urlencode($url)."%2Ffeed%2F&chk_weblogscom=on&chk_blogs=on&chk_technorati=on&chk_feedburner=on&chk_syndic8=on&chk_newsgator=on&chk_feedster=on&chk_myyahoo=on&chk_pubsubcom=on&chk_blogdigger=on&chk_blogrolling=on&chk_blogstreet=on&chk_moreover=on&chk_weblogalot=on&chk_icerocket=on&chk_newsisfree=on&chk_topicexchange=on&chk_audioweblogs=on&chk_rubhub=on&chk_geourl=on&chk_a2b=on&chk_blogshares=on";
$pingoat = "http://pingoat.com/index.php?pingoat=go&blog_name=".urlencode($title)."&blog_url=".urlencode($url)."&rss_url=".urlencode($url)."%2Ffeed%2F&cat_0=1&id%5B%5D=0&id%5B%5D=1&id%5B%5D=2&id%5B%5D=3&id%5B%5D=4&id%5B%5D=5&id%5B%5D=6&id%5B%5D=7&id%5B%5D=8&id%5B%5D=9&id%5B%5D=10&id%5B%5D=11&id%5B%5D=12&id%5B%5D=13&id%5B%5D=14&id%5B%5D=15&id%5B%5D=16&id%5B%5D=17&id%5B%5D=18&id%5B%5D=19&id%5B%5D=20&id%5B%5D=21&id%5B%5D=22&id%5B%5D=23&id%5B%5D=24&cat_1=1&id%5B%5D=25&id%5B%5D=26&id%5B%5D=27&id%5B%5D=28&id%5B%5D=29&id%5B%5D=30&id%5B%5D=31&id%5B%5D=32&id%5B%5D=33&id%5B%5D=34&id%5B%5D=35&id%5B%5D=36&id%5B%5D=37&id%5B%5D=38&id%5B%5D=39&cat_2=1&id%5B%5D=40&id%5B%5D=41&id%5B%5D=42&id%5B%5D=43&id%5B%5D=44&id%5B%5D=45&id%5B%5D=46&id%5B%5D=47&id%5B%5D=48";
$pinger = new Weblog_Pinger();
echo $pinger->ping_all($title,$url);
$ping1 = fetch_admin($pingomatic);
$ping2 = fetch_admin($pingoat);
echo "Done! All <strong>pings</strong> have been sent!";
echo "<br />";
echo "<a href=\"javascript:history.go(-1)\">Go back</a>";
?>