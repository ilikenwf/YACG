<?php // DIGG DESCRIPTION SCRAPER HOOK
if (DEBUG == false) {
	error_reporting(0);
}
function digg($keyword = THIS_PAGE_KEYWORD, $items = 5, $return = false) {
	$digg = '';
	$url = 'http://digg.com/rss_search?search='.urlencode($keyword).'&area=all&type=both&age=all&section=news';
	$digg_results = loadcache($keyword.".DIGG");
	if ($digg_results == false) {
		$digg_results = fetch($url);
		savecache($digg_results, $keyword.".DIGG");
	}
	$cow = '';
	if (preg_match_all('/<description>(.*?)<\/description>/s', $digg_results, $d)) {
		$d[0] = array_slice($d[0], 1);
		foreach ($d[0] as $description) {
			if ($cow < $items) {
				$description = str_replace("<description>", "<p>", $description);
				$description = str_replace("</description>", "</p>", $description);
				$digg .= "\n".$description."<br />";
			}
			$cow++;
		}
	}
	else {
		if (DEBUG == true) {
			print DIGG_ERROR_1;
			return $empty;
		}
	}
	$digg .= "\n";
	if ($return !== true) {
		print $digg;
	}
	else {
		return $digg;
	}
}
?>