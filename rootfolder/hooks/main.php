<?php // BASIC FUNCTIONS

function utf8_accents_and_strip($value = '') {
  return utf8_strip_non_ascii(utf8_accents_to_ascii($value));
}

function gettime() {
  $time = explode(' ', microtime());
  return $time[1] + $time[0];
}

//CASE-INSESITIVE ARRAY_SEARCH
function array_isearch($str, $array) {
  foreach($array as $k => $v) {
    if(strcasecmp($str, $v) == 0) return $k;
  }
  return false;
}

//DEFINE 'FILE_PUT_CONTENTS' FOR PHP4
if (!function_exists('file_put_contents')) {
  function file_put_contents($filename, $data) {
    $f = @fopen($filename, 'w+');
    if (!$f) {
      return false;
    } else {
      $bytes = fwrite($f, $data);
      fclose($f);
      return $bytes;
    }
  }
}

//RETURN GENERATED CONTENT INSTEAD OF PRINTING IT
function returntext($function = 'markov', $args = array()) {
  ob_start();
  call_user_func_array($function, $args);
  $contents = ob_get_contents();
  ob_end_clean();
  return $contents;
}

//SELECTIVE CACHING FUNCTION
function cache($function = 'markov', $args = array(), $keyword = THIS_PAGE_KEYWORD, $return = false) {
  $cachedfilename = LOCAL_CACHE.adashes($keyword).'.'.$function;
  $content = '';
  if (file_exists($cachedfilename) && (time() - CACHE_TIME < filemtime($cachedfilename))) {
    $content = file_get_contents($cachedfilename);
  } elseif ($args !== false) {
    $content = returntext($function, $args);
    file_put_contents($cachedfilename, $content);
  }
  //return or print
  if ($return)
    return $content;
  else
    print $content;
}

//CACHE HANDLING
function savecache($data, $file) {
  if (CACHE) {
    file_put_contents(LOCAL_CACHE.adashes($file), $data);
    return true;
  } else {
    return false;
  }
}

function loadcache($filename) {
	$file_path = LOCAL_CACHE.adashes($filename);
	if (CACHE && file_exists($file_path) && (time() - CACHE_TIME < filemtime($file_path))) {
		$cache = @file_get_contents($file_path);
		return $cache;
	}	else {
		return false;
	}
}

//PERMISSION CHECK
function perm($path) {
	clearstatcache();
	if (file_exists($path)) {
  	$configmod = substr(sprintf('%o', fileperms($path)), - 4);
  	if ($configmod !== '0777' && $configmod !== '0755' && DEBUG) die('<strong>Error - Please chmod correctly your files</strong>'."($path)");
  }
}

//FETCH CONTENT
function fetch($url, $postdata = false) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
	if (!ini_get('open_basedir') && !ini_get('safe_mode')) {
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
	}
	if ($postdata) {
	  curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	}
	if (PROXY) curl_setopt($ch, CURLOPT_PROXY, PROXY_IP.":".PROXY_PORT);
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}
//fetch from google cache
function fetchcache($url, $textonly = false) {
  $text = $textonly ? '&strip=1' : '';
  $response = fetch('http://google.com/search?q=cache:'.$url.$text, false);
  $response = strstr($response, '&copy;'.date('Y').' Google') ? false : $response;
  return $response;
}

//ADD DAILY DOSE OF KEYWORDS TO KEYWORDS.TXT AND SAVE
function sliceandsave($keywordstmp_list = array(), $keywords_list = array(), $numwords = 0) {
  $newkeywords = array_slice($keywordstmp_list, 0, $numwords);
  $keywords = implode("\n", array_merge($keywords_list, $newkeywords));
  $keywordstmp = array_slice($keywordstmp_list, $numwords);
  $keywordstmp = implode("\n", $keywordstmp);
  file_put_contents(FILE_KEYWORDS, $keywords);
  file_put_contents(FILE_KEYWORDS_TMP, $keywordstmp);
  if (CACHE_AUTO) {
    foreach ($newkeywords as $key)
      fetch(k2url($key), false);
  }
  return array($keywords, $keywordstmp);
}

