<?php //DELETE ALL FILES IN THE CACHE FOLDER
// Thanks to ngkong from Syndk8.net for the idea/code
require_once("functions.php");

$cookpass = $_COOKIE["yacg"];
$adminpass = md5($adminpass);
if($cookpass) {
    if($cookpass == $adminpass){
	
$cdir = realpath("../".LOCAL_CACHE."");

if ($handle = opendir($cdir)) {
   while (false !== ($file = readdir($handle))) {
    if ($file != "." && $file != "..") {
        $f2 = $cdir."/".$file;
unlink($f2);     
       }
   }
   
   closedir($handle);
}

echo "Done! All files in the cache folder were deleted!";
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
