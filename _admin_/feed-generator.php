<?php // FEED GENERATOR
ignore_user_abort(true);
set_time_limit(0);
require_once("functions.php");
$cookpass = $_COOKIE["yacg"];
if (!$cookpass) {
	$cookpass = md5($_GET["password"]);
}
if ($cookpass) {
	if ($cookpass == md5(PASSWORD)) {
		$list = file("../".FILE_KEYWORDS."");
		$out2 = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n<rss version=\"2.0\">\n<channel>\n<title>".SITE_NAME."</title><link>http://".THIS_DOMAIN."/</link>\n<description><![CDATA[".SITE_DESCRIPTION."]]></description>\n<language>en</language>";
		foreach ($list as $temporal) {
			$temporal2 = trim(str_replace(" ", "-", $temporal));
			$markov = markov(3, 50, 65, true);
			$out2 .= "\n" . '<item>';
			$out2 .= "\n" . '<title>'.trim($temporal).'</title>';
			$out2 .= "\n" . '<link>http://'.THIS_DOMAIN.'/'.$temporal2.'</link>';
			$out2 .= "\n" . '<guid isPermaLink="false">http://'.THIS_DOMAIN.'/'.$temporal2.'</guid>';
			$out2 .= "\n" . '<description>';
			$out2 .= "\n" . $markov;
			$out2 .= "\n" . '</description>';
			$out2 .= "\n" . '</item>';
		}
		$out2 .= "\n</channel>\n</rss>";
		$fp = fopen("../feed.xml", "w+");
		fwrite($fp, $out2);
		fclose($fp);
		print "Done! Your <strong>feed</strong> has been generated!";
		print "<br />";
		print "<a href=\"javascript:history.go(-1)\">Go back</a>";
	}
	else{
		print INCORRECT_PASSWORD;
		die();
	}
}
else{
	print NOT_LOGGED_IN;
}
?>