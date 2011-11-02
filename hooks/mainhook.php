<?php //BASIC FUNCTIONS

function give404($page) {
	header("HTTP/1.0 404 Not Found");
	header("Connection: close");
	die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
	<html><head><title>404 Not Found</title></head><body>
	<h1>Not Found</h1>
	<p>The requested URL '.$page.' was not found on this server.</p>
	<hr>
	<address>Apache/2.2.3 (Unix) Server at '.THIS_DOMAIN.' Port 80</address>
	</body></html>');
}

function savedata($data, $file) {
	$file = str_replace(" ", "-", $file);
	$fp = fopen(LOCAL_CACHE.$file, "w+");
	fwrite($fp, $data);
	fclose($fp);
}

function perm($path) {
	clearstatcache();
	$configmod = substr(sprintf('%o', fileperms($path)),  - 4);
	if ($configmod == '0777'){}
	 else {
		echo'Error with <strong>'.$path.'</strong>. Please chmod -c 777 this
	file/directory!';
		die();
	}
}

function titletag($keyword = THIS_PAGE) {
	switch ($keyword): case 'index.php':
		print"<title>".THIS_PAGE_KEYWORD." &raquo; ".THIS_DOMAIN."</title>\n";
		break;
	case 'sitemap.php':
		print"<title>Sitemap &raquo; ".THIS_DOMAIN."</title>\n";
		break;
	default:
		print"<title>".THIS_PAGE_KEYWORD." &raquo; ".THIS_DOMAIN."</title>\n";
		endswitch;
	}

function keyword($keyword = THIS_PAGE) {
	switch ($keyword): case 'index.php':
		print''.THIS_PAGE_KEYWORD.'';
		break;
	case 'sitemap.php':
		print''.THIS_PAGE_KEYWORD.'';
		break;
	default:
		print''.THIS_PAGE_KEYWORD.'';
		endswitch;
	}

function links($items = '', $ord = 'RAND') {
	$keywords = @file(FILE_KEYWORDS);
	array_shift($keywords);
	if ($items == null)
		$items = 999999999;
	switch ($ord): case 'RAND':
		@shuffle($keywords);
		$array_size = count($keywords) - $items;
		if ($array_size > 0)
			for ($c = 0; $c < $array_size; $c++)
				array_pop($keywords);
				print"\n";
		foreach($keywords as $keyword)print'<a href="http://'.THIS_DOMAIN.'/'.str_replace(" ", "-", trim($keyword)).'" title="'.trim($keyword).'">'.trim($keyword).'</a><br />'."\n";
		break;
	case 'DESC':
		$keywords = array_reverse($keywords);
		$array_size = count($keywords) - $items;
		if ($array_size > 0)
			for ($c = 0; $c < $array_size; $c++)
				array_pop($keywords);
				print"\n";
		foreach($keywords as $keyword)print'<a href="http://'.THIS_DOMAIN.'/'.str_replace(" ", "-", trim($keyword)).'" title="'.trim($keyword).'">'.trim($keyword).'</a><br />'."\n";
		break;
	default:
		$array_size = count($keywords) - $items;
		if ($array_size > 0)
			for ($c = 0; $c < $array_size; $c++)
				array_pop($keywords);
				print"\n";
		foreach($keywords as $keyword)print'<a href="http://'.THIS_DOMAIN.'/'.str_replace(" ", "-", trim($keyword)).'" title="'.trim($keyword).'">'.trim($keyword).'</a><br />'."\n";
		endswitch;
	}

function fetch($url) {
	$snoop = new Snoopy;
	$snoop->agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1)Gecko/20061010 Firefox/2.0';
	$snoop->fetch($url);
	return $snoop->results;
	}

function domain() {
	print'http://'.THIS_DOMAIN.'/';
	}

function template() {
	print LOCAL_TEMPLATE;
	}
	
?>