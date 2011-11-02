<?php //GOOGLE ANALYTICS HOOK
// Configure your ANALYTICS ACCOUNT at the config file
// 	  Usage: analytics(); -> Prints the Google Analytics code to track your visitors
if (DEBUG == false) {
	error_reporting(0);
}
function analytics() {
  $analytics = '';
	$analytics .= "\n"."<script src=\"http://www.google-analytics.com/urchin.js\" type=\"text/javascript\">";
	$analytics .= "\n"."</script>";
	$analytics .= "\n"."<script type=\"text/javascript\">";
	$analytics .= "\n"."_uacct = \"".ANALYTICS_ACCOUNT."\";";
	$analytics .= "\n"."urchinTracker();";
	$analytics .= "\n"."</script>";
	$analytics .= "\n";
	print $analytics;
}

?>
