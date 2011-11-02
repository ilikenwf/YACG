<?php // DIGG DESCRIPTION SCRAPER HOOK
if (!DEBUG) error_reporting(0);

function digg($keyword = THIS_PAGE_KEYWORD, $items = 5) {
  $digg = '';
  $digg_results = fetch('http://digg.com/rss_search?search='.urlencode($keyword).'&area=all&type=both&age=all&section=news');
  $cow = '';
  if (preg_match_all('/<description>(.*?)<\/description>/s', $digg_results, $d)) {
    $d[0] = array_slice($d[0], 1);
    foreach ($d[0] as $description) {
      if ($cow < $items) {
        $description = str_replace(array("<description>", "</description>"), array("<p>", "</p>"), $description);
        $digg .= "\n".$description."<br />";
      }
      $cow++;
    }
  } else {
    return printerror(DIGG_ERROR_1);
  }
  print $digg."\n";
}
?>