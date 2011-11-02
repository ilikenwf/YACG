<?php // DELETE ALL FILES IN THE CACHE FOLDER
ignore_user_abort(true);
set_time_limit(0);
require_once("functions.php");
$cookpass = $_COOKIE["yacg"];
if (!$cookpass) {
	$cookpass = md5($_GET["password"]);
}
if ($cookpass) {
	if ($cookpass == md5(PASSWORD)) {
		$cdir = realpath("../".LOCAL_CACHE."");
		if ($handle = opendir($cdir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && $file != "index.php") {
					$f2 = $cdir."/".$file;
					unlink($f2);
				}
			}
			closedir($handle);
		}
		print "Done! All files in the cache folder were deleted!";
		print "<br />";
		print "<a href=\"javascript:history.go(-1)\">Go back</a>";
	}
	else{
		print INCORRECT_PASSWORD;
		die();
	}
}
else {
	print NOT_LOGGED_IN;
}
?>