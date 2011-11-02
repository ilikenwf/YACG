<?php
require_once("functions.php");
$cookpass = $_COOKIE["yacg"];
$adminpass = md5($adminpass);
if($cookpass) {
    if($cookpass == $adminpass){
	header("Location: $first_page");
   }
    else{
	?>
<form id="login" name="login" method="post" action="login.php">
  <label>Password:
  <input name="password" type="password" id="password" />
  </label>
    <input type="submit" name="Submit" value="Submit" />
</form>
<?
    die();
    }
}
else{
?>
<form id="login" name="login" method="post" action="login.php">
  <label>Password:
  <input name="password" type="password" id="password" />
  </label>
    <input type="submit" name="Submit" value="Submit" />
</form>
<?
}
?>