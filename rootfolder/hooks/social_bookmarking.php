<?php // SOCIAL BOOKMARKING HOOK
function bookmarking($keyword = THIS_PAGE_KEYWORD, $services = 'addthis') {
	$url = urlencode(THIS_PAGE_URL);
	$keyword = urlencode($keyword);
	$imgpath = 'http://'.THIS_DOMAIN.str_replace(array('.'), '', LOCAL_IMAGE_CACHE);
	
	$service = array(
	                  'digg' => "\n".'<a href="http://digg.com/submit?phase=2&amp;title='.$keyword.'&amp;url='.$url.'"><img src="'.$imgpath.'digg.jpg" /></a>',
	                  'delicious' => "\n".'<a href="http://del.icio.us/post?title='.$keyword.'&amp;url='.$url.'"><img src="'.$imgpath.'delicious.jpg" /></a>',
	                  'reddit' => "\n".'<a href="http://reddit.com/submit?title='.$keyword.'&amp;url='.$url.'"><img src="'.$imgpath.'reddit.jpg" /></a>',
	                  'technorati' => "\n".'<a href="http://www.technorati.com/faves?add='.$url.'"><img src="'.$imgpath.'technorati.jpg" /></a>',
	                  'furl' => "\n".'<a href="http://www.furl.net/storeIt.jsp?t='.$keyword.'&amp;u='.$url.'"><img src="'.$imgpath.'furl.jpg" /></a>',
	                  'ask' => "\n".'<a href="http://myjeeves.ask.com/mysearch/BookmarkIt?v=true.2&amp;t=webpages&amp;title='.$keyword.'&amp;url='.$url.'"><img src="'.$imgpath.'ask.gif" /></a>',
	                  'google' => "\n".'<a href="http://www.google.com/bookmarks/mark?op=add&amp;title='.$keyword.'&amp;bkmk='.$url.'"><img src="'.$imgpath.'google.jpg" /></a>',
	                  'yahoo' => "\n".'<a href="http://myweb2.search.yahoo.com/myresults/bookmarklet?title='.$keyword.'&amp;popup=true&amp;u='.$url.'"><img src="'.$imgpath.'myweb.jpg" /></a>'
	                  );
	
	if ($services == 'addthis') {
	  print <<<HTML
    <!-- AddThis Button BEGIN -->
    <script type="text/javascript">addthis_pub  = '';</script>
    <a href="http://www.addthis.com/bookmark.php" onmouseover="return addthis_open(this, '', '[URL]', '[TITLE]')" onmouseout="addthis_close()" onclick="return addthis_sendto()"><img src="http://s7.addthis.com/button1-share.gif" width="125" height="16" border="0" alt="" /></a><script type="text/javascript" src="http://s7.addthis.com/js/152/addthis_widget.js"></script>
    <!-- AddThis Button END -->
HTML;
	} elseif ($services == 'all') {
	  foreach($service as $name => $url) print $url;
	} else {
	  $names = explode(',', $services);
	  foreach($names as $name) print $service[$name];
	}
}