<?php //CONTACT FORM
require_once(LOCAL_HOOKS."mailer/Swift.php");
require_once(LOCAL_HOOKS."mailer/Swift/Connection/NativeMail.php");
$myemail = EMAIL;
$result = 'To contact us about this site, please use the form below...';
function check_email_address($email) {
  if (!ereg("[^@]{1,64}@[^@]{1,255}", $email)) {
    return false;
  }
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
     if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
      return false;
    }
  }  
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
        return false;
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
        return false;
      }
    }
  }
  return true;
}
$name = addslashes($_POST['name']);
$email = addslashes($_POST['email']);
$subject = addslashes($_POST['subject']);
$message = addslashes($_POST['message']);
if(isset($message)) {
	if (check_email_address($email)) {
		$swift =& new Swift(new Swift_Connection_NativeMail());
		$message =& new Swift_Message($subject, $message);
		if ($swift->send($message, $myemail, $email)) {
			$result = "<strong>Thanks for your feedback :)</strong>";
		}
		else { 
			$result = "<strong>Something went wrong... Please try again later :(<strong>";
		}
	}
}
?>