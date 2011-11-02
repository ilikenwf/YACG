<?php //AUTO XML-RPC PINGER
ignore_user_abort(true);
set_time_limit(0);
require_once('weblog_pinger.php');
require_once("functions.php");
$cookpass = $_COOKIE["yacg"];
if (!$cookpass) {
	$cookpass = md5($_GET["password"]);
}
if ($cookpass) {
	if ($cookpass == md5(PASSWORD)) {
		$url = 'http://'.THIS_DOMAIN.'/';
		$pingomatic = "http://pingomatic.com/ping/?title=".urlencode(SITE_NAME)."&blogurl=".urlencode($url)."&rssurl=".urlencode($url)."%2Ffeed.xml%2F&chk_weblogscom=on&chk_blogs=on&chk_technorati=on&chk_feedburner=on&chk_syndic8=on&chk_newsgator=on&chk_feedster=on&chk_myyahoo=on&chk_pubsubcom=on&chk_blogdigger=on&chk_blogrolling=on&chk_blogstreet=on&chk_moreover=on&chk_weblogalot=on&chk_icerocket=on&chk_newsisfree=on&chk_topicexchange=on&chk_audioweblogs=on&chk_rubhub=on&chk_geourl=on&chk_a2b=on&chk_blogshares=on";
		$pinger = new Weblog_Pinger();
		print $pinger->ping_all(SITE_NAME,$url);
		$ping = fetch($pingomatic);
		print "Done! All <strong>pings</strong> have been sent!";
		print "<br />";
		print "<a href=\"javascript:history.go(-1)\">Go back</a>";
	}
	else {
		print(INCORRECT_PASSWORD);
		die();
	}
}
else {
	print(NOT_LOGGED_IN);
}
?>