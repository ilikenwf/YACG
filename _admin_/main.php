<?php
require_once("functions.php");
$cookpass = $_COOKIE["yacg"];
if ($cookpass) {
	if ($cookpass == md5(PASSWORD)) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>[YACG] Yet Another Content Generator 2.0</title>
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
<h1 style="text-align:right">[YACG] Yet Another Content Generator 2.0 </h1>
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
    <td width="30%"><h3><a href="keyword-cleaner.php">"Bad Keywords&quot; Removal Tool </a></h3></td>
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
</div>
</body>
</html>
<?
	}
	else{
		echo(INCORRECT_PASSWORD);
		die();
	}
}
else{
	echo(NOT_LOGGED_IN);
}
?>