<?php //YAHOO IMAGES SCRAPER
// Usage: yahooimg(); -> Prints 1 image from Yahoo! Images about the main page keyword
// yahooimg('Google','10'); -> Prints 10 images from Yahoo! Images about Google
if (DEBUG == false) {
	error_reporting(0);
}
function yahooimg($keyword = THIS_PAGE_KEYWORD, $items = '5') {
	// Thanks to ua3nbw from Syndk8.net for his amazing first post and this code!
$url = 'http://api.search.yahoo.com/ImageSearchService/V1/imageSearch?appid=YahooDemo&query='.urlencode($keyword).'&results='.$items.'';

	$yahooimg = @file_get_contents(LOCAL_CACHE.str_replace(" ", "-", $keyword).".YAHOOIMG");
	if ($yahooimg == false) {
		$yahooimg = fetch($url);
		savedata($yahooimg, $keyword.".YAHOOIMG");
	}
	$y = "";
	$i = '';
	$n = '1';
	$yahooimg = fetch($url);
	if (preg_match_all('/<Url>(.*)<\/Url>/',$yahooimg,$y)) {
	while ($i < $items):
	$file1 = basename($y[1][$n]).'.jpg';
	$yahooimg1 = @file_get_contents(LOCAL_CACHE.$file1);
	if ($yahooimg1 == false) {
			$yahooimg1 = fetch($y[1][$n].'.jpg');
			savedata($yahooimg1, $file1);
		}
		$yahooimg2 = '';
	  $yahooimg2 .= "\n".'<a href="' . LOCAL_CACHE.$file1 . '">';
      $yahooimg2 .= "\n".'<img src="' . LOCAL_CACHE.$file1 . '"  alt="'.$keyword.'" /></a>';
      $yahooimg2 .= "\n";
	  print $yahooimg2;
	$n++;
	$n++;
	$i++;
	endwhile;
	}
else {
		if (DEBUG == true) {
			echo "Nothing was found!";
		}
	}
}

?>