//ADD KEYWORD(S)
function add_keyword($keyword = '') {
  global $catarr, $keyarr;
  $keyword = is_array($keyword) ? $keyword : array($keyword);
  $keynum = count($keywords);
  for ($i=0; $i < $keynum; $i++) {
    $newkey = preg_replace("/[^\w\d]/", ' ', $keyword[$i]);
    $newkeywords[] = CATEGORIES ? $catarr[rand(0, CAT_NUM-1)].','.$newkey : $newkey;
  }
  $keywords = implode("\n", array_merge($keyarr, $newkeywords));
  file_put_contents(FILE_KEYWORDS, $keywords);
}

//KEYWORD TRANSFORMATION
function rmdashes($value = '') {
  if (UTF && !TRANSLIT) $value = urldecode($value);
  return str_replace("-", " ", $value);
}

function adashes($value = '') {
  return str_replace(" ", "-", $value);
}

function k2url($keyword = '') {
  $keyword = $keyword == '' ? THIS_PAGE_CATEGORY.','.THIS_PAGE_KEYWORD : $keyword;
  $keyword = array_map('adashes', explode(",", $keyword));
  if (UTF) {
    if (TRANSLIT)
      $keyword = TRANSLIT_ADVANCED ? array_map('utf8_to_ascii',$keyword) : array_map('utf8_accents_and_strip',$keyword);
    else
      $keyword = array_map('urlencode', $keyword);
  }
  //$category = CATEGORIES ? $keyword[0].'/' : '';
	return strtolower('http://'.THIS_DOMAIN.'/'.PERMALINK.$keyword[count($keyword)-1].FILE_EXT);
}

function c2url($category = THIS_PAGE_CATEGORY) {
  $category = explode(",", $category);
  $category = adashes($category[0]);
  if (UTF) {
    if (TRANSLIT)
      $category = TRANSLIT_ADVANCED ? utf8_to_ascii($category) : utf8_accents_and_strip($category);
    else
      $category = urlencode($category);
  }
  return strtolower('http://'.THIS_DOMAIN.'/category/'.$category);
}

//KEYWORD PUBLICATION TIME
function ktime($keyword = THIS_PAGE_KEYWORD, $hook = PREVIEW_HOOK) {
  $filename = LOCAL_CACHE.adashes(cut_cat($keyword)).'.'.$hook;
  if (!file_exists($filename))
    $filename = LOCAL_CACHE.adashes(cut_cat($keyword)).'.html';
  return file_exists($filename) ? filemtime($filename) : filemtime(FILE_KEYWORDS);
}

//CATEGORIES HANDLING
function split_key($line) {
  return CATEGORIES ? explode(',', $line) : array('', $line);
}

function cut_cat($line) {
  return preg_replace("/^(.*?,)/", "", $line);
}

function strip_cats($lines) {
  return array_map('cut_cat', $lines);
}

function cut_key($line) {
  return preg_replace("/(^(.+?),.+?$|^[^,]*?$)/", "$2", $line);
}

function strip_keys($lines) {
  return array_unique(array_map('cut_key', $lines));
}

