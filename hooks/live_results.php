<?php //LIVE.COM RESULTS SCRAPER
// Usage: live(); -> Prints 5 results about the main page keyword
// live('Google','10'); -> Prints 10 results about the keyword Google
if (DEBUG == false) {
	error_reporting(0);
}
function live($keyword = THIS_PAGE_KEYWORD, $items = '5') {
	$title = "";
	$link = "";
	$description = "";
	$cow = "";
	$feed = @file_get_contents(LOCAL_CACHE.str_replace(" ", "-", $keyword).".MSN");
	if ($feed == false) {
		$feed = fetch('http://search.msn.com/results.aspx?q='.urlencode($keyword).'&format=rss&FORM=RSNR');
		savedata($feed, $keyword.".MSN");
	}
	preg_match_all('#<title>(.*?)</title>#', $feed, $title, PREG_SET_ORDER);
	preg_match_all('#<link>(.*?)</link>#', $feed, $link, PREG_SET_ORDER);
	preg_match_all('#<description>(.*?)</description>#', $feed, $description,
	PREG_SET_ORDER);

	$nr = count($title);
	if ($nr == 1) {
		if (DEBUG == true) {
			echo "Nothing was found!";
		}	
		} elseif ($nr > 1) {
	  $live = '';
		for ($counter = 1; $counter < 11; $counter++) {
			if (empty($title[$counter][1])) {
				echo"";
			} elseif (!empty($title[$counter][1])) {
				$title[$counter][1] = str_replace("&amp;", "&", $title[$counter][1]);
				$title[$counter][1] = str_replace("&apos;", "'", $title[$counter][1]);
				$description[$counter][1] = str_replace("&amp;", "&",	$description[$counter][1]);
				$description[$counter][1] = str_replace("&apos;", "'", $description[$counter][1]);

				if ($cow < $items) {
					$live .= "\n"."<h3>".$title[$counter][1]."</h3>";
					$live .= "\n"."<p>".$description[$counter][1]."</p>";
					$live .= "\n"."<p style=\"text-align:right;\"><a href=\"".$link[$counter][1]."\" rel=\"external nofollow\">Read more...</a></p>";
				}
				$cow++;
			}
		}
			$live .= "\n";
			print $live;
	}
}

?>
