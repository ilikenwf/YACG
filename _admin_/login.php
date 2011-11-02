<?php
require_once("functions.php");
$formpass = md5($_POST["password"]);
if ($formpass) {
	setcookie ("yacg");
	setcookie ("yacg", $formpass);
	header("Location: $first_page");
}
else {
	print INCORRECT_PASSWORD;
}
?>