<?php //YAHOO IMAGES SCRAPER
// Usage: yahooimg(); -> Prints 1 image from Yahoo! Images about the main page keyword
// yahooimg('Google','10'); -> Prints 10 images from Yahoo! Images about Google

function yahooimg($keyword = THIS_PAGE_KEYWORD, $items = '1') {
	// Thanks to ua3nbw from Syndk8.net for his amazing first post and this code!
	$url = 'http://api.search.yahoo.com/ImageSearchService/V1/imageSearch?appid=YahooDemo&query='.urlencode($keyword).'&results=1&output=php';

	$yahooimg = @file_get_contents(LOCAL_CACHE.str_replace(" ", "-", $keyword).".YAHOOIMG");
	if ($yahooimg == false) {
		$yahooimg = fetch($url);
		savedata($yahooimg, $keyword.".YAHOOIMG");
	}

	$response = fetch($url);

	if ($response === false) {
		die('No Yahoo Images found!');
	}

	$yahooimg = unserialize($response);

	foreach($yahooimg as $mas) {
		foreach($mas as $mas1){

		}
		$n = '0';
		$yahooimg = '';
	foreach ($mas1 as $mas2) { 
		$file1 = basename($mas2[Thumbnail][Url]).'.jpg';
		$yahooimg1 = @file_get_contents(LOCAL_CACHE.$file1);
		if ($yahooimg1 == false) {
			$yahooimg1 = fetch($mas2[Thumbnail][Url].'.jpg');
			savedata($yahooimg1, $file1);
		}
		
	  $yahooimg .= "\n".'<a href="' . LOCAL_CACHE.$file1 . '">';
      $yahooimg .= "\n".'<img src="' . LOCAL_CACHE.$file1 . '"  alt="'.$keyword.'" class="thumbnail" /></a>';
      $yahooimg .= "\n";
			$n++;
			print $yahooimg;
		}
	}
}

?>