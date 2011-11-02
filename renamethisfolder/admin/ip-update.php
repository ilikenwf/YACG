<?php // UPDATE BOT LIST
require_once("functions.php");
$lists = array(
  'http://labs.getyacg.com/spiders/google.txt',
'http://labs.getyacg.com/spiders/inktomi.txt',
'http://labs.getyacg.com/spiders/lycos.txt',
'http://labs.getyacg.com/spiders/msn.txt',
'http://labs.getyacg.com/spiders/altavista.txt',
'http://labs.getyacg.com/spiders/askjeeves.txt',
'http://labs.getyacg.com/spiders/wisenut.txt',
);
$opt = '';
foreach($lists as $list) {
  $opt .= fetch($list, false)."\n";
}
$opt = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $opt);
$fp =  file_put_contents("../../".FILE_BOTS."", $opt);
print "Done! Your <strong>Bot List</strong> has been updated!<br /><a href=\"javascript:history.go(-1)\">Go back</a>";
?>