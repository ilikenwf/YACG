<?php // FLICKR SCRAPER HOOK
if (!DEBUG) error_reporting(0);

function flickr($keyword = THIS_PAGE_KEYWORD, $items = 8, $bare = false) {
	$links = array();
	$squareimg = array();
	$mediumimg = array();
	$phototitle = array();
	if (FLICKR_API == false || FLICKR_API == 'xxxxxxxx') {
	  $searchresults = fetch('http://www.flickr.com/search/?q='.urlencode($keyword).'&ct=0&mt=photos&z=t');
    preg_match_all("/<img src=\"(http:\/\/\w+?.static.flickr.com\/\d+\/[\w]+?)_t.jpg\".+?alt=\"(.+?)\".+?class=\"pc_img\".+?\/>/", $searchresults, $matches);
	  for ($i=0; $i < $items; $i++) {
      $squareimg[] = $matches[1][$i].'_s.jpg';
      $mediumimg[] = $matches[1][$i].'.jpg';
      $phototitle[] = $matches[2][$i];
    }
	} else {
    require_once("phpFlickr/phpFlickr.php");
  	$f = new phpFlickr(FLICKR_API);
  	$f->enableCache("fs", LOCAL_CACHE, CACHE_TIME);
  	$photos_cat = $f->photos_search(array("text"=>$keyword, "sort"=>"relevance", "per_page"=>$items));
	  foreach ($photos_cat['photo'] as $photo) {
      $squareimg[] = $f->buildPhotoURL($photo, "Square");
      $mediumimg[] = $f->buildPhotoURL($photo, "Medium");
      $phototitle[] = $photo['title'];
    }
	}
	for ($i=0; $i < $items; $i++) {
		if (CACHE) {
			$squarefilename = basename($squareimg[$i]);
      if (!file_exists(LOCAL_IMAGE_CACHE.$squarefilename)) {
        $squarefile = fetch($squareimg[$i]);
        file_put_contents(LOCAL_IMAGE_CACHE.$squarefilename, $squarefile);
      }
      $mediumfilename = basename($mediumimg[$i]);
      if (!file_exists(LOCAL_IMAGE_CACHE.$mediumfilename)) {
        $mediumfile = fetch($mediumimg[$i]);
        file_put_contents(LOCAL_IMAGE_CACHE.$mediumfilename, $mediumfile);
      }
      $links[] = array('http://'.THIS_DOMAIN.str_replace(array('.'), '', LOCAL_IMAGE_CACHE).$mediumfilename, 'http://'.THIS_DOMAIN.str_replace(array('.'), '', LOCAL_IMAGE_CACHE).$squarefilename, htmlspecialchars($phototitle[$i]));
		}	else {
		  $links[] = array($mediumimg[$i], $squareimg[$i], htmlspecialchars($phototitle[$i]));
		}
	}
	if ($bare) {
	  return $links;
	} else {
  	$flickr = '';
	  foreach ($links as $image) {
			$flickr .= "\n<a href=\"".$image[0]."\" class=\"thickbox\">\n<img alt=\"".$image[2]."\" src=\"".$image[1]."\" /></a>";
	  }
    print $flickr."\n";
	}
}
?>