<?php // DIGG DESCRIPTION SCRAPER HOOK
function digg($keyword = THIS_PAGE_KEYWORD, $items = 5) {
  $feed = new SimplePie('http://digg.com/rss_search?search='.urlencode($keyword).'&area=all&type=both&age=all&section=news');
  $feed->handle_content_type();
  
  $i = 0;
  foreach ($feed->get_items() as $item) {
    print "\n<p>".$item->get_description()."</p><br />";
    $i++;
    if ($i > $items) break;
  }
}
?>