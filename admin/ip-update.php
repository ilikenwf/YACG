<?php //UPDATE IP LIST OF BOTS
require_once("functions.php"); 

$lists = array(
	'http://spiders.wphost.info/update.php',
	'http://spiders.wphost.info/google.txt',
	'http://spiders.wphost.info/inktomi.txt',
	'http://spiders.wphost.info/lycos.txt',
	'http://spiders.wphost.info/msn.txt',
	'http://spiders.wphost.info/altavista.txt',
	'http://spiders.wphost.info/askjeeves.txt',
	'http://spiders.wphost.info/wisenut.txt',
	'http://spiders.wphost.info/misc.txt'
);

foreach($lists as $list) {
	$opt .= fetch_admin($list);
	}

$opt = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $opt);
$fp =  fopen("../".FILE_BOTS."","w");
fwrite($fp,$opt);
fclose($fp);
echo "Done! Your <strong>Bot List</strong> has been updated!";
echo "<br />";
echo "<a href=\"javascript:history.go(-1)\">Go back</a>";
?>