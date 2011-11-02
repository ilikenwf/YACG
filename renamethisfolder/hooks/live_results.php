<?php // LIVE.COM RESULTS SCRAPER HOOK
if (!DEBUG) error_reporting(0);

function live($keyword = THIS_PAGE_KEYWORD, $items = 5) {
  $live = '';
  $cow = 0;
  $feed = fetch('http://search.msn.com/results.aspx?q='.urlencode($keyword).'&format=rss&FORM=RSNR');
  preg_match_all('#<title>(.*?)</title>#', $feed, $title, PREG_SET_ORDER);
  preg_match_all('#<link>(.*?)</link>#', $feed, $link, PREG_SET_ORDER);
  preg_match_all('#<description>(.*?)</description>#', $feed, $description, PREG_SET_ORDER);
  $nr = count($title);
  if ($nr == 1) {
    return printerror(LIVE_ERROR_1);
  }	elseif ($nr > 1) {
    for ($counter = 1; $counter < 11; $counter++) {
      if (empty($title[$counter][1])) {
        return printerror(LIVE_ERROR_2);
      } elseif (!empty($title[$counter][1])) {
        $title[$counter][1] = str_replace(array("&amp;", "&apos;"), array("&", "'"), $title[$counter][1]);
        $description[$counter][1] = str_replace(array("&amp;", "&apos;"), array("&", "'"),	$description[$counter][1]);
        if ($cow < $items) {
          $live .= "\n<h3>".$title[$counter][1]."</h3>";
          $live .= "\n<p>".$description[$counter][1]."</p>";
          $live .= "\n<p style=\"text-align:right;\"><a href=\"".$link[$counter][1]."\" rel=\"external nofollow\">Read more...</a></p>";
        }
        $cow++;
      }
    }
    print $live."\n";
  }
}
?>