/**** BUILDING DIFFERENT ARRAYS OF LINKS ****/
//LIST OF ALL PAGES
function pages($ul = false) {
  global $pages;
  if ($ul) print "<ul>";
  foreach ($pages as $page) {
    print '<li><a href="http://'.THIS_DOMAIN.'/'.$page.'" title="'.ucwords(rmdashes($page)).'">'.ucwords(rmdashes($page))."</a></li>\n";
  }
  if ($ul) print "</ul>";
}
//LIST OF ALL CATEGORIES
function categories($ul = false) {
  global $categories, $catarr;
  $catarrtr = explode("\n", $categories);
  if ($ul) print "<ul>\n";
  for ($i=0; $i < count($catarr); $i++) { 
    print '<li><a href="http://'.THIS_DOMAIN.'/category/'.strtolower(urlencode(adashes($catarrtr[$i]))).'" title="'.$catarr[$i].'">'.$catarr[$i]."</a></li>\n";
  }
  if ($ul) print "\n</ul>";
}
//LIST OF ALL KEYS IN A CATEGORY
function list_category($bare = false, $cat = THIS_PAGE_CATEGORY, $preview = PREVIEW_HOOK) {
  global $keyarr;
  $links = array();
  foreach ($keyarr as $key) {
    if (preg_match("/^$cat,/i".UTFRE, $key)) {
      $pre = '';
      if (file_exists(LOCAL_CACHE.adashes(cut_cat($key)).".".$preview))
        $pre = substr(strip_tags(@file_get_contents(LOCAL_CACHE.adashes(cut_cat($key)).".".$preview)), 0, 300);
      $links[] = array('key' => cut_cat($key), 'url' => k2url($key), 'timestamp' => ktime($key), 'preview' => $pre);
    }
  }
  if ($bare) {
    return $links;
  } else {
    print "<ul>\n";
    foreach ($links as $link) {
      print '<li><a href="'.$link['url'].'" title="'.$link['key'].'">'.$link['key']."</a>";
      if ($link['preview'] != '') print "<p>".$link['preview']."...</p>";
      print "</li>\n";
    }
    print "\n</ul>\n";
  }
}
//LIST LINKS TO ARCHIVE
function archives($ul = false, $bare = false) {
  global $keyarr;
  $time1 = ktime($keyarr[0]);
  $time2 = ktime($keyarr[count($keyarr)-1]);

  $my = date('mY', $time2);
  $list = array();
  $f = '';

  while($time1 < $time2) {
    $time1 = strtotime((date('Y-m-d', $time1).' +15days'));
    if(date('F', $time1) != $f) {
      $f = date('F', $time1);
      if(date('mY', $time1) != $my && ($time1 < $time2))
        $list[] = array('date' => date('F Y', $time1), 'url' => 'http://'.THIS_DOMAIN.'/'.date('Y/m/', $time1));
    }
  }

  $list[] = array('date' => date('F Y', $time2), 'url' => 'http://'.THIS_DOMAIN.'/'.date('Y/m/', $time2));
  
  if ($bare) {
    return $list;
  } else {
    if ($ul) print "<ul>\n";
    foreach($list as $item) {
      print '<li><a href="'.$item['url'].'" title="'.$item['date'].'">'.$item['date']."</a></li>\n";
    }
    if ($ul) print "\n</ul>";
  }
}
//LIST OF ALL KEYS IN THE TIMEFRAME
function list_archive($bare = false, $cat = THIS_PAGE_CATEGORY, $preview = PREVIEW_HOOK) {
  global $keyarr;
  $links = array();
  $monthlater = strtotime(date('Y-m-d', $cat).' +1 month');
  foreach ($keyarr as $key) {
    $ktime = ktime($key);
    if ($ktime >= $cat) {
      if ($ktime > $monthlater) break;
      $pre = '';
      if (file_exists(LOCAL_CACHE.adashes(cut_cat($key)).".".$preview))
        $pre = substr(strip_tags(@file_get_contents(LOCAL_CACHE.adashes(cut_cat($key)).".".$preview)), 0, 300);
      $links[] = array('key' => $key, 'url' => k2url($key), 'timestamp' => $ktime, 'preview' => $pre);
    }
  }
  if ($bare) {
    return $links;
  } else {
    print "<ul>\n";
    foreach ($links as $link) {
      print '<li><a href="'.$link['url'].'" title="'.cut_cat($link['key']).'">'.cut_cat($link['key'])."</a>";
      if ($link['preview'] != '') print "<p>".$link['preview']."...</p>";
      print "</li>\n";
    }
    print "\n</ul>\n";
  }
}
//LIST OF SPECIFIED NUMBER OF KEYWORDS FROM THE BEGINNING/END OF THE LIST OR RANDOM
function links($items = '20', $ord = false, $tagcloud = false, $bare = false) {
  global $keyarr;
  $loacalarr = array_reverse($keyarr);
  switch ($ord): 
  case 'RAND':
    @shuffle($loacalarr);
  break;
  case 'DESC':
    rsort($loacalarr);
  break;
  case 'ASCE':
    arsort($loacalarr);
  break;
  default:
    $loacalarr;
  endswitch;
  $links = array();
  foreach (array_slice($loacalarr, 0, $items) as $key)
    $links[] = array('key' => $key, 'url' => k2url($key), 'timestamp' => ktime($key));
  if ($bare) {
    return $links;
  } elseif ($tagcloud) {
    $popularity = array('not-popular" style="font-size: 1em;',
      'not-very-popular" style="font-size: 1.3em;',
      'somewhat-popular" style="font-size: 1.6em;',
      'popular" style="font-size: 1.9em;',
      'very-popular" style="font-size: 2.2em;',
      'ultra-popular" style="font-size: 2.5em;');
    foreach ($links as $link) {
      shuffle($popularity);
      print "<a href='".$link['url']."' class=\"".$popularity[0]."\">".cut_cat($link['key'])."</a>";
    }
  } else {
    print "<ul>";
    foreach ($links as $link) {
      print '<li><a href="'.$link['url'].'" title="'.cut_cat($link['key']).'">'.cut_cat($link['key']).'</a></li>';
    }
    print "</ul>";
  }
}
//LIST OF SPECIFIED NUMBER OF KEYWORDS FROM THE BEGINNING/END OF THE LIST OR RANDOM WITH PREVIEW
function previews($items = '5', $ord = false, $bare = false, $full = false, $preview = PREVIEW_HOOK) {
  global $keyarr;
  $loacalarr = array_reverse($keyarr);
  switch ($ord): 
  case 'RAND':
    @shuffle($loacalarr);
  break;
  case 'DESC':
    rsort($loacalarr);
  break;
  case 'ASCE':
    arsort($loacalarr);
  break;
  default:
    $loacalarr;
  endswitch;
  $links = array();
  foreach (array_slice($loacalarr, 0, $items) as $key) {
    $pre = '';
    if (file_exists(LOCAL_CACHE.adashes(cut_cat($key)).".".$preview)) {
      $pre = strip_tags(@file_get_contents(LOCAL_CACHE.adashes(cut_cat($key)).".".$preview));
      if (!$full) $pre = substr($pre, 0, 300);
    }
    $links[] = array('key' => $key, 'url' => k2url($key), 'timestamp' => ktime($key), 'preview' => $pre);
  }
  if ($bare) {
    return $links;
  } else {
    print "<ul>";
    foreach ($links as $link)      
      print '<li><a href="'.$link['url'].'" title="'.cut_cat($link['key']).'">'.cut_cat($link['key']).'</a><p>'.$link['preview'].'</p></li>';
    print "</ul>";
  }
}
//LINKS TO PREVIOUS AND NEXT KEYWORD
function navigation($keyword = THIS_PAGE_KEYWORD, $category = THIS_PAGE_CATEGORY) {
  global $keyarr;
  $keyword = CATEGORIES ? $category.','.$keyword : $keyword;
	$key = array_search($keyword, $keyarr);
	$prev = isset($keyarr[$key-1]) ? $keyarr[$key-1] : '';
	$next = isset($keyarr[$key+1]) ? $keyarr[$key+1] : '';
	$navigation = '<div class="navigation">';
	if ($prev) {
		$navigation .= "\n".'<div style="float:left;text-align:left;">&laquo; <a href="'.k2url($prev).'" title="'.cut_cat($prev).'">'.cut_cat($prev).'</a></div>';
	}
	if ($next) {
		$navigation .= "\n".'<div style="float:right;text-align:right;"><a href="'.k2url($next).'" title="'.cut_cat($next).'">'.cut_cat($next).'</a> &raquo;</div>';
	}
	print $navigation.'</div>';
}

