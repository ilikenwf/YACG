<?php //AUTO XML-RPC PINGER
require_once('../includes/xmlrpc.class.php');
require_once('../includes/weblog_pinger.php');
require_once('functions.php');
$url = 'http://'.THIS_DOMAIN.'/';
$pingomatic = "http://pingomatic.com/ping/?title=".urlencode(SITE_NAME)."&blogurl=".urlencode($url)."&rssurl=".urlencode($url)."%2Ffeed.xml%2F&chk_weblogscom=on&chk_blogs=on&chk_technorati=on&chk_feedburner=on&chk_syndic8=on&chk_newsgator=on&chk_feedster=on&chk_myyahoo=on&chk_pubsubcom=on&chk_blogdigger=on&chk_blogrolling=on&chk_blogstreet=on&chk_moreover=on&chk_weblogalot=on&chk_icerocket=on&chk_newsisfree=on&chk_topicexchange=on&chk_audioweblogs=on&chk_rubhub=on&chk_geourl=on&chk_a2b=on&chk_blogshares=on";
$pinger = new Weblog_Pinger();
$pinger->ping_all(SITE_NAME,$url);
$ping = fetch($pingomatic, false);
//print $ping;
print "Done! All <strong>pings</strong> have been sent!<br /><a href=\"javascript:history.go(-1)\">Go back</a>";
?>