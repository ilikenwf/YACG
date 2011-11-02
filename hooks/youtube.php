<?php //YOUTUBE SCRAPER
// Usage: youtube(); -> Prints a video from Youtube about the main page keyword
// youtube('Google'); -> Prints a video from Youtube about the keyword Google

function youtube($keyword = THIS_PAGE_KEYWORD) {
	$url = 'http://www.youtube.com/rss/tag/'.urlencode($keyword).'.rss';
	$youtube = @file_get_contents(LOCAL_CACHE.str_replace(" ", "-", $keyword).".YOUTUBE");
	if ($youtube == false) {
		$youtube = fetch($url);
		savedata($youtube, $keyword.".YOUTUBE");
	}
	if (preg_match('/<enclosure url=\"(.*)swf/s', $youtube, $y)) {
		$youtube = $y[1];
		$youtube = substr($y[1], 0, 36);
		$video = '';
		$video .= "\n".'<object type="application/x-shockwave-flash" style="width:400px; height:325px;" data="'.$youtube.'">';
    $video .= "\n".'<param name="movie" value="'.$youtube.'" />';
    $video .= "\n".'</object>';
    $video .= "\n";
    print $video;
	} else
		echo"No Youtube was found!";
}

?>
