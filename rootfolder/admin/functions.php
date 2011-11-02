<?php
header('Content-Type: text/html;charset=utf-8');
require_once "../../config.inc.php";
if (!defined('THIS_DOMAIN')) define('THIS_DOMAIN', preg_replace(array("/www\./i", "/\/".preg_quote(str_replace('./', '', ROOT_DIR), '/')."admin\/[\w-]+?\.php/i"), '', $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']));

error_reporting(DEBUG ? E_ALL^E_NOTICE : 0);

// REQUIRE MAIN HOOK
require_once "../../".LOCAL_HOOKS.'main.php';
//REQUIRE SIMPLEPIE
require_once "../../".ROOT_DIR.'includes/simplepie.inc';

//UTF-8 SUPPORT
if (UTF) {
  require_once '../includes/utf8/utf8.php';
  require_once UTF8.'/ucwords.php';
  if (TRANSLIT) {
    require_once UTF8.'/utils/ascii.php';
    require_once UTF8.'/utf8_to_ascii.php';
  } else {
    $utfre = 'u';
  }
}
define('UTFRE', $utfre);

// PICK HOOKS
if (PICK_HOOKS) {
  foreach ($hooks as $hook)
    require_once "../../".LOCAL_HOOKS.$hook;
} elseif ($dh = @opendir("../../".LOCAL_HOOKS)) {
  while (($file = readdir($dh)) !== false) 
    if (substr($file, - 4) == '.php') require_once "../../".LOCAL_HOOKS.$file;
  closedir($dh);
}

ignore_user_abort(true);
set_time_limit(0);

$cookpass = isset($_COOKIE['yacg']) ? $_COOKIE['yacg'] : false;
if (!$cookpass && isset($_GET["password"])) $cookpass = md5($_GET["password"]);
if ($cookpass != md5(PASSWORD)) {
	if ($cookpass) print "<h4 style=\"color:red\">".'Wrong password!<br /><a href="javascript:history.go(-1)">Go back</a>'."</h4>";
?>
    <center>
    <form id="login" name="login" method="post" action="login.php">
    <label>Password:
      <input name="password" type="password" id="password" />
    </label>
    <input type="submit" name="Submit" value="Submit" />
    </form>
    </center>
<?
exit();
}
?>