<?php // UPDATE BOT LIST
ignore_user_abort(true);
set_time_limit(0);
require_once("functions.php");
$cookpass = $_COOKIE["yacg"];
if (!$cookpass) {
	$cookpass = md5($_GET["password"]);
}
if ($cookpass) {
	if ($cookpass == md5(PASSWORD)) {
		$lists = array(
		'http://labs.getyacg.com/spiders/google.txt',
		'http://labs.getyacg.com/spiders/inktomi.txt',
		'http://labs.getyacg.com/spiders/lycos.txt',
		'http://labs.getyacg.com/spiders/msn.txt',
		'http://labs.getyacg.com/spiders/altavista.txt',
		'http://labs.getyacg.com/spiders/askjeeves.txt',
		'http://labs.getyacg.com/spiders/wisenut.txt',
		);
		foreach($lists as $list) {
			$opt .= fetch($list)."\n";
		}
		$opt = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $opt);
		$fp =  fopen("../".FILE_BOTS."","w");
		fwrite($fp,$opt);
		fclose($fp);
		print "Done! Your <strong>Bot List</strong> has been updated!";
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