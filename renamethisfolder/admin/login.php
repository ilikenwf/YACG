<?php
setcookie("yacg", md5($_POST["password"]), time()+86400);
header("Location: index.php");
?>