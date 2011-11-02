<?php // CONTACT FORM HOOK
if (!DEBUG) error_reporting(0);

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
	if (preg_match("/[A-Za-z0-9_.-]+@([A-Za-z0-9_-]+\.)?[A-Za-z0-9_-]+\.[A-Za-z]{2,6}$/i", $email)) {
		if (mail(EMAIL, $subject, $body)) {
			$result = CONTACT_FORM_2;
		}
	}	else {
		$result = CONTACT_FORM_ERROR_1;
	}
}
?>