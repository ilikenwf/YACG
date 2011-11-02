<?php // FLICKR SCRAPER HOOK
require_once("phpFlickr/phpFlickr.php");
if (DEBUG == false) {
	error_reporting(0);
}
function flickr($keyword = THIS_PAGE_KEYWORD, $items = 8, $return = false) {
	$flickr = '';
	$f = new phpFlickr(FLICKR_API);
	$f->enableCache("fs", LOCAL_CACHE, CACHE_TIME);
	$photos_cat = $f->photos_search(array("text"=>$keyword, "sort"=>"relevance", "per_page"=>$items));
	foreach ($photos_cat['photo'] as $photo) {
		if (CACHE == true) {
			$imagename1 = basename($f->buildPhotoURL($photo, "Square"));
			$file1 = loadcache($imagename1);
			if ($file1 == false) {
				$file1 = fetch($f->buildPhotoURL($photo, "Square"));
				savecache($file1, $imagename1);
			}
			$imagename2 = basename($f->buildPhotoURL($photo, "Medium"));
			$file2 = loadcache($imagename2);
			if ($file2 == false) {
				$file2 = fetch($f->buildPhotoURL($photo, "Medium"));
				savecache($file2, $imagename2);
			}
			$flickr .= "\n"."<a href=\"".LOCAL_CACHE.$imagename2."\" class=\"thickbox\">";
			$flickr .= "\n"."<img alt=\"".htmlentities($photo['title'])."\" src=\"".LOCAL_CACHE.$imagename1."\" /></a>";
		}
		else {
			$imagename1 = $f->buildPhotoURL($photo, "Square");
			$imagename2 = $f->buildPhotoURL($photo, "Medium");
			$flickr .= "\n"."<a href=\"".$imagename2."\" class=\"thickbox\">";
			$flickr .= "\n"."<img alt=\"".htmlentities($photo['title'])."\" src=\"".$imagename1."\" /></a>";
		}
	}
	$flickr .= "\n";
	if ($return !== true) {
		print $flickr;
	}
	else {
		return $flickr;
	}
}
?>