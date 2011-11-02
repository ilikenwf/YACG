<?php // YAHOO IMAGES SCRAPER HOOK
if (!DEBUG) error_reporting(0);

function yahooimg($keyword = THIS_PAGE_KEYWORD, $items = 5) {
  $yahooimg_results = loadcache($keyword.".yahooimg");
  if ($yahooimg_results == false) {
    $yahooimg_results = fetch('http://api.search.yahoo.com/ImageSearchService/V1/imageSearch?appid=YahooDemo&query='.urlencode($keyword).'&results='.$items);
    savecache($yahooimg_results, $keyword.".yahooimg");
  }
  if (preg_match_all('/<Url>(.*)<\/Url>/', $yahooimg_results, $y)) {
    $i = 0;
    $n = 1;
    while ($i < $items) {
      $file1 = basename($y[1][$n]).'.jpg';
      if (!file_exists(LOCAL_IMAGE_CACHE.$file1)) {
        $yahooimg1 = fetch($y[1][$n].'.jpg');
        file_put_contents(LOCAL_IMAGE_CACHE.$file1, $yahooimg1);
      }
      $yahooimg = "\n<a href=\"http://".THIS_DOMAIN.'/img/'.$file1."\">\n<img src=\"http://".THIS_DOMAIN.'/img/'.$file1.'"  alt="'.$keyword.'" /></a>';
      $n = $n+2;
      $i++;
    }
  }	else {
    return printerror(YAHOO_ERROR_1);
  }
  print $yahooimg."\n";
}
?>