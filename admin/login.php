<?php
require_once("functions.php");
$formpass = $_POST["password"];
$formpass = md5($formpass);
if($formpass) {
    setcookie ("yacg");
  	setcookie ("yacg", $formpass);
    header("Location: $first_page");
    }
    else {
    echo $incorrect_password;
    }
?>