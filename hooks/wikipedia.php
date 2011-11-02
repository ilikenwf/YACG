<?php // WIKIPEDIA ARTICLE SCRAPER HOOK
if (DEBUG == false) {
	error_reporting(0);
}
function wikipedia($keyword = THIS_PAGE_KEYWORD, $language = "en", $format = "html", $size = 10000, $images = false, $return = false) {
	$wikipedia = '';
	$pattern[0] = '/<a href="(.*?)">(.*?)<\\/a>/';
	$replace[0] = '$2';
	$pattern[1] = '/<h3 id="siteSub">From Wikipedia, the free encyclopedia<\/h3>/';
	$replace[1] = '';
	$pattern[2] = '/<div id="contentSub">(.*?)<\/div><div id="jump-to-nav">Jump to: navigation, search<\/div>/';
	$replace[2] = '';
	$pattern[3] = '/<div class="messagebox cleanup metadata">(.*?)<p><br \/><\/p>/';
	$replace[3] = '';
	$pattern[4] = '/<table class="messagebox" (.*?)>(.*?)<\/table>/';
	$replace[4] = '';
	$pattern[5] = '/<dl>(.*?)<\/dl>/';
	$replace[5] = '';
	$pattern[6] = '/<h1 class="firstHeading">(.*?)<\/h1>/';
	$replace[6] = '<h3>$1</h3>';
	$pattern[7] = '/<table class="messagebox protected" style="border: 1px solid #8888aa; padding: 0px; font-size:9pt;">(.*?)<\/table>/';
	$replace[7] = '';
	$pattern[8] = '/<div class="infobox sisterproject">(.*?)<\/div><\/div>/';
	$replace[8] = '';
	$pattern[9] = '/<sup (.*?)>(.*?)<\/sup>/';
	$replace[9] = '';
	$pattern[10] = '/<table style="background: transparent;" width="0">(.*?)<\/table>/';
	$replace[10] = '';
	$pattern[11] = '/<table class="messagebox current" style="font-size: normal;">(.*?)<\/table>/';
	$replace[11] = '';
	$pattern[12] = '/<table class="toccolours" align="center" width="55%" cellpadding="0" cellspacing="0">(.*?)<\/table>/';
	$replace[12] = '';
	$pattern[13] = '/<div class="editsection"(.*?)>(.*?)<\/div>/';
	$replace[13] = '';
	$pattern[14] = '/<div id="bodyContent">/';
	$replace[14] = '<div>';
	$pattern[15] = '/<dd>(.*?)<\/dd>/';
	$replace[15] = '';
	$pattern[16] = '/<div class="messagebox cleanup metadata">(.*?)<\/div>/';
	$replace[16] = '';
	$pattern[17] = '/<div class="thumbcaption">(.*?)<\/div><\/div>/';
	$replace[17] = '';
	$pattern[18] = '/<div class="thumb tright">/';
	$replace[18] = '';
	$pattern[19] = '/\[(.*?)\]/';
	$replace[19] = '';
	$pattern[20] = '/<table class="messagebox protected" (.*?)>(.*?)<\/table>/';
	$replace[20] = '';
	$pattern[21] = '/<div style="position:absolute; z-index:100; right:20px; top:10px; height:10px; width:300px;"><\/div>/';
	$replace[21] = '';
	$pattern[22] = '/<div style="position:absolute; z-index:100; right:10px; top:10px;" class="metadata" id="administrator">(.*?)<\/div><\/div>/';
	$replace[22] = '';
	$pattern[23] = '/<table class="messagebox current"(.*?)>(.*?)<\/table>/';
	$replace[23] = '';
	$pattern[24] = '/<table class="messagebox current" style="width: auto;">(.*?)<\/table>/';
	$replace[24] = '';
	$pattern[25] = '/<div class="dablink">(.*?)<\/div>/';
	$replace[25] = '';
	$pattern[26] = '/<div class="plainlinks messagebox cleanup metadata">(.*?)<\/div>/';
	$replace[26] = '';
	$pattern[27] = '/<div class="notice spoiler" id="spoiler">(.*?)<\/div>/';
	$replace[27] = '';
	$pattern[28] = '/<p><i>See also:(.*?)<\/i><\/p>/';
	$replace[28] = '';
	$pattern[29] = '/<div style="margin-left: 60px;">(.*?)<\/div>/';
	$replace[29] = '';
	$pattern[30] = '/<map(.*?)>(.*?)<\/map>/';
	$replace[30] = '';
	$pattern[31] = '/<img src="(.*?)" alt="This page is semi-protected." width="18" (.*?)\/>/';
	$replace[31] = '';
	$pattern[32] = '/<table style="width:100%;background:none">(.*?)<\/table>/';
	$replace[32] = '';
	$pattern[33] = '/<div class="messagebox merge metadata"><div class="floatleft">(.*?)<\/div>(.*?)<\/div>/';
	$replace[33] = '';
	$pattern[34] = '/<img src="http:\/\/upload.wikimedia.org\/wikipedia\/commons\/thumb\/f\/fa\/Padlock-silver-medium.svg\/18px-Padlock-silver-medium.svg.png" alt="" width="18" height="18" longdesc="\/wiki\/Image:Padlock-silver-medium.svg" usemap="#ImageMap_1" \/>/';
	$replace[34] = '';
	$pattern[35] = '/<div class="messagebox cleanup metadata plainlinks">(.*?)<\/div>/';
	$replace[35] = '';
	$pattern[36] = '/<small>(.*?)<\/small>/';
	$replace[36] = '';
	$pattern[37] = '/<div class="messagebox merge metadata">(.*?)<\/div>/';
	$replace[37] = '';
	$pattern[51] = '/<div(.*?)>/';
	$replace[51] = '';
	$pattern[52] = '/<\/div>/';
	$replace[52] = '';
	$pattern[41] = '/<div class="messagebox cleanup">(.*?)<\/div>/';
	$replace[41] = '';
	// REMOVE TABLE
	$pattern[38] = '/<table(.*?)>(.*?)<table(.*?)>(.*?)<\/table>(.*?)<\/table>/';
	$replace[38] = '';
	$pattern[39] = '/<table(.*?)>(.*?)<\/table>/';
	$replace[39] = '';
	$pattern[42] = '/<table(.*?)>/';
	$replace[42] = '';
	$pattern[43] = '/<\/table>/';
	$replace[43] = '';
	$pattern[45] = '/<tr(.*?)>(.*?)<\/tr>/';
	$replace[45] = '';
	$pattern[46] = '/<td(.*?)>(.*?)<\/td>/';
	$replace[46] = '';
	$pattern[47] = '/<td(.*?)>/';
	$replace[47] = '';
	$pattern[48] = '/<\/td>/';
	$replace[48] = '';
	$pattern[49] = '/<tr(.*?)>/';
	$replace[49] = '';
	$pattern[50] = '/<\/tr>/';
	$replace[50] = '';		
	// FIND AND CACHE A RELATED WIKIPEDIA ARTICLE
	$search_url = 'http://search.yahooapis.com/WebSearchService/V1/webSearch?appid='.YAHOO_API.'&query=site:'.$language.'.wikipedia.org+'.urlencode($keyword).'';
	$wikipedia_search = loadcache($keyword.".WIKIPEDIA-SEARCH");
	if ($wikipedia_search == false) {
		$wikipedia_search = fetch($search_url);
		savecache($wikipedia_search, $keyword.".WIKIPEDIA-SEARCH");
	}
	preg_match_all('/<Url>(.*?)<\/Url>/', $wikipedia_search, $wikipedia_results);
	$wikipedia_results = $wikipedia_results['1']['0'];
	$wikipedia_article = loadcache($keyword.".WIKIPEDIA-ARTICLE");
	if ($wikipedia_article == false) {
		$wikipedia_article = fetch($wikipedia_results);
		// CHECK FOR DISAMBIGUATION
		if (preg_match('/Wikipedia:Disambiguation/',$wikipedia_article)) {
			preg_match_all('/href=[\"\'](.*?)?[\"\']/',$wikipedia_article,$w);
			foreach ($w[1] as $new_url) {
				if (preg_match('/\/wiki\//',$new_url)) {
					$new_url = str_replace('http://'.$language.'.wikipedia.org',"",$new_url);
					$old_url = str_replace('http://'.$language.'.wikipedia.org',"",$wikipedia_results);
					if ($new_url != $old_url) {
						$wikipedia_article = fetch('http://'.$language.'.wikipedia.org'.$new_url);
						break;
					}
				}
			}
		}
		savecache($wikipedia_article, $keyword.".WIKIPEDIA-ARTICLE");
	}
	// REMOVE DOUBLE SPACES AND NEW LINES
	$wikipedia_article = preg_replace("/\n/", "", preg_replace("/\s\s+/", " ", $wikipedia_article));
	$wikipedia = $wikipedia_article;
	unset($wikipedia_article);
	// MATCH CONTENT (HTML OR TXT)
	if ($format == "html") {
		if (preg_match("/<\!-- start content --\>(.*)<table id=\"toc\" class=\"toc\" summary=\"(.*)\">/", $wikipedia, $w)) {
			$wikipedia = $w[1];
		} elseif (preg_match("/<\!-- start content --\>(.*)<a name=\"(.*)\">/is", $wikipedia, $w)) {
			$wikipedia = $w[1];
		} elseif (preg_match("/<\!-- start content --\>(.*)<div class=\"boilerplate metadata\" id=\"stub\">/is", $wikipedia, $w)) {
			$wikipedia = $w[1];
		} elseif (preg_match("/<\!-- start content --\>(.*)<div class=\"printfooter\">/is", $wikipedia, $w)) {
			$wikipedia = $w[1];
		}
		$wikipedia = preg_replace($pattern, $replace, $wikipedia);
	}
	else {
		if (preg_match('/\<\!-- start content --\>(.+?)\<\!-- end content --\>/', $wikipedia, $w)) {
			$wikipedia = $w[1];
			$wikipedia = preg_replace($pattern, $replace, $wikipedia);
			preg_match('/(.+?)<table id="toc"(.+?)<\/table>(.*)/s', $wikipedia, $w);
			if ($w[1] != "") {
				$wikipedia = $w[1].$w[3];
			}
			$wikipedia = str_replace('[edit]', '', $wikipedia);
			$wikipedia = strip_tags($wikipedia);
			preg_match('/(.+?)Retrieved from "http/s', $wikipedia, $w);
			$wikipedia = $w[1];
			preg_match('/(.+?)External links/s', $wikipedia, $ext);
			if ($ext[1] != "") {
				$wikipedia = $ext[1];
			}
			preg_match('/(.+?)References/s', $wikipedia, $ref);
			if ($ref[1] != "") {
				$wikipedia = $ref[1];
			}
		}
		else {
			unset($wikipedia);
			if (DEBUG == true) {
				print WIKIPEDIA_ERROR_1;
				return $empty;
			}
		}
	}
	$wikipedia = sanitize_xhtml($wikipedia);
	// TRIM ARTICLE TO THE DESIRED SIZE
	$wikipedia_size = strlen($wikipedia);
	if ($wikipedia_size > $size) {
		$wikipedia = substr($wikipedia, 0, $size);
	}
	// CACHE ALL THE IMAGES
	if ($images == false) {
		$wikipedia = preg_replace("!<img src=[^>]*>!si", "", $wikipedia);
		$wikipedia = preg_replace("!<div class=\"magnify\".*?</div>!si", "", $wikipedia);
		$wikipedia = preg_replace("!<div class=\"thumbcaption\".*?</div>!si", "", $wikipedia);
		$wikipedia = preg_replace("!<div class=\"thumbinner\".*?</div>!si", "", $wikipedia);
		$wikipedia = preg_replace("!<div class=\"thumb tright\".*?</div>!si", "", $wikipedia);
		$wikipedia = preg_replace("!<div class=\"thumb\".*?</div>!si", "", $wikipedia);
	}
	else {
		$images = get_src($wikipedia);
		foreach ($images['src'] as $image) {
			$image_name = basename($image);
			$image_file = loadcache($image_name);
			if ($image_file == false) {
				$image_file = fetch($image);
				savecache($image_file, $image_name);
			}
			$wikipedia = str_replace($image,LOCAL_CACHE.$image_name,$wikipedia);
		}
	}
	$wikipedia .= "\n";
	if ($return !== true) {
		print $wikipedia;
	}
	else {
		return $wikipedia;
	}
}
?>