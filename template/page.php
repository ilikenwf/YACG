<? print '<?xml version="1.0" encoding="utf-8" ?>'."\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<? metakeywords() ?>
<? //metadescription() ?>
<? title() ?>
<link href="<? template(); ?>style.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<? template(); ?>thickbox.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="<? template(); ?>js/jquery.js"></script>
<script type="text/javascript" src="<? template(); ?>js/thickbox.js"></script>
</head>
<body>
<div id="wrapper">
  <div id="content">
    <div id="header">
      <div id="logo">
        <h1><? sitename() ?></h1>
        <h4>everything right here</h4>
      </div>
      <div id="links">
        <ul>
          <li><a href="<? domain() ?>" title="<? sitename() ?>">Home</a></li>
          <li><a href="contact-us" title="Contact Us">Contact Us</a></li>
          <li><a href="sitemap" title="Sitemap">Sitemap</a></li>
        </ul>
      </div>
    </div>
    <div id="mainimg">
      <h3><? keyword() ?></h3>
    </div>
    <div id="contentarea">
      <div id="leftbar">
	  	<div id="padding-left">
	  		<br /><? navigation() ?><br />
        	<h2><? keyword() ?></h2>
        	<div><? wikipedia() ?></div>
			<br />
			<div style="text-align:center"><? youtube() ?></div>
			<br />
			<h2>Even more about <? keyword() ?></h2>
			<div><? live(THIS_PAGE_KEYWORD,'2') ?></div>
      	</div>
	  </div>
      <div id="rightbar">
  	  	<div id="padding-right">
	        <h2>media</h2>
			<p><? flickr() ?></p>
		</div>
      </div>
    </div>
    <div id="bottom">
      <div id="email"><span>&copy; 2006–2007 <a href="<? domain() ?>" title="<? sitename() ?>"><? sitename() ?></a></span></div>
      <div id="validtext">
        <p>Valid <a href="http://validator.w3.org/check?uri=referer" title="XHTML Valid">XHTML</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer" title="CSS Valid">CSS</a> &mdash; <a href="sitemap" title="Sitemap">Sitemap</a></p>
      </div>
    </div>
  </div>
</div>
</body>
</html>