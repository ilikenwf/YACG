<?php // YOUTUBE VIDEO SCRAPER HOOK
if (DEBUG == false) {
	error_reporting(0);
}
function youtube($keyword = THIS_PAGE_KEYWORD, $return = false) {
	$youtube = '';
	$url = 'http://www.youtube.com/rss/tag/'.str_replace(" ", "-", $keyword).'.rss';
	$youtube_results = loadcache($keyword.".YOUTUBE");
	if ($youtube_results == false) {
		$youtube_results = fetch($url);
		savecache($youtube_results, $keyword.".YOUTUBE");
	}
	if (preg_match('/<enclosure url=\"(.*)swf/s', $youtube_results, $v)) {
		$video = $v[1];
		$video = substr($v[1], 0, 36);
		$youtube .= "\n".'<object type="application/x-shockwave-flash" style="width:400px; height:325px;" data="'.$video.'">';
		$youtube .= "\n".'<param name="movie" value="'.$video.'" />';
		$youtube .= "\n".'</object>';
	}
	else {
		if (DEBUG == true) {
			print YOUTUBE_ERROR_1;
			return $empty;
		}
	}
	$youtube .= "\n";
	if ($return !== true) {
		print $youtube;
	}
	else {
		return $youtube;
	}
}
?>