//ADD NICE INDENTATION TO OUTPUT
function indenter($buffer) {
  if (INDENT == true) {
    $indenter = '  ';
    $buffer = str_replace("\n", '', $buffer);
    $buffer = str_replace("\r", '', $buffer);
    $buffer = str_replace("\t", '', $buffer);
    $buffer = ereg_replace(">( )*", ">", $buffer);
    $buffer = ereg_replace("( )*<", "<", $buffer);
    $level = 0;
    $buffer_len = strlen($buffer);
    $pt = 0;
    while ($pt < $buffer_len) {
      if ($buffer{$pt} === '<') {
        $started_at = $pt;
        $tag_level = 1;
        if ($buffer{$pt+1} === '/') $tag_level = -1;
        if ($buffer{$pt+1} === '!') $tag_level = 0;
        while ($buffer{$pt} !== '>') $pt++;
        if ($buffer{$pt-1} === '/') $tag_level = 0;
        $tag_length = $pt+1-$started_at;
        if ($tag_level === -1) $level--;
        $array[] = str_repeat($indenter, $level).substr($buffer, $started_at, $tag_length);
        if ($tag_level === 1) $level++;
      }
      if (($pt+1) < $buffer_len) {
        if ($buffer{$pt+1} !== '<') {
          $started_at = $pt+1;
          while ($buffer{$pt} !== '<' && $pt < $buffer_len) $pt++;
          if ($buffer{$pt} === '<') {
            $tag_length = $pt-$started_at;
            $array[] = str_repeat($indenter, $level).substr($buffer, $started_at, $tag_length);
          }
        } else {
          $pt++;
        }
      } else {
        break;
      }
    }
    $buffer = implode($array, "\n");
    $buffer = str_replace("<!--","<!--\n",$buffer);
    $buffer = str_replace("//-->","\n//-->",$buffer);
    preg_match_all('/<a(.*?)>\\s*/m', $buffer, $result, PREG_PATTERN_ORDER);
    for ($i = 0; $i < count($result[0]); $i++) {
      $buffer = str_replace($result[0][$i],trim($result[0][$i]),$buffer);
    }
    preg_match_all('%\\s*</a>%m', $buffer, $result, PREG_PATTERN_ORDER);
    for ($i = 0; $i < count($result[0]); $i++) {
      $buffer = str_replace($result[0][$i],trim($result[0][$i]),$buffer);
    }
    preg_match_all('/<textarea(.*?)>\\s*/m', $buffer, $result, PREG_PATTERN_ORDER);
    for ($i = 0; $i < count($result[0]); $i++) {
      $buffer = str_replace($result[0][$i],trim($result[0][$i]),$buffer);
    }
    preg_match_all('%\\s*</textarea>%m', $buffer, $result, PREG_PATTERN_ORDER);
    for ($i = 0; $i < count($result[0]); $i++) {
      $buffer = str_replace($result[0][$i],trim($result[0][$i]),$buffer);
    }
    preg_match_all('/<title>\\s*/m', $buffer, $result, PREG_PATTERN_ORDER);
    for ($i = 0; $i < count($result[0]); $i++) {
      $buffer = str_replace($result[0][$i],trim($result[0][$i]),$buffer);
    }
    preg_match_all('%\\s*</title>%m', $buffer, $result, PREG_PATTERN_ORDER);
    for ($i = 0; $i < count($result[0]); $i++) {
      $buffer = str_replace($result[0][$i],trim($result[0][$i]),$buffer);
    }
    return $buffer;
  } else {
    return $buffer;
  }
}

