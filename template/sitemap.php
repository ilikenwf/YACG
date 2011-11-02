<? print '<?xml version="1.0" encoding="utf-8" ?>'."\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<? title() ?>
<link rel="stylesheet" type="text/css" href="<? template() ?>main.css" media="screen" />
</head>
<body>
<div class="container">

<div class="titleblock"><h1><? sitename() ?></h1><p><? description() ?></p></div>

<div><ul class="navbar">
		 <li><a href="<? domain() ?>" class="nav">Home</a></li>
		 <li><a href="contact-us" class="nav">Contact Us</a></li>
		 <li><a href="sitemap" class="nav">Sitemap</a></li>
</ul></div>

<div class="rightcontainer">

	<div class="rightbox"><h2>News</h2></div>

  <div class="rightbox linkbox"><h2>Links</h2>
  </div>
    <div class="rightbox">
<? adsense("120x600_as") ?>
</div>
</div>

<div class="content">
<h2><? keyword() ?></h2>
<div style="text-align:center">
<? sitemap() ?>
</div>
</div>
<div class="footer">
  <div class="right">
    <p>&copy; 2007 <? sitename() ?></p>
    <p><a href="http://validator.w3.org/check/referer">XHTML 1.1</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a></p>
  </div>
  <br />
    <p><a href="contact-us">Contact Us</a></p>
</div>

</div>
<? analytics() ?>
</body>
</html>