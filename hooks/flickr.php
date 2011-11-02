<?php //FLICKR SCRAPER
// Usage: flickr(); -> Prints 8 images from Flickr about the main page keyword
// flickr('Google','10'); -> Prints 10 images from Flickr about Google
if (DEBUG == false) {
	error_reporting(0);
}
function flickr($keyword = THIS_PAGE_KEYWORD, $items = '8', $thumb = true) {
	$url = 'http://www.flickr.com/services/feeds/photos_public.gne?tags='.urlencode($keyword).'&format=rss_200';
	$flickr = @file_get_contents(LOCAL_CACHE.str_replace(" ", "-", $keyword).	".FLICKR");
	if ($flickr == false) {
		$flickr = fetch($url);
		savedata($flickr, $keyword.".FLICKR");
	}
	if (preg_match_all('/<media:.*?url="(http:\/\/.*?\.jpg)".*?>/s',$flickr,$f)) {
		$pattern1[1] = '/_o/';
		$replace1[1] = '_s';
		$pattern1[2] = '/_m/';
		$replace1[2] = '_s';
		$pattern1[3] = '/_t/';
		$replace1[3] = '_s';
		$pattern2[1] = '/_t/';
		$replace2[1] = '_o';
		$pattern2[2] = '/_m/';
		$replace2[2] = '_o';
		$pattern2[3] = '/_s/';
		$replace2[3] = '_o';
		$n = '0';
		$i = '1';
		$flickr = '';
		while ($i <= $items):
			if ($f[1][$n] == '') {
				print $flickr;
				return;
				}
			else {
				$newfile1 = '';
				$filename1 = basename(preg_replace($pattern1, $replace1, $f[1][$n]));
				$newfile1 = @file_get_contents(LOCAL_CACHE.$filename1);
				if ($newfile1 == false) {
				$newfile1 = fetch(preg_replace($pattern1, $replace1, $f[1][$n]));
				savedata($newfile1, $filename1);
				}
			 	$newfile2 = '';
				$filename2 = basename(preg_replace($pattern2, $replace2, $f[1][$n]));
				$newfile2 = @file_get_contents(LOCAL_CACHE.$filename2);
				if ($newfile2 == false) {
				if ($thumb == true) {
					$newfile2 = fetch(preg_replace($pattern1, $replace1, $f[1][$n]));
					}
				else {
				$newfile2 = fetch(preg_replace($pattern2, $replace2, $f[1][$n]));
				}
				savedata($newfile2, $filename2);
				}
				$flickr .= "\n".'<a href="'.LOCAL_CACHE.$filename2.'">';
		  		$flickr .= "\n".'<img src="'.LOCAL_CACHE.$filename1.'" alt="' . $keyword . '" width="75px" height="75px" /></a>';
				$n++;
				$n++;	
				$i++;
				}
		endwhile;
		$flickr .= "\n";
		print $flickr;
		}
	else {
	 	if (DEBUG == true) {
		echo "Nothing was found!";
		}
	}
	}
?>