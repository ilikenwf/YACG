<?php // LIVE.COM RESULTS SCRAPER HOOK
if (DEBUG == false) {
	error_reporting(0);
}
function live($keyword = THIS_PAGE_KEYWORD, $items = 5, $return = false) {
	$live = '';
	$cow = 0;
	$feed = loadcache($keyword.".MSN");
	if ($feed == false) {
		$feed = fetch('http://search.msn.com/results.aspx?q='.urlencode($keyword).'&format=rss&FORM=RSNR');
		savecache($feed, $keyword.".MSN");
	}
	preg_match_all('#<title>(.*?)</title>#', $feed, $title, PREG_SET_ORDER);
	preg_match_all('#<link>(.*?)</link>#', $feed, $link, PREG_SET_ORDER);
	preg_match_all('#<description>(.*?)</description>#', $feed, $description, PREG_SET_ORDER);
	$nr = count($title);
	if ($nr == 1) {
		if (DEBUG == true) {
			print LIVE_ERROR_1;
			return $empty;
		}
	}
	elseif ($nr > 1) {
		for ($counter = 1; $counter < 11; $counter++) {
			if (empty($title[$counter][1])) {
				if (DEBUG == true) {
					print LIVE_ERROR_2;
					return $empty;
				}
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
		if ($return !== true) {
			print $live;
		}
		else {
			return $live;
		}
	}
}
?>