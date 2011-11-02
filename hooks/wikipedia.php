<?php //WIKIPEDIA SCRAPER
// Usage: wikipedia(); -> Prints Wikipedia's article of the page main keyword in plain text format (English)
// wikipedia('Google','en','0', '10000'); -> Prints Wikipedia's article of Google in plain text format (English)
// wikipedia('Google','es','1', '10000'); -> Prints Wikipedia's article of Google in html with format (Spanish)
if (DEBUG == false) {
	error_reporting(0);
}
function get_src($string, $strict=false) {
	$inner = $strict?'[a-z0-9:?=&@/._-]+?':'.+?';
	preg_match_all("|src\=([\"'`])(".$inner.")\\1|i", $string, &$matches);
	$image["src"] = $matches[2];
	return $image;
}
/* This function will remove style, id and class attributes */
function sanitize_xhtml($source) {
	global $allowed_styles;
	$source = "<parse>".$source."</parse>";
	$exceptions = str_replace(",", "|", $allowed_styles);
	function replacer($text) {
		$check = "@:@s";
		$replace = "&#58;";
		return preg_replace($check, $replace, $text[0]);
	}
	$source = preg_replace_callback("@>(.*)<@Us", "replacer", $source);
	$regexp = '@([^;"]+)?(?<!'.$exceptions.')(?<!\>\w):(?!\/\/(.+?)\/|<|>)((.*?)[^;"]+)(;)?@is';
	$source = preg_replace($regexp, '', $source);
	$source = preg_replace('@[a-z]*=""@is', '', $source);
	$source = preg_replace('/class="(.*?)"/','', $source);
	$source = preg_replace('/id="(.*?)"/','', $source);
	$source = preg_replace('/usemap="(.*?)"/','', $source);
	$source = preg_replace('/\s\s+/', ' ', $source);
	$source = preg_replace('/ >/','>', $source);
	$source = str_replace(array('<parse>','</parse>'),'',$source);
	return $source;
}
function wikipedia($keyword = THIS_PAGE_KEYWORD, $language = 'en', $type = '1', $size = 10000)	{
	$url = "";
	$w = "";
	$ext = "";
	$ref = "";
	$pattern[0] = '/<a href="(.*?)">(.*?)<\\/a>/';
	$replace[0] = '$2';
	$pattern[1] = '/<h3 id=\"siteSub\">From Wikipedia, the free	encyclopedia<\/h3>/';
	$replace[1] = '';
	$pattern[2] = '/<div id=\"contentSub\">(.*?)<\/div><div id=\"jump-to-nav\">Jump to: navigation, search<\/div>/';
	$replace[2] = '';
	$pattern[3] = '/<div class=\"messagebox cleanup metadata\">(.*?)<p><br \/><\/p>/';
	$replace[3] = '';
	$pattern[4] = '/<table class=\"messagebox\" (.*?)>(.*?)<\/table>/';
	$replace[4] = '';
	$pattern[5] = '/<dl>(.*?)<\/dl>/';
	$replace[5] = '';
	$pattern[6] = '/<h1 class=\"firstHeading"\>(.*?)<\/h1>/';
	$replace[6] = '<h3>$1</h3>';
	$pattern[7] = '/<table class=\"messagebox protected\" style=\"border: 1px solid #8888aa; padding: 0px; font-size:9pt;\">(.*?)<\/table>/';
	$replace[7] = '';
	$pattern[8] = '/<div class=\"infobox sisterproject\">(.*?)<\/div><\/div>/';
	$replace[8] = '';
	$pattern[9] = '/<sup (.*?)>(.*?)<\/sup>/';
	$replace[9] = '';
	$pattern[10] = '/<table style=\"background: transparent;\" width=\"0\">(.*?)<\/table>/';
	$replace[10] = '';
	$pattern[11] = '/<table class=\"messagebox current\" style=\"font-size:	normal;\">(.*?)<\/table>/';
	$replace[11] = '';
	$pattern[12] = '/<table class=\"toccolours\" align=\"center\" width=\"55%\" cellpadding=\"0\" cellspacing=\"0\">(.*?)<\/table>/';
	$replace[12] = '';
	$pattern[13] = '/<div class=\"editsection\"(.*?)>(.*?)<\/div>/';
	$replace[13] = '';
	$pattern[14] = '/<div id=\"bodyContent\">/';
	$replace[14] = '<div>';
	$pattern[15] = '/<dd>(.*?)<\/dd>/';
	$replace[15] = '';
	$pattern[16] = '/<div class=\"messagebox cleanup metadata\">(.*?)<\/div>/';
	$replace[16] = '';
	$pattern[17] = '/<div class=\"thumbcaption\">(.*?)<\/div><\/div>/';
	$replace[17] = '';
	$pattern[18] = '/<div class=\"thumb tright\">/';
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
	$pattern[26] = '/<b>/';
	$replace[26] = '<strong>';
	$pattern[27] = '/<\/b>/';
	$replace[27] = '</strong>';
	$pattern[28] = '/<div(.*?)>/';
	$replace[28] = '';
	$pattern[29] = '/<\/div>/';
	$replace[29] = '';
	$pattern[30] = '/<map(.*?)>(.*?)<\/map>/';
	$replace[30] = '';
	$pattern[31] = '/<img src="(.*?)" alt="This page is semi-protected." width="18" (.*?)\/>/';
	$replace[31] = '';
	$pattern[32] = '/<table style="width:100%;background:none">(.*?)<\/table>/';
	$replace[32] = '';
	$pattern[33] = '/<div class="messagebox merge metadata">(.*?)<\/div>/';
	$replace[33] = '';
	$search = 'http://search.yahooapis.com/WebSearchService/V1/webSearch?appid='.YAHOO_API.'&query=site:'.$language.'.wikipedia.org+'.urlencode($keyword).'';
	$wiki_search = @file_get_contents(LOCAL_CACHE.str_replace(" ", "-", $keyword).".WIKIPEDIA-SEARCH");
	if ($wiki_search == false) {
		$wiki_search = fetch($search);
		savedata($wiki_search, $keyword.".WIKIPEDIA-SEARCH");
	}
	preg_match_all('/<Url>(.*?)<\/Url>/',$wiki_search,$url);
	$url = $url['1']['0'];
	$wikipedia = @file_get_contents(LOCAL_CACHE.str_replace(" ", "-", $keyword).".WIKIPEDIA-ARTICLE");
	if ($wikipedia == false) {
		$wikipedia = fetch($url);
		$wikipedia = trim(preg_replace($pattern, $replace, preg_replace('/\n/', '',	preg_replace('/\s\s+/', ' ', $wikipedia))));
		savedata($wikipedia, $keyword.".WIKIPEDIA-ARTICLE");
	}
	if ($type == "1") {
		$wikipedia = preg_replace($pattern, $replace, $wikipedia);
		if (preg_match("/<\!-- start content --\>(.*)<table id=\"toc\" class=\"toc\" summary=\"(.*)\">/", $wikipedia, $w)) {
			$wikipedia = $w[1];
		} elseif (preg_match("/<\!-- start content --\>(.*)<a name=\"(.*)\">/is", $wikipedia, $w)) {
			$wikipedia = $w[1];
		} elseif (preg_match("/<\!-- start content --\>(.*)<div class=\"boilerplate metadata\" id=\"stub\">/is", $wikipedia, $w)) {
			$wikipedia = $w[1];
		} elseif (preg_match("/<\!-- start content --\>(.*)<div class=\"printfooter\">/is", $wikipedia, $w)) {
			$wikipedia = $w[1];
		}
	}
	else {
		if (preg_match('/\<\!-- start content --\>(.+?)\<\!-- end content --\>/', $wikipedia, $w)) {
			$wikipedia = $w[1];
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
			$wikipedia = '';
 			if (DEBUG == true) {
				print "Nothing was found!";
				}
			}
	}
	$size2 = strlen($wikipedia);
	if ($size2 > $size) {
		$wikipedia = substr($wikipedia, 0, $size);
	}
	$wikipedia = sanitize_xhtml($wikipedia);
	$images = get_src($wikipedia);
	foreach ($images['src'] as $image) {
		$imagename = basename($image);
		$file = @file_get_contents(LOCAL_CACHE.$imagename);
		if ($file == false) {
			$file = fetch($image);
			savedata($file, $imagename);
		}
		$wikipedia = str_replace($image,LOCAL_CACHE.$imagename,$wikipedia);
	}
	print $wikipedia;
}
?>