<?php // DELETE ALL FILES IN THE CACHE FOLDER
require_once("functions.php");
$cdir = realpath("../../".LOCAL_CACHE."");
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
?>