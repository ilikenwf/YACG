<?php // GOOGLE AND YAHOO! SITEMAP GENERATOR
require_once('functions.php');
$keyarr = file('../../'.FILE_KEYWORDS);
$keyarr = array_map('rtrim', $keyarr);
$sitemap = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
$sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
foreach ($pages as $page) {
  $sitemap .= '<url>'."\n";
  $sitemap .= '<loc>http://'.THIS_DOMAIN.'/'.$page.'</loc>'."\n";
  $sitemap .= '<lastmod>'.date("Y-m-d").'</lastmod>'."\n";
  $sitemap .= '</url>'."\n";
}
foreach ($keyarr as $keyword) {
  $sitemap .= '<url>'."\n";
  $sitemap .= '<loc>'.k2url($keyword).'</loc>'."\n";
  $sitemap .= '<lastmod>'.date("Y-m-d").'</lastmod>'."\n";
  $sitemap .= '</url>'."\n";
}
$sitemap .= '</urlset>';
$file = '../../sitemap.xml';
file_put_contents($file, $sitemap);
print "Done! Your <strong>sitemap</strong> has been generated!<br /><a href=\"javascript:history.go(-1)\">Go back</a>";
?>