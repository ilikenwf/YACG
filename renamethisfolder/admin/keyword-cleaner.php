<?php // KEYWORD CLEANER
require_once("functions.php");

function cleankeys() {
  $keywords = explode("\n", $_POST['keywords']);
  $keywords = array_map('trim', array_unique($keywords));
  $badkeywords = file('badkeywords.txt');
  $badkeywords = array_map('trim', $badkeywords);
  $clean = array();
  $bad = array();
  foreach ($keywords as $key) {
    if ($_POST['adult'] == 'yes') {
      foreach ($badkeywords as $nasty) {
        if (stristr($key, $nasty)) {
          array_push($bad, $key);
          continue 2;
        }
      }  
    }
    if($_POST['invalid'] == 'yes'){
      $re = UTF ? "/[^\p{L}\p{N}\w\s]/u" : "/[^\w\s]/";
      if (preg_match($re, $key)) {
        array_push($bad, $key);
        continue;
      }
    }
    if($_POST['format'] == 'yes') {
      $key = UTF ? utf8_ucwords(utf8_strtolower($key)) : ucwords(strtolower($key));
      //$re = UTF ? "/([\p{L}\p{N}])/eu" : "/(\w)/e";
      //$replacement = UTF ? "','.utf8_strtoupper('\\1')" : "','.strtoupper('\\1')";
      //$key = preg_replace($re, $replacement, $key);
    }
    array_push($clean, $key);
  }
  return array($clean, $bad);
}

if (isset($_GET['save'])) {
  list($clean, $bad) = cleankeys();
  $keywordstxt = implode("\n", $clean);
  $file = "../../".FILE_KEYWORDS."";
  file_put_contents($file, $keywordstxt);
  print "Your <strong>keywords.txt</strong> file has been succesfully cleaned!<br /><a href=\"javascript:history.go(-3)\">Go back</a>";
} elseif (isset($_GET['clean'])) {
  list($clean, $bad) = cleankeys();
  ?>
  <table width="700px" cellpadding="8">
    <tr>
      <td width="350px" valign="top" style="border-bottom: 1px solid #669900;border-right: 1px solid #669900;border-left:1px solid #669900;border-top:1px solid #669900;background:#ECECEC;">
        <h5>Bad Keywords</h5><br />
        <? foreach($bad AS $keyword) print "<span style=\"color:red\">$keyword</span><br />"; ?>
        <br /><br />
      </td>
      <td width="350px" valign="top" style="border-bottom: 1px solid #669900;border-right: 1px solid #669900;border-left:1px solid #669900;border-top:1px solid #669900;background:#ECECEC;">
        <h5>"Good" keywords</h5><br />
        <? foreach($clean AS $keyword) print "$keyword<br />"; ?>
        <br /><br />
        <form id="save" name="form1" method="post" action="keyword-cleaner.php?save=1">
          <input type="hidden" name="invalid" value="<? echo $_POST['invalid'] ?>" />
          <input type="hidden" name="adult" value="<? echo $_POST['adult'] ?>" />
          <input type="hidden" name="format" value="<? echo $_POST['format'] ?>" />
          <input type="hidden" name="keywords" value="<? echo $_POST['keywords'] ?>" />
          <input type="submit" name="Submit" value="Save keywords.txt" />
        </form>
      </td>
    </tr>
  </table>
  <?
} else {
  $keywords = @file_get_contents("../../".FILE_KEYWORDS."");
  ?>
  <table width="350px" cellpadding="8">
    <tr>
      <td width="350px" valign="top" style="border-bottom: 1px solid #669900;border-right: 1px solid #669900;border-left:1px solid #669900;border-top:1px solid #669900;background:#ECECEC;">
        <br />Your <strong>keywords.txt</strong> file:<br /><br />
        <form name='clean' method='post' action='keyword-cleaner.php?clean=1'>
          <textarea name='keywords' rows='30' cols='20'><? print $keywords ?></textarea>
          <br /><br />
          <label>Invalid keywords<input name="invalid" type="checkbox" value="yes" checked="checked" /></label><br />
          <label>&nbsp;&nbsp;Adult keywords<input name="adult" type="checkbox" value="yes" /></label><br />
          <label>&nbsp;&nbsp;Fix capitalization<input name="format" type="checkbox" value="yes" checked="checked" /></label>
          <br /><br />
          <input type='submit' name='Submit' value='Clean!'>
        </form>
        <br /><br />
      </td>
    </tr>
  </table>
  <?
}
?>