<?php
function cb($keyword = THIS_PAGE_CATEGORY, $numresults = 3, $gendescr = true, $thumbs = true, $bare = false, $cbcategory = '-1', $cbsubcategory = '-1') {
  if (SHOW_ADS) {
    $result = array();

    $response = fetch('http://www.clickbank.com/marketplace.htm', 'method=Sort&c='.$cbcategory.'&subc='.$cbsubcategory.'&keywords='.urlencode($keyword).'&sortBy=popularity&billingType=ALL&locale=ALL&i=10');

    preg_match_all("/<\/b>\s+<a class=\"siteHeader\"[^>]+?href=\"(.+?)\">([^<]+?)<\/a>\s+?<\/b>\s+?(.+?)\s+?<br>/ims", $response, $matches);

    for($i=0;$i<$numresults;$i++) {
      $result[$i]['url'] = str_replace('zzzzz', CLICKBANK_ID, $matches[1][$i]).'/?tid='.THIS_DOMAIN;
      $result[$i]['title'] = $matches[2][$i];
      $result[$i]['description'] = $matches[3][$i];
      $result[$i]['thumbnail'] = '';
      if ($thumbs || $gendescr) {
        $salespage = fetch($matches[1][$i]);
        if ($thumbs) {
          foreach($snoopy->headers as $header) {
            if (preg_match("/Location: http:\/\/(.+?)[\?\/].*?hop=0/im", $header, $location)) {
              $thumb = googleimg($location[1], 1, true);
              $result[$i]['thumbnail'] = $thumb[0]['thumbnail'];
              break;
            }
          }
        }
        if ($gendescr) {
          if (preg_match("/<meta[^>]+?[\"']description[\"'][^>]+?content=[\"]([^\"]+?)[\"]/im", $salespage, $description) && strlen($description[1]) > 150) {
            $result[$i]['description'] = $description[1];
          } elseif (preg_match_all("/<h(\d).*?>(.+?)<\/h\d>/im", $salespage, $headers) && count($headers[2]) > 2) {
            $result[$i]['description'] = '';
            foreach($headers[2] as $header) {
              $result[$i]['description'] .= ' '.$header;
              if (strlen($result[$i]['description']) > 500) break;
            }
          }
        }
      }
    }

    if (!$bare) {
      foreach($result as $ad) {
        print '<div style="margin:5px"><a href="'.$ad['url'].'">'.$ad['title'].'</a><br />';
        if ($thumbs) print '<div style="float:left;margin:5px"><a href="'.$ad['url'].'"><img src="'.$ad['thumbnail'].'" /></a></div>';
        print $ad['description'].'<br style="clear:both"></div>';
      }
      $result = true;
    }

    return $result;
  }
}
?>