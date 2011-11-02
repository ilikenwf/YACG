<?php //METATAGS HOOK
// Usage: metakeywords(); -> Prints <meta name="keywords"... for the current page
// metadescription(); -> Prints <meta name="description"... for the current page
if (DEBUG == false) {
	error_reporting(0);
}
function metakeywords($keyword=THIS_PAGE) {
	$keywords = @file_get_contents(FILE_KEYWORDS);
	$keywords = explode("\n", $keywords);
	switch ($keyword): case 'index':
		$firstKey = array(trim($keywords[0]));
		break;
	case 'sitemap':
		$firstKey = array(trim($keywords[0]));
		break;
	default:
		$firstKey = array(trim(THIS_PAGE_KEYWORD));
		endswitch;
		shuffle($keywords);
		$keywords = array_merge($firstKey, $keywords);
		$firstten = array_slice($keywords, 0, 9);
		$result = implode(", ", preg_replace('/\n|\r/', '', $firstten));
		echo'<meta name="keywords" content="'.strtolower(preg_replace('/\n|\r/', '', $result)).'" />'."\n";
	}
	
function metadescription() {
		echo'<meta name="description" content="';
		markov(3, 25);
		echo'." />'."\n";
	}
	
?>
