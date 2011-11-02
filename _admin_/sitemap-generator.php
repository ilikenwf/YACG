<?php // GOOGLE AND YAHOO! SITEMAP GENERATOR
ignore_user_abort(true);
set_time_limit(0);
require_once("functions.php");
$cookpass = $_COOKIE["yacg"];
if (!$cookpass) {
	$cookpass = md5($_GET["password"]);
}
if ($cookpass) {
	if ($cookpass == md5(PASSWORD)) {
		$sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
		$sitemap .= "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		$sitemap .= "\n" . '<url>';
		$sitemap .= "\n" . '<loc>http://'.THIS_DOMAIN.'/'.'sitemap</loc>';
		$sitemap .= "\n" . '<lastmod>'.date("Y-m-d").'</lastmod>';
		$sitemap .= "\n" . '</url>';
		$keywords = @file("../".FILE_KEYWORDS."");
		foreach ($keywords as $keyword) {
			$sitemap .= "\n" . '<url>';
			$sitemap .= "\n" . '<loc>http://'.THIS_DOMAIN.'/'.str_replace(" ", "-", trim($keyword)).'</loc>';
			$sitemap .= "\n" . '<lastmod>'.date("Y-m-d").'</lastmod>';
			$sitemap .= "\n" . '</url>';
		}
		$sitemap .= '</urlset>';
		$file = '../sitemap.xml';
		$fp = fopen($file, "w+");
		fwrite($fp, $sitemap);
		fclose($fp);
		print "Done! Your <strong>sitemap</strong> has been generated!";
		print "<br />";
		print "<a href=\"javascript:history.go(-1)\">Go back</a>";
	}
	else {
		print(INCORRECT_PASSWORD);
		die();
	}
}
else {
	print(NOT_LOGGED_IN);
}
?>