<?php // STATCOUNTER HOOK
if (!DEBUG) error_reporting(0);

function statcounter() {
	$statcounter = "\n<script type=\"text/javascript\">
	\nvar sc_project=".STATCOUNTER_PROJECT.";
	\nvar sc_invisible=1;\n
	var sc_partition=".STATCOUNTER_PARTITION.";\n
	var sc_security=\"".STATCOUNTER_SECURITY."\";\n
	</script>\n\n
	<script type=\"text/javascript\" src=\"http://www.statcounter.com/counter/counter.js\">\n
	</script>\n
	<noscript>\n
	<a href=\"http://www.statcounter.com/\" target=\"_blank\">\n
	<img src=\"http://c20.statcounter.com/counter.php?sc_project=".STATCOUNTER_PROJECT."&java=0&security=".STATCOUNTER_SECURITY."&invisible=1\"></a>\n
	</noscript>";
	print $statcounter;
}
?>