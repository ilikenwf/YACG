<?php //GOOGLE AND YAHOO SITEMAP GENERATOR
require_once("functions.php"); 

$cookpass = $_COOKIE["yacg"];
$adminpass = md5($adminpass);
if($cookpass) {
    if($cookpass == $adminpass){ 

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
}
else{
    echo($incorrect_password);
    die();
    }
}
else{
echo($not_logged_in);
}
?>
