<?php //AUTO XML-RPC PINGER
require_once('weblog_pinger.php');
require_once("functions.php");

$cookpass = $_COOKIE["yacg"];
$adminpass = md5($adminpass);
if($cookpass) {
    if($cookpass == $adminpass){ 

$keywords = @file("../".FILE_KEYWORDS."");
$title = ucwords(strtolower(htmlentities(trim($keywords[0]))));
$url = "http://".THIS_DOMAIN;
$pingomatic = "http://pingomatic.com/ping/?title=".urlencode($title)."&blogurl=".urlencode($url)."&rssurl=".urlencode($url)."%2Ffeed%2F&chk_weblogscom=on&chk_blogs=on&chk_technorati=on&chk_feedburner=on&chk_syndic8=on&chk_newsgator=on&chk_feedster=on&chk_myyahoo=on&chk_pubsubcom=on&chk_blogdigger=on&chk_blogrolling=on&chk_blogstreet=on&chk_moreover=on&chk_weblogalot=on&chk_icerocket=on&chk_newsisfree=on&chk_topicexchange=on&chk_audioweblogs=on&chk_rubhub=on&chk_geourl=on&chk_a2b=on&chk_blogshares=on";
$pinger = new Weblog_Pinger();
echo $pinger->ping_all($title,$url);
$ping = fetch_admin($pingomatic);
echo "Done! All <strong>pings</strong> have been sent!";
echo "<br />";
echo "<a href=\"javascript:history.go(-1)\">Go back</a>";
}
else{
    echo($incorrect_password);
    die();
    }
}
else{
echo($not_logged_in);
}
?>