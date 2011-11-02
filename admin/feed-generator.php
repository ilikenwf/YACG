<?php //FEED GENERATOR
require_once("admin-hooks.php"); 
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

$list = file("../".FILE_KEYWORDS."");

$out2 = '';
	$out2 .= "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n<rss version=\"2.0\">\n<channel>\n";
for($i=0; $i<count($list); $i++) 
{
	$out2 .= "\n" . '<item>';
	$out2 .= "\n" . '<title>'.trim($list[$i]).'</title>';
	$out2 .= "\n" . '<link>http://'.THIS_DOMAIN.'/'.trim(str_replace(" ", "-", $list[$i])).'</link>';
	$out2 .= "\n" . '<description>';
	$out2 .= "\n" . markov_admin(3, 50);
	$out2 .= "\n" . '</description>';
	$out2 .= "\n" . '</item>';

}
	$out2 .= "\n</channel>\n</rss>";

	$fp=fopen("../feed.xml", "w+");
	fwrite($fp, $out2);
	fclose($fp);
echo "Done! Your <strong>feed</strong> has been generated!";
echo "<br />";
echo "<a href=\"javascript:history.go(-1)\">Go back</a>";
?>