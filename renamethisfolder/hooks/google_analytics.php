<?php // GOOGLE ANALYTICS HOOK
if (!DEBUG) error_reporting(0);

function analytics() {
  $analytics = "\n<script src=\"http://www.google-analytics.com/urchin.js\" type=\"text/javascript\"></script>\n
<script type=\"text/javascript\">\n
_uacct = \"".GOOGLE_ANALYTICS_ACCOUNT."\";\n
urchinTracker();\n
</script>\n";
  print $analytics;
}

?>