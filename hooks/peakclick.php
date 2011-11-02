<?php //PEAKCLICK HOOK
// Configure your AFFILIATE ID / SUBAFFILIATE ID at the config file
// Usage: peakclick(); Prints 5 results from Peakclick with the main page keyword
// peakclick('Google', 10); -> Prints 10 results from Peakclick with the keyword 'Google'
if (DEBUG == false) {
	error_reporting(0);
}
function peakclick($keyword = THIS_PAGE_KEYWORD, $items = 5) {

	$url = 'http://feed.peakclick.com/res.php?aff='._AFF.'&subaff='._SUBAFF.($keyword != '' ? '&keyword='.urlencode($keyword): '').'&num='.$items.'&ip='._IP;

	if (_THUMBS == '1') {
		$url .= '&thumbs=1';
		$lines = file($url);

		if (!substr_count(join('', $lines), 'ERROR:')) {
			if (count($lines)) {
				foreach($lines as $line_num => $line) {
					$result = explode('|', $line);
					$tur = explode('/', str_replace('https://', '', $result[3]));
					$targetUrlReal = $tur[0];
					$targetUrl = str_replace('https://', '', str_replace('http://', '', $result[4]));

					if ($targetUrl && $targetUrlReal && $result[2]) {
						echo'<table border="0" cellspacing="0" cellpadding="2">'."\n";
						echo'	<tr><td><a href="http://'.$targetUrl.'">'.$result[6].'</a></td>'."\n";
						echo'		<td valign="top">'."\n";
						echo'			<p><b><a href="http://'.$targetUrl.'">'.$result[1].'</a></b><br>'."\n";
						echo'			'.$result[2].'<br>'."\n";
						echo'			<a href="http://'.$targetUrl.'">'.$targetUrlReal.'</a>'."\n";
						echo'		</td>'."\n";
						echo'	</tr>'."\n";
						echo'</table>'."\n";
					}
				}
			} else {
				echo'No Peakclick was found!';
			}
		} else {
			echo'Error (wrong IP?)';
		}
	} else {
		$lines = file($url);

		if (!substr_count(join('', $lines), 'ERROR:')) {
			if (count($lines)) {
				foreach($lines as $line) {
					$result = explode('|', $line);
					$tur = explode('/', str_replace('https://', '', $result[3]));
					$targetUrlReal = $tur[0];
					$targetUrl = str_replace('https://', '', str_replace('http://', '', $result[4]));
					if ($targetUrl && $targetUrlReal && $result[2]) {
						echo'<p><b><a href="http://'.$targetUrl.'">'.$result[1].'</a></b><br>';
						echo $result[2].'<br>';
						echo'<a href="http://'.$targetUrl.'">'.$targetUrlReal.'</a>'."\n";
					}
				}
			} else {
				 	if (DEBUG == true) {
						echo "Nothing was found!";	
					}
			}
		} else {
			if (DEBUG == true) {
				echo "Error (Wrong IP?)";
			}
		}
	}
}

?>
