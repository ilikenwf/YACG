<?php
require_once("functions.php");
$config = file_get_contents('../../config.inc.php');
if (isset($_REQUEST['action'])) {
  foreach ($_POST as $key => $value) {
    $value = preg_match("/^(false|true|\d+)$/", $value) ? $value : "'".$value."'";
    $config = preg_replace("/^define\(\s*?'".$key."'\s*?,\s*(.+?)\s*?\);(.*?)$/im", "define('$key', $value);\\2", $config);
  }
  file_put_contents('../../config.inc.php', $config);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>[YACG] Yet Another Content Generator <?php echo $version ?></title>
<style type="text/css">
<!--
body{font-family:Verdana, Arial, Helvetica, sans-serif; font-size:0.8em;}
a{text-decoration:none;color:#0000FF}
a:hover{text-decoration:underline;color:#0000FF}
a:visited{color:#0000FF}
#header {text-align:right; line-height:0.5em}
#menu {text-align:center}
#footer {text-align:center}
.container {margin: 0 auto; width: 780px}
.codex {margin: 0 auto; width: 90%;}
.date {font-weight:bold}
.red {color: #FF0000}
.style1 {color: #FFFFFF}
-->
</style>
</head>
<body>
<div class="container">
<h1 style="text-align:right">[YACG] Yet Another Content Generator <?php echo $version ?> </h1>
<table width="100%" style="text-align:center">
  <tr>
    <td width="30%" bgcolor="#000000"><h3 class="style1">Function</h3></td>
    <td width="70%" bgcolor="#000000"><h3 class="style1">Description </h3></td>
  </tr>
  <tr>
    <td width="30%"><h3><a href="cache-clean.php">Cache Cleaner </a></h3></td>
    <td width="70%">Clears all the cache </td>
  </tr>
  <tr>
    <td width="30%"><h3><a href="feed-generator.php">Feed Generator </a></h3></td>
    <td width="70%">Generate a RSS 2.0 feed</td>
  </tr>
  <tr>
    <td width="30%"><h3><a href="sitemap-generator.php">XML Sitemap Generator </a></h3></td>
    <td width="70%">Generate a XML sitemap</td>
  </tr>
  <tr>
    <td width="30%"><h3><a href="ip-update.php">Bot List Updater</a></h3></td>
    <td width="70%">Update the Bot IP list </td>
  </tr>
    <tr>
    <td width="30%"><h3><a href="pinger.php">Pinger</a></h3></td>
    <td width="70%">Ping Weblogs.Com, Blo.gs, Ping-o-Matic, Technorati and other ping services </td>
  </tr>
      <tr>
    <td width="30%"><h3><a href="keyword-cleaner.php">Keywords Cleaner Tool </a></h3></td>
    <td width="70%">Removes adult and other "bad keywords"</td>
  </tr>
  <tr>
    <td width="30%"><h3><a href="http://forums.getyacg.com">Support Forums</a></h3></td>
    <td width="70%">Support forums</td>
  </tr>
  <tr>
    <td colspan="2"><h3><a href="logout.php">Logout!</a></h3></td>
  </tr>
  </table>
<?
$configmod = substr(sprintf('%o', fileperms('../../config.inc.php')), -4);
if ($configmod !== '0777') {
  print '<span style="color:red">Your <strong>config.inc.php</strong> file has <strong>'.$configmod.'</strong> file permissions and most likely won\'t be writeable by this script. Please change permissions to <strong>777</strong> to ensure that changes made here will be saved.</span>';
}
$skipoptions = array('PICK_HOOKS', 'LOCAL_CACHE', 'LOCAL_ARTICLES', 'LOCAL_TEMPLATE', 'LOCAL_HOOKS', 'FILE_BOTS', 'FILE_KEYWORDS', 'FILE_KEYWORDS_TMP', 'FILE_CATEGORIES','FILE_KEYWORDS_TR', 'FILE_CATEGORIES_TR');
preg_match_all("/^define\(\s*?'(\w+?)'\s*?,\s*(.+?)\s*?\);(.*?)$/im", $config, $matches);
?>
<form action="index.php" method="post">
<input type="hidden" name="action" value="config" id="action">
<table width="100%" style="text-align:right;">
  <tr>
    <td colspan="2" style="text-align:center;"><h3>Site config:</h3></td>
  </tr>
<?
for ($i=0; $i < count($matches[0]); $i++) {
  if (!in_array($matches[1][$i], $skipoptions)) {
    print '<tr>
      <td width="30%"><label title="'.trim(str_replace('//', '', $matches[3][$i])).'">'.$matches[1][$i].': </label></td>
      <td width="70%" style="text-align:left;"><input title="'.trim(str_replace('//', '', $matches[3][$i])).'" type="text" name="'.$matches[1][$i].'" value="'.preg_replace("/(^'|'$)/", '', $matches[2][$i]).'" style="width:300px;"/></td>
    </tr>';
  }
}
?>
  <tr>
    <td colspan="2" style="text-align:center;"><input type="submit" name="Save" value="Save" id="Save"></td>
  </tr>
</table>
</form>
</div>
</body>
</html>