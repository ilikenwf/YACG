<?php // ARTICLES TABLE HOOK
function table($items = 2, $timeformat = '\P\u\b\l\i\s\h\e\d \o\n F j, Y, g:i a') {
  global $keyarr;
	$table = '';
	$timestamp = filemtime(FILE_KEYWORDS);
	$timestamp = date($timeformat, $timestamp);
	$table .= "<div>\n<table>\n";
	for ($n=0; $n < $items; $n++) {
	  $imglink = flickr(cut_cat($keyarr[$n]), 1, true);
	  $table .= "<tr>\n<td>\n<a href=\"".k2url($keyarr[$n]).'" title="'.cut_cat($keyarr[$n]).'">'."\n<img alt=\"".$imglink[0][2]."\" src=\"".$imglink[0][1]."\" /></a></td>\n<td>\n<a href=\"".k2url($keyarr[$n]).'" title="'.cut_cat($keyarr[$n]).'">'.cut_cat($keyarr[$n])."</a><br />\n";
		$cachedpage = LOCAL_CACHE.adashes(cut_cat($keyarr[$n])).'.html';
		$timestamp = file_exists($cachedpage) ? date($timeformat, filemtime($cachedpage)) : $timestamp;
		$table .= $timestamp."\n</td>\n</tr>\n";
	}
	$table .= "</table>\n</div>\n\n";
	print $table;
}
?>