<?php // SOCIAL BOOKMARKING HOOK
function bookmarking($keyword = THIS_PAGE_KEYWORD) {
	$url = urlencode(THIS_PAGE_URL);
	$keyword = urldecode($keyword);
	$bookmarking = '';
	if (DIGG == true) {
		$bookmarking .= "\n".'<a href="http://digg.com/submit?phase=2&amp;title='.$keyword.'&amp;url='.$url.'"><img src="http://'.THIS_DOMAIN.'/img/digg.jpg" /></a>';
	}
	if (DELICIOUS == true) {
		$bookmarking .= "\n".'<a href="http://del.icio.us/post?title='.$keyword.'&amp;url='.$url.'"><img src="http://'.THIS_DOMAIN.'/img/delicious.jpg" /></a>';
	}
	if (REDDIT == true) {
		$bookmarking .= "\n".'<a href="http://reddit.com/submit?title='.$keyword.'&amp;url='.$url.'"><img src="http://'.THIS_DOMAIN.'/img/reddit.jpg" /></a>';
	}
	if (TECHNORATI == true) {
		$bookmarking .= "\n".'<a href="http://www.technorati.com/faves?add='.$url.'"><img src="http://'.THIS_DOMAIN.'/img/technorati.jpg" /></a>';
	}
	if (FURL == true) {
		$bookmarking .= "\n".'<a href="http://www.furl.net/storeIt.jsp?t='.$keyword.'&amp;u='.$url.'"><img src="http://'.THIS_DOMAIN.'/img/furl.jpg" /></a>';
	}
	if (ASK == true) {
		$bookmarking .= "\n".'<a href="http://myjeeves.ask.com/mysearch/BookmarkIt?v=true.2&amp;t=webpages&amp;title='.$keyword.'&amp;url='.$url.'"><img src="http://'.THIS_DOMAIN.'/img/ask.gif" /></a>';
	}
	if (GOOGLE == true) {
		$bookmarking .= "\n".'<a href="http://www.google.com/bookmarks/mark?op=add&amp;title='.$keyword.'&amp;bkmk='.$url.'"><img src="http://'.THIS_DOMAIN.'/img/google.jpg" /></a>';
	}
	if (YAHOO == true) {
		$bookmarking .= "\n".'<a href="http://myweb2.search.yahoo.com/myresults/bookmarklet?title='.$keyword.'&amp;popup=true&amp;u='.$url.'"><img src="http://'.THIS_DOMAIN.'/img/myweb.jpg" /></a>';
	}
	print $bookmarking;
}