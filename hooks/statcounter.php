<?php // STATCOUNTER HOOK
if (DEBUG == false) {
	error_reporting(0);
}
function statcounter() {
	$statcounter = '';
	$statcounter .= "\n"."<script type=\"text/javascript\">";
	$statcounter .= "\n"."var sc_project=".STATCOUNTER_PROJECT.";";
	$statcounter .= "\n"."var sc_invisible=1;";
	$statcounter .= "\n"."var sc_partition=".STATCOUNTER_PARTITION.";";
	$statcounter .= "\n"."var sc_security=\"".STATCOUNTER_SECURITY."\";";
	$statcounter .= "\n"."</script>";
	$statcounter .= "\n\n"."<script type=\"text/javascript\" src=\"http://www.statcounter.com/counter/counter.js\">";
	$statcounter .= "\n"."</script>";
	$statcounter .= "\n"."<noscript>";
	$statcounter .= "\n"."<a href=\"http://www.statcounter.com/\" target=\"_blank\">";
	$statcounter .= "\n"."<img src=\"http://c20.statcounter.com/counter.php?sc_project=".STATCOUNTER_PROJECT."&java=0&security=".STATCOUNTER_SECURITY."&invisible=1\"></a>";
	$statcounter .= "\n"."</noscript>";
	print $statcounter;
}
?>