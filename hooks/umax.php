<?php // UMAX HOOK
function umax($keyword = THIS_PAGE_KEYWORD, $items = 5, $return = false) {
	if (SHOW_ADS == true) {
	$umax = '';
	$ip = $_SERVER['REMOTE_ADDR'];
	$language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? rawurlencode($_SERVER['HTTP_ACCEPT_LANGUAGE']) : '';
	$ref = rawurlencode('http://'.$_SERVER['HTTP_HOST']).'/';
	$url = 'http://xml.umaxfeed.com/xmlfeed.php?aid='.UMAX_AFF.'&qr='.$items.'&said='.UMAX_SUBAFF.'&ip='.$ip.'&q='.urlencode($keyword).'&ref='.$ref.'&l='.$language.'&grw=0&qpw=0&t=txt';
	$feed = fetch($url);
	while ($data = fgetcsv($feed, 3000, "|")) {
		if ($data[1] !== 0) {
			$umax .= "\n".'<p><strong><a href="'.$data[4].'">'.$data[1].'</a></strong><br />';
			$umax .= "\n".$data[2].'<br />';
			$umax .= "\n".'<a href="'.$data[4].'">'.$data[3].'</a>';
		}
		else {
			if (DEBUG == true) {
				print UMAX_ERROR_1;
				return $empty;
			}
		}
	}
	$umax .= "\n";
	if ($return !== true) {
		print $umax;
	}
	else {
		return $umax;
	}
	}
}
?>