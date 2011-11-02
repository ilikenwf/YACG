<?php // CONTACT FORM HOOK
if (DEBUG == false) {
	error_reporting(0);
}
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
function contact_form() {
	global $result;
	print $result;
	print '<form id="contact" method="post" action="">
<table style="width:50%;">
<tr>
<td style="text-align:right">Name:</td>
<td><input name="name" type="text" size="50" /></td>
</tr>
<tr>
<td style="text-align:right">Email:</td>
<td><input name="email" type="text" size="50" /></td>
</tr>
<tr>
<td style="text-align:right">Subject:</td>
<td><input name="subject" type="text" size="50" /></td>
</tr>
<tr>
<td style="text-align:right">Message:</td>
<td><textarea name="message" cols="50" rows="10"></textarea></td>
</tr>
<tr>
<td style="text-align:right"></td>
<td>
<input type="submit" name="Submit" value="Submit" /></td>
</tr>
</table>
</form>';
}
$result = CONTACT_FORM_1;
if(isset($_POST['message'])) {
	$name = addslashes($_POST['name']);
	$email = addslashes($_POST['email']);
	$subject = addslashes($_POST['subject']);
	$body = addslashes($_POST['message']);
	if (check_email_address($email)) {
		if (mail(EMAIL, $subject, $body)) {
			$result = CONTACT_FORM_2;
		}
	}
	else {
		$result = CONTACT_FORM_ERROR_1;
	}
}
?>