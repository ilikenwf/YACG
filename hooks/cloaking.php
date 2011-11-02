<?php //IPCLOACK
// Thanks to http://iplists.com and rjonesx from Syndk8.net
require_once("mainhook.php");

if (DEBUG == false) {
	error_reporting(0);
}

$timestamp = filemtime(FILE_BOTS);
$lastupdated = date("Ymd",$timestamp);
if($lastupdated != date("Ymd")) {
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
		$opt .= fetch($list);
	}
	$opt = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $opt);
	$fp =  fopen("../".FILE_BOTS."","w");
	fwrite($fp,$opt);
	fclose($fp);
}
// GET DOMAIN NAME AND OTHER VALUABLE INFORMATION
$ip = $_SERVER["REMOTE_ADDR"];
$ref = $_SERVER['HTTP_REFERER'];
$agent = $_SERVER['HTTP_USER_AGENT'];
$host = strtolower(gethostbyaddr($ip));
$file = implode(" ", file(FILE_BOTS));
$exp = explode(".", $ip);
$class = $exp[0].'.'.$exp[1].'.'.$exp[2].'.';
$threshold = LEVEL;

// PERFORM CLOAK CHECKS
if (stristr($host, "googlebot") && stristr($host, "inktomi") && stristr($host,
	"msn")) {
	$cloak++;
}

if (stristr($file, $class)) {
	$cloak++;
}

if (stristr($file, $agent)) {
	$cloak++;
}

if (strlen($ref) > 0) {
	$cloak = 0;
}

// PERFORM CLOAK DATA ANALYSIS
if ($cloak >= $threshold) {
	$cloakdirective = 1;
} else {
	$cloakdirective = 0;
}

?>