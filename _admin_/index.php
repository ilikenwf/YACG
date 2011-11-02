<?php
require_once("functions.php");
$cookpass = $_COOKIE["yacg"];

if ($cookpass) {
	if ($cookpass == PASSWORD) {
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
<?php
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
<?php
}
?>