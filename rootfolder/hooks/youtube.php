<?php // YOUTUBE VIDEO SCRAPER HOOK
function youtube($keyword = THIS_PAGE_KEYWORD, $width = '400', $height = '300', $params = '') {
  $params = $params ? $params : '&color1=0xb1b1b1&color2=0xcfcfcf&fs=1';
  $results = fetch('http://www.youtube.com/rss/tag/'.urlencode($keyword).'.rss');
  if (preg_match('/<enclosure url=\"(.*)\.swf/im', $results, $video)) {
    print '<object width="'.$width.'" height="'.$height.'"><param name="movie" value="'.$video[1].$params.'"></param><param name="allowFullScreen" value="true"></param><embed src="'.$video[1].$params.'" type="application/x-shockwave-flash" allowfullscreen="true" width="'.$width.'" height="'.$height.'"></embed></object>';
  }	else return printerror('No videos found on YouTube');
}
?>