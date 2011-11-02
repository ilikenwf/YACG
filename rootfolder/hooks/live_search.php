<?php // LIVE.COM RESULTS SCRAPER HOOK
function live($keyword = THIS_PAGE_KEYWORD, $items = 5, $bare = false, $market = 'en-US') {
  $feed = new SimplePie('http://search.msn.com/results.aspx?q='.urlencode($keyword).'&format=rss&FORM=RSNR&mkt='.$market);
  $feed->handle_content_type();
  
  $output = array();  
  $i = 0;  
  foreach ($feed->get_items() as $item) {
    if ($bare) { 
      $output[] = array('title' => $item->get_title(), 'description' => $item->get_description(), 'url' => $item->get_permalink());
    } else {
      print <<<HTML
<h3>{$item->get_title()}</h3>
<p>{$item->get_description()}</p>
<p style="text-align:right;">
  <a href="{$item->get_permalink()}" rel="external nofollow">Read more...</a>
</p>
HTML;
    }
    $i++;
    if ($i > $items) break;
  }
  return $output;
}
?>