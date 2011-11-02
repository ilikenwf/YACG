<?php
function googleimg($keyword = THIS_PAGE_KEYWORD, $items = 5, $bare = false) {
  $output = array();

  $google_images = loadcache($keyword.".googleimg");

  if ($google_images == false) {
    $google_images = fetch("http://images.google.com/images?hl=en&q=".urlencode($keyword)."&btnG=Search+Images&gbv=1");
    savecache($google_images, $keyword.".googleimg");
  }

  if (preg_match_all("/href=\/imgres\?imgurl=(.+?)&.+?img src=([^\s]+?) width=(\d+) height=(\d+)/im", $google_images, $images)) {
    for ($i=0;$i<$items;$i++) {
      $filename = basename($images[1][$i]);
      
      if (!file_exists(LOCAL_IMAGE_CACHE.$filename)) {
        $img = fetch($images[1][$i]);
        file_put_contents(LOCAL_IMAGE_CACHE.$filename, $img);
        $img_thumb = fetch($images[2][$i]);
        file_put_contents(LOCAL_IMAGE_CACHE.'thumb_'.$filename, $img_thumb);
      }

      if ($bare) {
        $output[$i]['image'] = 'http://'.THIS_DOMAIN.str_replace('.', '', LOCAL_IMAGE_CACHE).$filename;
        $output[$i]['thumbnail'] = 'http://'.THIS_DOMAIN.str_replace('.', '', LOCAL_IMAGE_CACHE).'thumb_'.$filename;
        $output[$i]['width'] = $images[3][$i];
        $output[$i]['height'] = $images[4][$i];
      } else {
        print '<a rel="nofollow" title="'.$keyword.'" href="http://'.THIS_DOMAIN.str_replace('.', '', LOCAL_IMAGE_CACHE).$filename.'"><img alt="'.$keyword.'" width="'.$images[3][$i].'" height="'.$images[4][$i].'" src="http://'.THIS_DOMAIN.str_replace('.', '', LOCAL_IMAGE_CACHE).'thumb_'.$filename."\"></a>\n";
      }
    }

    return $output;
  } else {
    return printerror("Nothing was found");
  }
}
?>