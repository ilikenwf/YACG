<? //PHOTOBUCKET IMAGES HOOK
function photobucket($keyword = THIS_PAGE_KEYWORD, $items = 6, $bare = false) {
  $feed = new SimplePie('http://feed.photobucket.com/images/'.urlencode($keyword).'/feed.rss');
  $feed->handle_content_type();
  
  $output = array();  
  $i = 0;  
  foreach ($feed->get_items() as $item) {
    $url = $item->get_id();
    $filename = basename($url);
    $enclosure = $item->get_enclosure();
    
    if (!file_exists(LOCAL_IMAGE_CACHE.$filename)) {
      $img = fetch($url);
      file_put_contents(LOCAL_IMAGE_CACHE.$filename, $img);
      $img_thumb = fetch(urldecode($enclosure->get_thumbnail()));
      file_put_contents(LOCAL_IMAGE_CACHE.'thumb_'.$filename, $img_thumb);
    }

    if ($bare) {
      $output[$i]['image'] = 'http://'.THIS_DOMAIN.str_replace('.', '', LOCAL_IMAGE_CACHE).$filename;
      $output[$i]['thumbnail'] = 'http://'.THIS_DOMAIN.str_replace('.', '', LOCAL_IMAGE_CACHE).'thumb_'.$filename;
      $output[$i]['title'] = $item->get_title();
    } else {
      print '<a rel="nofollow" title="'.$item->get_title().'" href="http://'.THIS_DOMAIN.str_replace('.', '', LOCAL_IMAGE_CACHE).$filename.'"><img alt="'.$item->get_title().'" src="http://'.THIS_DOMAIN.str_replace('.', '', LOCAL_IMAGE_CACHE).'thumb_'.$filename."\"></a>\n";
    }
    
    $i++;
    if ($i > $items) break;
  }
  return $output;
}
?>