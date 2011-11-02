<?php //GOOGLE AND YAHOO SITEMAP GENERATOR
require_once("../config.inc.php");

// GET DOMAIN NAME AND OTHER INFORMATION
if(FOLDER == true){
	$requested_page = str_replace(FOLDERNAME,"",$_SERVER['REQUEST_URI']);
	$domain_name = $_SERVER['HTTP_HOST'].FOLDERNAME;
} else{
// Thanks to ngkong for the fix
	$requested_page	= $_SERVER['REQUEST_URI'];
	$domain_name	= $_SERVER['HTTP_HOST'];
}
if(DOMAIN_TYPE == true){
	if(preg_match("/www\./", $domain_name)) : 
	define('THIS_DOMAIN', $domain_name); 
	else: 
		ignore_user_abort(true);
		header("Pragma: no-cache");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: http://www.$domain_name$requested_page"); 
		header("Connection: close");
		exit;
	endif;
} else{
	define('THIS_DOMAIN', $domain_name); 	
}

$sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
$sitemap .= "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

$sitemap .= "\n" . '<url>';
$sitemap .= "\n" . '<loc>http://'.THIS_DOMAIN.'/'.'sitemap.html</loc>';
$sitemap .= "\n" . '<lastmod>'.date(DATE_W3C).'</lastmod>';
$sitemap .= "\n" . '</url>';

$keywords = @file("../".FILE_KEYWORDS."");
array_shift($keywords);
	if($num==null) $num = 999999999;{
		$array_size = count($keywords)-$num;
		if($array_size > 0) for($c=0; $c < $array_size; $c++) array_pop($keywords);	
			foreach($keywords as $keyword){
				$sitemap .= "\n" . '<url>';
				$sitemap .= "\n" . '<loc>http://'.THIS_DOMAIN.'/'.str_replace(" ", "-", trim($keyword)).'</loc>';
				$sitemap .= "\n" . '<lastmod>'.date(DATE_W3C).'</lastmod>';
				$sitemap .= "\n" . '</url>';
				}
	}
$sitemap .= '</urlset>';

$file = '../sitemap.xml';
$fp=fopen($file, "w+");
fwrite($fp, $sitemap);
fclose($fp);
echo "Done! Your <strong>sitemap</strong> has been generated!";
echo "<br />";
echo "<a href=\"javascript:history.go(-1)\">Go back</a>";
?>
