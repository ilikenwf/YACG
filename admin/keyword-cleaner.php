<?php //KEYWORD CLEANER
// Thanks to BlackMamba and deregular for the code
// Original contribution -> http://www.syndk8.net/forum/index.php/topic,2497.0
require_once("functions.php"); 

$cookpass = $_COOKIE["yacg"];
$adminpass = md5($adminpass);
if($cookpass) {
    if($cookpass == $adminpass){

if(isset($_GET['save'])){
	$keywordy = $_POST['keywords'];
	$keywordum = explode("\n",$keywordy);
	$keywords = array_values(array_unique($keywordum));
	$badkeywords = file_get_contents("badkeywords.txt");
	$nasty = explode("\n",$badkeywords);
	$nastyCount = count($nasty);
	$keywordCount = count($keywords);
	for ($k=0; $k<$keywordCount; $k++){
		for ($l=0; $l<$nastyCount; $l++){
				if(isset($_POST['adult'])){
			if (stristr($keywords[$k], $nasty[$l])){
				unset($keywords[$k]);
				}
				}
				if(isset($_POST['invalid'])){
			if (preg_match("/[^\w\s]/", $keywords[$k])){
				unset($keywords[$k]);
				}
				}
			}
		}
	foreach($keywords AS $keyword){
		if(isset($_POST['format'])){
	$keyword = ucwords(strtolower($keyword));
	}
		$keywordstxt .= $keyword."\n";
		}
	$keywordstxt = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $keywordstxt);
	$file = "../".FILE_KEYWORDS."";
	$fp = fopen($file, "w+");
	fwrite($fp, $keywordstxt);
	fclose($fp);	
	echo "Your <strong>keywords.txt</strong> file has been succesfully cleaned!";
	echo "<br />";
	echo "<a href=\"javascript:history.go(-3)\">Go back</a>";

}
else {
if(isset($_GET['clean'])){
	$keywordy = $_POST['keywords'];
	$keywordum = explode("\n",$keywordy);
	$keywords = array_values(array_unique($keywordum));
	$badkeywords = file_get_contents("badkeywords.txt");
	$nasty = explode("\n",$badkeywords);
	$nastyCount = count($nasty);
	$keywordCount = count($keywords);
	
	echo '<table width="700px" cellpadding="8"><tr><td width="350px" valign="top" style="border-bottom: 1px solid #669900;border-right: 1px solid #669900;border-left:1px solid #669900;border-top:1px solid #669900;background:#ECECEC;"><br /><strong>Bad Keywords</strong><br /><br />';
	for ($k=0; $k<$keywordCount; $k++){
		for ($l=0; $l<$nastyCount; $l++){
		if(isset($_POST['adult'])){
			if (stristr($keywords[$k], $nasty[$l])){
				echo "<span style=\"color:red\">$keywords[$k]</span><br />";
				unset($keywords[$k]);
				}
			}
			if(isset($_POST['invalid'])){
			if (preg_match("/[^\w\s]/", $keywords[$k])){
				echo "<span style=\"color:red\">$keywords[$k]</span><br />";
				unset($keywords[$k]);
				}
			}
			}
		}

	echo '<br /><br /></td><td width="350px" valign="top" style="border-bottom: 1px solid #669900;border-right: 1px solid #669900;border-left:1px solid #669900;border-top:1px solid #669900;background:#ECECEC;"><br /><strong>"Good" keywords</strong><br /><br />';
	foreach($keywords AS $keyword){
	if(isset($_POST['format'])){
	$keyword = ucwords(strtolower($keyword));
	}
		echo "$keyword<br />";
		}
  	echo "<br /><br />";
	echo '<form id="save" name="form1" method="post" action="'.$_SERVER['PHP_SELF'].'?save=1">
    <input type="hidden" name="invalid" value="'.$_POST['invalid'].'" />
    <input type="hidden" name="adult" value="'.$_POST['adult'].'" />
    <input type="hidden" name="format" value="'.$_POST['format'].'" />
    <input type="hidden" name="keywords" value="'.$_POST['keywords'].'" />
    <input type="submit" name="Submit" value="Save keywords.txt" />
    </form>';
	echo "</td></tr></table>";
	}
else{
	$keywords = @file_get_contents("../".FILE_KEYWORDS."");
	echo '<table width="350px" cellpadding="8"><tr><td width="350px" valign="top" style="border-bottom: 1px solid #669900;border-right: 1px solid #669900;border-left:1px solid #669900;border-top:1px solid #669900;background:#ECECEC;"><br />';
	echo "Your <strong>keywords.txt</strong> file:<br /><br />";
	echo "<form name='clean' method='post' action='".$_SERVER['PHP_SELF']."?clean=1'>";
	echo "<textarea name='keywords' rows='30' cols='20'>";
	echo $keywords;
	echo "</textarea>";
  	echo "<br /><br />";
	echo '  <label>Invalid keywords<input name="invalid" type="checkbox" value="yes" checked="checked" /></label><br />
  <label>&nbsp;&nbsp;Adult keywords<input name="adult" type="checkbox" value="yes" /></label><br />
   <label>&nbsp;&nbsp;Fix capitalization<input name="format" type="checkbox" value="yes" checked="checked" /></label>';
  	echo "<br /><br />";
	echo "<input type='submit' name='Submit' value='Clean!'>";
	echo "</form>";
	echo "<br /><br /></td></tr></table>";
}
}
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