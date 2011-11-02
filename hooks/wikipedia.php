<?php //WIKIPEDIA SCRAPER
// Usage: wikipedia(); -> Prints Wikipedia's article of the page main keyword in plain text format (English)
// wikipedia('Google','en','0'); -> Prints Wikipedia's article of Google in plain text format (English)
// wikipedia('Google','es','1'); -> Prints Wikipedia's article of Google in html with format (Spanish)
if (DEBUG == false) {
	error_reporting(0);
}
function wikipedia($keyword = THIS_PAGE_KEYWORD, $language = 'en', $type = '1')	{
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
	$keyword = urlencode($keyword);
	$keyword = str_replace("+", "_", $keyword);
	$url = 'http://'.$language.'.wikipedia.org/wiki/'.$keyword.'';
	$wikipedia = @file_get_contents(LOCAL_CACHE.str_replace("_", "-", $keyword).".WIKIPEDIA");
	if ($wikipedia == false) {
		$wikipedia = fetch($url);
		$wikipedia = trim(preg_replace($pattern, $replace, preg_replace('/\n/', '',	preg_replace('/\s\s+/', ' ', $wikipedia))));
		savedata($wikipedia, $keyword.".WIKIPEDIA");
	}
	if ($type == "1") {
		if (preg_match("/<\!-- start content --\>(.*)<table id=\"toc\" class=\"toc\" summary=\"(.*)\">/", $wikipedia, $w)) {
			$wikipedia = $w[1];
		} elseif (preg_match("/<\!-- start content --\>(.*)<a name=\"Section_name1\">/is",$wikipedia,$w)) {
		  $wikipedia = $w[1];
		  $wikipedia = @trim(preg_replace($pattern, $replace, preg_replace('/\n/', '',preg_replace('/\s\s+/', ' ', $wikipedia))));
		  preg_match('/(.+?)<table id="toc"(.+?)<\/table>(.*)/s',$wikipedia,$w);
		  if ($w[1]!=""){
			$wikipedia=$w[1].$w[3];
    	          }
		} elseif (preg_match("/<\!-- start content --\>(.*)<a name=\"Section_name2\">/is",$wikipedia,$w)) {
		  $wikipedia = $w[1];
		  $wikipedia = @trim(preg_replace($pattern, $replace, preg_replace('/\n/', '',preg_replace('/\s\s+/', ' ', $wikipedia))));
		  preg_match('/(.+?)<table id="toc"(.+?)<\/table>(.*)/s',$wikipedia,$w);
		  if ($w[1]!=""){
			$wikipedia=$w[1].$w[3];
    	  }
		} elseif (preg_match("/<\!-- start content --\>(.*)<a name=\"Section_name3\">/is",$wikipedia,$w)) {
		  $wikipedia = $w[1];
		  $wikipedia = @trim(preg_replace($pattern, $replace, preg_replace('/\n/', '',preg_replace('/\s\s+/', ' ', $wikipedia))));
		  preg_match('/(.+?)<table id="toc"(.+?)<\/table>(.*)/s',$wikipedia,$w);
		  if ($w[1]!=""){
			$wikipedia=$w[1].$w[3];
    	  }
		} elseif (preg_match("/<\!-- start content --\>(.*)<a name=\"Section_name4\">/is",$wikipedia,$w)) {
		  $wikipedia = $w[1];
		  $wikipedia = @trim(preg_replace($pattern, $replace, preg_replace('/\n/', '',preg_replace('/\s\s+/', ' ', $wikipedia))));
		  preg_match('/(.+?)<table id="toc"(.+?)<\/table>(.*)/s',$wikipedia,$w);
		  if ($w[1]!=""){
			$wikipedia=$w[1].$w[3];
    	  }
		} elseif (preg_match("/<\!-- start content --\>(.*)<div class=\"boilerplate metadata\" id=\"stub\">/is",$wikipedia,$w)) {
		  $wikipedia = $w[1];
		  $wikipedia = @trim(preg_replace($pattern, $replace, preg_replace('/\n/', '',preg_replace('/\s\s+/', ' ', $wikipedia))));
		  preg_match('/(.+?)<table id="toc"(.+?)<\/table>(.*)/s',$wikipedia,$w);
		  if ($w[1]!=""){
			$wikipedia=$w[1].$w[3];
    	  }
		} elseif (preg_match("/<\!-- start content --\>(.*)<div class=\"printfooter\">/is",$wikipedia,$w)) {
		  $wikipedia = $w[1];
		  $wikipedia = @trim(preg_replace($pattern, $replace, preg_replace('/\n/', '',preg_replace('/\s\s+/', ' ', $wikipedia))));
		  preg_match('/(.+?)<table id="toc"(.+?)<\/table>(.*)/s',$wikipedia,$w);
		  if ($w[1]!=""){
			$wikipedia=$w[1].$w[3];
    	  }
		  }
	}
	 else {
		if (preg_match('/\<\!-- start content --\>(.+?)\<\!-- end content --\>/',$wikipedia, $w)) {
			$wikipedia = $w[1];
			preg_match('/(.+?)<table id="toc"(.+?)<\/table>(.*)/s', $wikipedia, $w);
			if ($w[1] != "") {
				$wikipedia = $w[1].$w[3];
			}
			$wikipedia = strip_tags($wikipedia);
			preg_match('/(.+?)Retrieved from "http/s', $wikipedia, $w);
			$wikipedia = $w[1];
			$wikipedia = str_replace('[edit]', '', $wikipedia);
			preg_match('/(.+?)External links/s', $wikipedia, $exl);
			if ($exl[1] != "") {
				$wikipedia = $exl[1];
			}
			preg_match('/(.+?)References/s', $wikipedia, $ref);
			if ($ref[1] != "") {
				$wikipedia = $ref[1];
			}
		} else {
			$wikipedia = '';
 			if (DEBUG == true) {
				echo "Nothing was found!";
				}
			}
	}
	$size = strlen($wikipedia);
	if ($size > 10000) {
	$wikipedia = substr($wikipedia, 0, 10000);
	}
	print $wikipedia;
}
?>