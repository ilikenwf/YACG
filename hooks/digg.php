<?php //Digg description Scraper
// Usage: digg(); -> Prints descriptions from recent Digg submissions about the main page keyword
// digg('Google'); -> Prints descriptions from recent Digg submissions about the keyword Google
if (DEBUG == false) {
	error_reporting(0);
}
function digg($keyword = THIS_PAGE_KEYWORD, $items = '5') {
	$y = "";
	$url = 'http://digg.com/rss_search?search='.urlencode($keyword).'&area=all&type=both&age=all&section=news';
    $digg = @file_get_contents(LOCAL_CACHE.str_replace(" ", "-", $keyword).".DIGG");
	if ($digg == false) {
    	$digg = fetch($url);
        savedata($digg, $keyword.".DIGG");
    }
	$cow = '';
    if (preg_match_all('/<description>(.*?)<\/description>/s', $digg, $y)) {
		$y[0] = array_slice($y[0], 1);
        foreach ($y[0] as $description) {
			if ($cow < $items) {
				$description = str_replace("<description>", "<p>", $description);
				$description = str_replace("</description>", "</p>", $description);
	            print "\n".$description . "<br /><br />";
			}
			$cow++;
		}
    } 
    else {
		if (DEBUG == true) {
			echo "Nothing was found!";
		}	
	}
}
?>