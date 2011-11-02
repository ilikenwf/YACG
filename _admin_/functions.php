<?php
require_once("../config.inc.php");
require_once("../thesarus.inc.php");
require_once("../".LOCAL_HOOKS."main.php");
require_once("../".LOCAL_HOOKS."markov.php");
if (DEBUG == false) {
	error_reporting(0);
}
// LOGIN INFORMATION
$first_page = "main.php";
?>