//SHORTCUTS
function error404() {
	header("HTTP/1.0 404 Not Found");
	die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
  <html><head><title>404 Not Found</title></head><body>
  <h1>Not Found</h1>
  <p>The requested URL '.$_SERVER['REQUEST_URI'].' was not found on this server.</p>
  <hr>
  <address>Apache/2.2.3 (Unix) Server at '.THIS_DOMAIN.' Port 80</address>
  </body></html>');
}
function redirect301($url = THIS_DOMAIN) {
	header("HTTP/1.1 301 Moved Permanently");
  header("Location: http://".$url);
  exit();
}

function printerror($message='') {
  if (DEBUG) print $message;
  return NULL;
}

function title($keyword = THIS_PAGE) {
	if ($keyword == 'index.php') {
    print "<title>".SITE_NAME."</title>\n";
	} else {
	  print "<title>".SITE_NAME." &raquo; ".THIS_PAGE_KEYWORD."</title>\n";
	}
}

function metakeywords($keyword = THIS_PAGE) {
	if ($keyword == 'index.php' || $keyword == 'sitemap.php') {
	  $firstkey = SITE_NAME;
	} else {
	  $firstkey = THIS_PAGE_KEYWORD;
	}
	print '<meta name="keywords" content="'.$firstkey.'" />'."\n";
}

function metadescription($function = PREVIEW_HOOK, $args = array()) {
	print '<meta name="description" content="';
  print substr(strip_tags(returntext('cache', array($function, $args))), 0, 300);
	print "\" />\n";
}

function feed() {
  print '<link rel="alternate" type="application/rss+xml" title="'.SITE_NAME.' RSS Feed" href="http://'.THIS_DOMAIN.'/feed.xml" />'."\n";
}
function domain() {
	print 'http://'.THIS_DOMAIN.'/';
}
function description() {
	print SITE_DESCRIPTION;
}
function template() {
	print LOCAL_TEMPLATE;
}
function sitename() {
	print SITE_NAME;
}
function keyword() {
	print THIS_PAGE_KEYWORD;
}
function category() {
	print THIS_PAGE_CATEGORY;
}
?>