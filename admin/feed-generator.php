<?php //FEED GENERATOR
require_once("functions.php"); 

$cookpass = $_COOKIE["yacg"];
$adminpass = md5($adminpass);
if($cookpass) {
    if($cookpass == $adminpass){
	
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