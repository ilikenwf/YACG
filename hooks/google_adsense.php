<?php //GOOGLE ADSENSE HOOK
// Configure your PUB-ID / CHANNEL at the config file
// Usage: adsense($ad_format, $color_border, $color_bg, $color_link, $color_url, $color_text);
// adsense('468x60_as'); -> Prints a 468x60_as ad unit with the default colors
// adsense('468x60_as', 'FFFFFF', 'FFFFFF', '1480CD', '000000', '000000'); -> Prints a 468x60_as ad unit with custom colors
			
function adsense($ad_format, $color_border="FFFFFF", $color_bg="FFFFFF", $color_link="1480CD", $color_url="000000", $color_text="000000"){
	$ad = '';
	if (GOOGLE_SHOW_ADS == 1) {
		switch($ad_format) {	
			//Text only ads
			case "728x90_as": //Leaderboard
				$ad_width = "728";
				$ad_height = "90";
				$ad_type = "text";
			break;			
			case "468x60_as": //Banner
				$ad_width = "468";
				$ad_height = "60";
				$ad_type = "text";
			break;			
			case "336x280_as": //Large Rectangle
				$ad_width = "336";
				$ad_height = "280";
				$ad_type = "text";
			break;			
			case "300x250_as": //Medium Rectangle
				$ad_width = "300";
				$ad_height = "250";
				$ad_type = "text";
			break;			
			case "250x250_as": //Square
				$ad_width = "250";
				$ad_height = "250";
				$ad_type = "text";
			break;			
			case "234x60_as": //Half Banner
				$ad_width = "234";
				$ad_height = "60";
				$ad_type = "text";
			break;			
			case "180x150_as": //Small Rectangle
				$ad_width = "180";
				$ad_height = "150";
				$ad_type = "text";
			break;			
			case "160x600_as": //Wide Skyscraper
				$ad_width = "160";
				$ad_height = "600";
				$ad_type = "text";
			break;			
			case "125x125_as": //Button
				$ad_width = "125";
				$ad_height = "125";
				$ad_type = "text";
			break;			
			case "120x600_as": //Skyscraper
				$ad_width = "120";
				$ad_height = "600";
				$ad_type = "text";
			break;			
			case "120x240_as": //Vertical Banner
				$ad_width = "120";
				$ad_height = "240";
				$ad_type = "text";
			break;			
			//Link Units (4 links per unit)
			case "728x15_0ads_al":
				$ad_width = "728";
				$ad_height = "15";
				$ad_type = "";
			break;			
			case "468x15_0ads_al":
				$ad_width = "468";
				$ad_height = "15";
				$ad_type = "";
			break;			
			case "200x90_0ads_al":
				$ad_width = "200";
				$ad_height = "90";
				$ad_type = "";
			break;			
			case "180x90_0ads_al":
				$ad_width = "180";
				$ad_height = "90";
				$ad_type = "";
			break;			
			case "160x90_0ads_al":
				$ad_width = "160";
				$ad_height = "90";
				$ad_type = "";
			break;			
			case "120x90_0ads_al":
				$ad_width = "120";
				$ad_height = "90";
				$ad_type = "";
			break;			
			//Link Units (5 links per unit)
			case "728x15_0ads_al_s":
				$ad_width = "728";
				$ad_height = "15";
				$ad_type = "";
			break;			
			case "468x15_0ads_al_s":
				$ad_width = "468";
				$ad_height = "15";
				$ad_type = "";
			break;			
			case "200x90_0ads_al_s":
				$ad_width = "200";
				$ad_height = "90";
				$ad_type = "";
			break;			
			case "180x90_0ads_al_s":
				$ad_width = "180";
				$ad_height = "90";
				$ad_type = "";
			break;			
			case "160x90_0ads_al_s":
				$ad_width = "160";
				$ad_height = "90";
				$ad_type = "";
			break;			
			case "120x90_0ads_al_s":
				$ad_width = "120";
				$ad_height = "90";
				$ad_type = "";
			break;
		}
$ad = '';    			
$ad =  
"\n" . "<script type=\"text/javascript\"><!--
google_ad_client = \"".GOOGLE_PUBID."\";
google_ad_width = $ad_width;
google_ad_height = $ad_height;
google_ad_format = \"$ad_format\";";
if ($ad_type !== "") {
$ad .= "\n" . "google_ad_type = \"$ad_type\";";
}
$ad .= "\n" . "google_ad_channel =\"".GOOGLE_ADCHANNEL."\";
google_color_border = \"$color_border\";
google_color_bg = \"$color_bg\";
google_color_link = \"$color_link\";
google_color_url = \"$color_url\";
google_color_text = \"$color_text\";
//--></script>
<script type=\"text/javascript\" src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\">
</script>";
$ad .= "\n";
	}
	print $ad;
	}
?>
