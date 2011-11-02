<?php // GOOGLE ANALYTICS HOOK
if (DEBUG == false) {
	error_reporting(0);
}
function analytics($return = false) {
	$analytics = '';
	$analytics .= "\n"."<script src=\"http://www.google-analytics.com/urchin.js\" type=\"text/javascript\">";
	$analytics .= "\n"."</script>";
	$analytics .= "\n"."<script type=\"text/javascript\">";
	$analytics .= "\n"."_uacct = \"".GOOGLE_ANALYTICS_ACCOUNT."\";";
	$analytics .= "\n"."urchinTracker();";
	$analytics .= "\n"."</script>";
	$analytics .= "\n";
	if ($return !== true) {
		print $analytics;
	}
	else {
		return $analytics;
	}
}

?>