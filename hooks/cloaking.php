<?php //IPCLOACK
// Thanks to http://iplists.com and rjonesx from Syndk8.net

/*require_once("admin/ip-update.php");
$timestamp = filemtime("./ips.txt");
$lastupdated = date("Ymd",$timestamp);
if($lastupdated!=date("Ymd")) {
$server = "http://" . $_SERVER['SERVER_NAME'];
}*/
// GET DOMAIN NAME AND OTHER VALUABLE INFORMATION
$ip = $_SERVER["REMOTE_ADDR"];
$ref = $_SERVER['HTTP_REFERER'];
$agent = $_SERVER['HTTP_USER_AGENT'];
$host = strtolower(gethostbyaddr($ip));
$file = implode(" ", file("./ips.txt"));
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
