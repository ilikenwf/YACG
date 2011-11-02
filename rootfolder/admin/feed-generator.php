<?php // FEED GENERATOR
require_once("functions.php");
$keyarr = file('../../'.FILE_KEYWORDS);
$keyarr = array_map('rtrim', $keyarr);
$keyarr = array_reverse($keyarr);
chdir('../..');
$out2 = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<rss version=\"2.0\">
<channel>
<title>".SITE_NAME."</title>
<link>http://".THIS_DOMAIN."/</link>
<description><![CDATA[".SITE_DESCRIPTION."]]></description>
<language>en-us</language>
<pubDate>".date("D, d M Y H:i:s T")."</pubDate>
<lastBuildDate>".date("D, d M Y H:i:s T")."</lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<ttl>5</ttl>";
$i = 0;
foreach($keyarr as $key) {
  $out2 .= "\n" . '<item>';
  $out2 .= "\n" . '<title>'.cut_cat($key).'</title>';
  $out2 .= "\n" . '<link>'.k2url($key).'</link>';
  $out2 .= "\n" . '<description>';
  if (PREVIEW_HOOK != 'markov') {
    $out2 .= substr(strip_tags(cache(PREVIEW_HOOK, array(cut_cat($key)), cut_cat($key), true)), 0, 300)."...";
    $out2 .= "\n" . '</description>';
    $out2 .= "\n" . '<pubDate>'.date("D, d M Y H:i:s T", ktime($key, PREVIEW_HOOK)).'</pubDate>';
  } else {
    $out2 .= cache('markov', array(5, 50, 65), cut_cat($key), true);
    $out2 .= "\n" . '</description>';
    $out2 .= "\n" . '<pubDate>'.date("D, d M Y H:i:s T").'</pubDate>';
  }
  $out2 .= "\n" . '<guid isPermaLink="false">'.k2url($key).'</guid>';
  $out2 .= "\n" . '</item>';
  $i++;
  if ($i == 20) break;
}
$out2 .= "\n</channel>\n</rss>";
file_put_contents("feed.xml", $out2);
print "Done! Your <strong>feed</strong> has been generated!<br /><a href=\"javascript:history.go(-1)\">Go back</a>";
?>