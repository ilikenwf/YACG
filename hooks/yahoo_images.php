<?php // YAHOO IMAGES SCRAPER HOOK
if (DEBUG == false) {
	error_reporting(0);
}
function yahooimg($keyword = THIS_PAGE_KEYWORD, $items = 5, $return = false) {
	$yahooimg = '';
	$url = 'http://api.search.yahoo.com/ImageSearchService/V1/imageSearch?appid=YahooDemo&query='.urlencode($keyword).'&results='.$items.'';
	$yahooimg_results = loadcache($keyword.".YAHOOIMG");
	if ($yahooimg_results == false) {
		$yahooimg_results = fetch($url);
		savecache($yahooimg_results, $keyword.".YAHOOIMG");
	}
	$y = '';
	$i = '';
	$n = '1';
	if (preg_match_all('/<Url>(.*)<\/Url>/',$yahooimg_results,$y)) {
		while ($i < $items):
		$file1 = basename($y[1][$n]).'.jpg';
		$yahooimg1 = loadcache($file1);
		if ($yahooimg1 == false) {
			$yahooimg1 = fetch($y[1][$n].'.jpg');
			savecache($yahooimg1, $file1);
		}
		$yahooimg .= "\n".'<a href="' . LOCAL_CACHE.$file1 . '">';
		$yahooimg .= "\n".'<img src="' . LOCAL_CACHE.$file1 . '"  alt="'.$keyword.'" /></a>';
		$n++;
		$n++;
		$i++;
		endwhile;
	}
	else {
		if (DEBUG == true) {
			print YAHOO_ERROR_1;
			return $empty;
		}
	}
	$yahooimg .= "\n";
	if ($return !== true) {
		print $yahooimg;
	}
	else {
		return $yahooimg;
	}
}
?>