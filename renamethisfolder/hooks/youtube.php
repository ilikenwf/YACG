<?php // YOUTUBE VIDEO SCRAPER HOOK
if (!DEBUG) error_reporting(0);

function youtube($keyword = THIS_PAGE_KEYWORD) {
  $youtube_results = fetch('http://www.youtube.com/rss/tag/'.urlencode($keyword).'.rss');
	if (preg_match('/<enclosure url=\"(.*)swf/s', $youtube_results, $v)) {
		$video = $v[1];
		$video = substr($v[1], 0, 36);
		$youtube = "\n".'<object type="application/x-shockwave-flash" style="width:400px; height:325px;" data="'.$video.'">';
		$youtube .= "\n".'<param name="movie" value="'.$video.'" />';
		$youtube .= "\n</object>\n";
	}	else {
	  return printerror(YOUTUBE_ERROR_1);
	}
	print $youtube;
}
?>