<?php //ADMIN FUNCTIONS
require_once("../config.inc.php");

// PASSWORD PROTECT STUFF
$adminpass = PASSWORD; 
$not_logged_in = "
You are not logged in!
<br />
<a href=\"javascript:history.go(-1)\">Go back</a>";
$incorrect_password = "
Wrong password!
<br />
<a href=\"javascript:history.go(-1)\">Go back</a>";
$first_page = "main.php";

// GET DOMAIN NAME AND OTHER INFORMATION
define('_IP', $_SERVER["REMOTE_ADDR"]);
define('FOLDERNAME', dirname(str_replace("admin/","",$_SERVER['PHP_SELF'])));
if(FOLDER == true){
	$requested_page = strlen(FOLDERNAME);
	$requested_page = substr($_SERVER['REQUEST_URI'], $requested_page);
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

function fetch_admin($url) {
	if (function_exists('curl_exec')) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1)Gecko/20061010 Firefox/2.0');
		return curl_exec($ch);
		curl_close($ch);
	}
	else {
		$snoop = new Snoopy;
		$snoop->agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1)Gecko/20061010 Firefox/2.0';
		$snoop->fetch($url);
		return $snoop->results;
		}
	}
	
function markov_admin($gran = '5', $num = '200') {
	if (is_dir("../".LOCAL_ARTICLES."")) {
		if ($dh = opendir("../".LOCAL_ARTICLES."")) {
			while (($file = readdir($dh)) !== false) {
				if ($file == "." || $file == ".." || empty($file)) {
					$my_dump[] = $file;
				} elseif (substr($file,  - 4) == '.txt') {
					$combo .= file_get_contents("../".LOCAL_ARTICLES."".$file);
				}
				if ($i >= $nr_files) {
					$i = 0;
				} elseif ($i < $nr_files) {
					++$i;
				}
			}
			closedir($dh);
		}
	}
	//$combo = utf8_encode($combo);
	$combo = htmlentities($combo);
	$combo = preg_replace('/\s\s+/', ' ', $combo);
	$combo = preg_replace('/\n|\r/', '', $combo);
$chickenfeet=explode(".",$combo);
	shuffle($chickenfeet);
	$combo="";
	$combo=implode(".",$chickenfeet);
	$G = $gran;
	$O = $num;
	$output = "";
	$combo = $combo;
	$LETTERS_LINE = 65;
	$textwords = array();
	$textwords = explode(" ", $combo);
	$loopmax = count($textwords) - ($G - 2) - 1;
	$frequency_table = array();
	for ($j = 0; $j < $loopmax; $j++) {
		$key_string = "";
		$end = $j + $G;
		for ($k = $j; $k < $end; $k++) {
			$key_string .= $textwords[$k].' ';
		}
		$frequency_table[$key_string] .= $textwords[$j + $G]." ";
	}
	$buffer = "";
	$lastwords = array();
	for ($i = 0; $i < $G; $i++) {
		$lastwords[] = $textwords[$i];
		$buffer .= " ".$textwords[$i];
	}
	for ($i = 0; $i < $O; $i++) {
		$key_string = "";
		for ($j = 0; $j < $G; $j++) {
			$key_string .= $lastwords[$j]." ";
		}
		if ($frequency_table[$key_string]) {
			$possible = explode(" ", trim($frequency_table[$key_string]));
			mt_srand();
			$c = count($possible);
			$r = mt_rand(1, $c) - 1;
			$nextword = $possible[$r];
			$buffer .= " $nextword";
			if (strlen($buffer) >= $LETTERS_LINE) {
				$output .= $buffer;
				$buffer = "";
			}
			for ($l = 0; $l < $G - 1; $l++) {
				$lastwords[$l] = $lastwords[$l + 1];
			}
			$lastwords[$G - 1] = $nextword;
		} else {
			$lastwords = array_splice($lastwords, 0, count($lastwords));
			for ($l = 0; $l < $G; $l++) {
				$lastwords[] = $textwords[$l];
				$buffer .= ' '.$textwords[$l];
			}
		}
	}

	return trim($output);
}
?>