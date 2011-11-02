<?php
header('Content-Type: text/html;charset=utf-8');
//GETTING CONFIGURATION OPTIONS
require_once './config.inc.php';
error_reporting(DEBUG ? E_ALL^E_NOTICE : 0);
ignore_user_abort(true);
set_time_limit(0);
if (!defined('THIS_DOMAIN')) define('THIS_DOMAIN', str_replace(array('www.', '/index.php'), '', $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']));
require_once ROOT_DIR.'thesarus.inc.php';
if (strstr(THIS_DOMAIN, $_SERVER['SERVER_ADDR'])) {
  header("HTTP/1.0 404 Not Found");
  die(ERROR_404);
}
/* REQUIRE HOOKS */
// ENABLE UTF-8 SUPPORT
$utfre = '';
if (UTF) {
  require_once ROOT_DIR.'includes/utf8/utf8.php';
  //TRANSLITERATE OR TREAT EVERYTHING AS UNICODE
  if (TRANSLIT) {
    require_once UTF8.'/utils/ascii.php';
    require_once UTF8.'/utf8_to_ascii.php';
  } else
    $utfre = 'u';
}
define('UTFRE', $utfre);
// REQUIRE MAIN HOOK
require_once(LOCAL_HOOKS.'main.php');
// PICK HOOKS
if (PICK_HOOKS) {
  foreach ($hooks as $hook)
    require_once LOCAL_HOOKS.$hook;
} elseif ($dh = @opendir(LOCAL_HOOKS)) {
  while (($file = readdir($dh)) !== false) 
    if (substr($file, - 4) == '.php') require_once LOCAL_HOOKS.$file;
  closedir($dh);
}
/* */

// GET KEYWORDS FILES
$keywords = @file_get_contents(FILE_KEYWORDS);// GET KEYWORDS
$categories = CATEGORIES ? @file_get_contents(FILE_CATEGORIES) : '';// GET CATEGORIES

//PERFORM PERMISSIONS AND KEYWORD FILE INTEGRITY CHECKS IF IN DEBUG MODE
if (DEBUG) {
  $files = array('./config.inc.php', './feed.xml', './sitemap.xml', ROOT_DIR, LOCAL_CACHE, LOCAL_IMAGE_CACHE, FILE_KEYWORDS);
  if (PHP_SHLIB_SUFFIX != 'dll')
    foreach ($files as $file) perm($file);
  if ($keywords == false) die(KEYWORDS_ERROR_1);
  $re = UTF ? "/[^\p{L}\p{N}\w\s,]/u" : "/[^\w\s,]/";
  if (preg_match($re, $keywords)) die(KEYWORDS_ERROR_2);
  if (preg_match("/,/", $keywords) && preg_match("/^[^,]+?$/ims", $keywords)) die(KEYWORDS_ERROR_2);
  $start_time = gettime();
}

/* UPDATING CATEGORIES AND KEYWORD LISTS */
clearstatcache();
// BUILD CATEGORIES LIST
if (CATEGORIES && !file_exists(FILE_CATEGORIES)) {
	$keywords_list = array_map('trim', explode("\n", $keywords));
	$cats = strip_keys($keywords_list);
	if (count($cats) <= 1) {
    $catcount = CAT_NUM ? CAT_NUM : rand(5, 15);
    $cats = array_slice($keywords_list, 0, $catcount);
    $keywords = array_slice($keywords_list, $catcount);
    for ($i=0; $i < count($keywords); $i++)
      $keywords[$i] = $cats[rand(0, $catcount-1)].','.$keywords[$i];
    $keywords = implode("\n", $keywords);
    file_put_contents(FILE_KEYWORDS, $keywords);
    @chmod(FILE_KEYWORDS, FILEMODE);
  }
  $categories = implode("\n", $cats);
  file_put_contents(FILE_CATEGORIES, $categories);
  if (UTF && TRANSLIT) {
    $categories = TRANSLIT_ADVANCED ? utf8_to_ascii($categories) : utf8_accents_and_strip($categories);
    file_put_contents(FILE_CATEGORIES_TR, $categories);
  }
}
// ADD DAILY DOSE OF KEYWORDS
if (START_KEYS && (filemtime(FILE_KEYWORDS) < time() - 86400 || !file_exists(FILE_KEYWORDS_TMP))) {
  $keywordstmp = @file_get_contents(FILE_KEYWORDS_TMP);
  if (!file_exists(FILE_KEYWORDS_TMP)) {
    $keywordstmp_list = array_map('trim', explode("\n", $keywords));
    $keyarr = sliceandsave($keywordstmp_list, array(), START_KEYS);
    @chmod(FILE_KEYWORDS_TMP, FILEMODE);
    $keywords = $keyarr[0];
    $keywordstmp = $keyarr[1];
  } elseif ($keywordstmp != '') {
    $keywordstmp_list = explode("\n", $keywordstmp);
    $keywords_list = explode("\n", $keywords);
    $keyarr = sliceandsave($keywordstmp_list, $keywords_list, rand(DAILY_MIN, DAILY_MAX));
    $keywords = $keyarr[0];
    $keywordstmp = $keyarr[1];
  }
  if (isset($keyarr)) {
    // UPDATE RSS FEED AND SITEMAP
    $adminurl = 'http://'.THIS_DOMAIN.'/'.str_replace('./', '', ROOT_DIR).'admin/';
    fetch($adminurl.'feed-generator.php?password='.PASSWORD, false);
    fetch($adminurl.'sitemap-generator.php?password='.PASSWORD, false);
    fetch($adminurl.'pinger.php?password='.PASSWORD, false);
  }
}
/* */

// REPLACE KEYWORDS AND CATEGORIES VARS FOR TRANSLITERATED COUNTERPARTS
if (UTF && TRANSLIT) {
  //UPDATE KEYWORDS.TR.TXT
  if (filemtime(FILE_KEYWORDS_TR) <= filemtime(FILE_KEYWORDS)) {
    $keywords = TRANSLIT_ADVANCED ? utf8_to_ascii($keywords) : utf8_accents_and_strip($keywords);
    file_put_contents(FILE_KEYWORDS_TR, $keywords);
  }
  $keywords = file_get_contents(FILE_KEYWORDS_TR);
  $categories = file_get_contents(FILE_CATEGORIES_TR);
}

// GET KEYWORDS AND CATEGORIES ARRAYS FOR HOOKS
$keyarr = file(FILE_KEYWORDS);
$keyarr = array_map('rtrim', $keyarr);
$catarr = file(FILE_CATEGORIES);
$catarr = array_map('rtrim', $catarr);

define('THIS_PAGE_URL', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
//EXTRACT CATEGORY AND/OR KEYWORD FROM URL
$keyword = explode("?", $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
$keyword = explode('/', rmdashes(substr(str_replace(array('www.', THIS_DOMAIN, PERMALINK, FILE_EXT), '', $keyword[0]), 1)));
//SET THIS_PAGE_CATEGORY
if (CATEGORIES && UTF && TRANSLIT && ($i = array_isearch($keyword[0], explode("\n", $categories))) !== false)
  define('THIS_PAGE_CATEGORY', $catarr[$i]);
else
  define('THIS_PAGE_CATEGORY', $keyword[0]);
//SELECT THE RIGHT PAGE AND SET THIS_PAGE_KEYWORD
if (CATEGORIES && count($keyword) == 1 && preg_match("/^".preg_quote($keyword[0])."$/im".UTFRE, $categories)) {
  define('THIS_PAGE', 'category.php');
  define('THIS_PAGE_KEYWORD', 'Category');
} elseif ((CATEGORIES && preg_match("/^".preg_quote($keyword[0])."\s*?,\s*?".preg_quote(isset($keyword[1]) ? $keyword[1] : '')."$/im".UTFRE, $keywords)) || (!CATEGORIES && preg_match("/^".preg_quote($keyword[0])."$/im".UTFRE, $keywords))) {
  define('THIS_PAGE', 'page.php');
  if (UTF && TRANSLIT) {
    $i = array_isearch($keyword[count($keyword)-1], strip_cats(explode("\n", $keywords)));
    if ($i !== false) define('THIS_PAGE_KEYWORD', cut_cat($keyarr[$i]));
  } else {
    define('THIS_PAGE_KEYWORD', $keyword[count($keyword)-1]);
  }
} elseif (count($keyword) == 1 && in_array(adashes($keyword[0]), $pages)) {
  define('THIS_PAGE', adashes($keyword[0].'.php'));
  define('THIS_PAGE_KEYWORD', ucfirst($keyword[0]));
} elseif ($keyword[0] == '') {
  define('THIS_PAGE', 'index.php');
  define('THIS_PAGE_KEYWORD', SITE_NAME);
} else {
  //PAGE NOT FOUND
  redirect301();
}

// LET THE TEMPLATE FILE TO TAKE OVER FROM HERE
ob_start('indenter');
if (CACHE && (THIS_PAGE == 'page.php' || THIS_PAGE == 'index.php')) {
  // CACHE CONTENT
  $cachefile_name = str_replace(' ', '-', THIS_PAGE_KEYWORD).'.html';
  $cachefile_path = LOCAL_CACHE.$cachefile_name;
  if (file_exists($cachefile_path) && (time() - CACHE_TIME < filemtime($cachefile_path))) {
    print @file_get_contents($cachefile_path);
    if (DEBUG) print "\n".'<!-- Cached on '.date('F jS, Y H:i', filemtime($cachefile_path)).' -->';
    exit();
  }
  require_once LOCAL_TEMPLATE.THIS_PAGE;
  file_put_contents($cachefile_path, indenter(ob_get_contents()));
} else {
  // DO NOT CACHE
  require_once LOCAL_TEMPLATE.THIS_PAGE;
}

if (DEBUG) print "\n".'<!-- Generated in '.(gettime() - $start_time).' seconds -->';
?>