<?php //FLICKR SCRAPER
// Usage: flickr(); -> Prints 8 images from Flickr about the main page keyword
// flickr('Google','10'); -> Prints 10 images from Flickr about Google
require_once("phpFlickr/phpFlickr.php");
if (DEBUG == false) {
	error_reporting(0);
}
function flickr($keyword = THIS_PAGE_KEYWORD, $items = '8') {
	$f = new phpFlickr(FLICKR_API);
	$f->enableCache("fs", LOCAL_CACHE);
	$photos_cat = $f->photos_search(array("text"=>$keyword, "sort"=>"relevance", "per_page"=>$items));
	foreach ($photos_cat['photo'] as $photo) {
		$imagename1 = basename($f->buildPhotoURL($photo, "Square"));
		$file1 = @file_get_contents(LOCAL_CACHE.$imagename1);
		if ($file1 == false) {
			$file1 = fetch($f->buildPhotoURL($photo, "Square"));
			savedata($file1, $imagename1);
		}
		$imagename2 = basename($f->buildPhotoURL($photo, "Medium"));
		$file2 = @file_get_contents(LOCAL_CACHE.$imagename2);
		if ($file2 == false) {
			$file2 = fetch($f->buildPhotoURL($photo, "Medium"));
			savedata($file2, $imagename2);
		}
    	print "\n"."<a href=\"".LOCAL_CACHE.$imagename2."\" class=\"thickbox\">"."\n";
    	print "<img alt=\"".$photo[title]."\" src=\"".LOCAL_CACHE.$imagename1."\" /></a>";
	}
	print "\n";
}
?>