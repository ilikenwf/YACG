<?php
header('Content-Type: text/html;charset=utf-8');
//GETTING/SETTING CONFIGURATION OPTIONS
require_once './config.inc.php';
if (!defined('THIS_DOMAIN')) define('THIS_DOMAIN', str_replace(array('www.', '/index.php'), '', $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']));

error_reporting(DEBUG ? E_ALL^E_NOTICE : 0);
ignore_user_abort(true);
set_time_limit(0);
clearstatcache();

// REQUIRE MAIN HOOK
require_once LOCAL_HOOKS.'main.php';
//REQUIRE SIMPLEPIE
require_once ROOT_DIR.'includes/simplepie.inc';

/* UTF-8 SETTINGS */
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
/* */

// GET KEYWORD FILES
$keywords = @file_get_contents(FILE_KEYWORDS);// GET KEYWORDS
$categories = CATEGORIES ? @file_get_contents(FILE_CATEGORIES) : '';// GET CATEGORIES

/* PERFORM PERMISSIONS AND KEYWORD FILE INTEGRITY CHECKS IF IN DEBUG MODE */
if (DEBUG) {
  $files = array('./config.inc.php', './feed.xml', './sitemap.xml', ROOT_DIR, LOCAL_CACHE, LOCAL_IMAGE_CACHE, FILE_KEYWORDS);
  if (PHP_SHLIB_SUFFIX != 'dll')
    foreach ($files as $file) perm($file);
  if ($keywords == false) die('<strong>Error - <em>keywords.txt</em> doesn\'t exist</strong>');
  $re = UTF ? "/[^\p{L}\p{N}\w\s\',]/u" : "/[^\w\s\',]/";
  if (preg_match($re, $keywords)) die('<strong>Error - <em>keywords.txt</em> can only contain letters &amp; numbers</strong>');
  if (preg_match("/,/", $keywords) && preg_match("/^[^,]+?$/ims", $keywords)) die('<strong>Error - <em>keywords.txt</em> can only contain letters &amp; numbers</strong>');
  $start_time = gettime();
}
/* */

// BUILD CATEGORIES LIST(DONE ONLY ON FIRST LAUNCH)
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

// REPLACE KEYWORDS AND CATEGORIES VARS WITH TRANSLITERATED COUNTERPARTS
if (UTF && TRANSLIT) {
  //UPDATE KEYWORDS.TR.TXT
  if (filemtime(FILE_KEYWORDS_TR) <= filemtime(FILE_KEYWORDS)) {
    $keywords = TRANSLIT_ADVANCED ? utf8_to_ascii($keywords) : utf8_accents_and_strip($keywords);
    file_put_contents(FILE_KEYWORDS_TR, $keywords);
  }
  $keywords = file_get_contents(FILE_KEYWORDS_TR);
  $categories = @file_get_contents(FILE_CATEGORIES_TR);
}

// GET KEYWORDS AND CATEGORIES ARRAYS FOR HOOKS
$keyarr = file(FILE_KEYWORDS);
$keyarr = array_map('rtrim', $keyarr);
$catarr = @file(FILE_CATEGORIES);
$catarr = @array_map('rtrim', $catarr);
//ABSOLUTE URL OF THE CURRENT PAGE
define('THIS_PAGE_URL', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

//EXTRACT CATEGORY AND/OR KEYWORD FROM URL
$urlparts = explode("?", $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
$urlparts = array_values(array_filter(explode('/', rmdashes(str_replace(array('www.', THIS_DOMAIN, PERMALINK, FILE_EXT), '', $urlparts[0])))));

/* SELECT THE RIGHT PAGE AND SET 'THIS_PAGE_KEYWORD' */
$parts = count($urlparts);
$category = false;
//print_r($urlparts);exit;

if (!preg_match(UTF ? "/[^\p{L}\p{N}\w\s\']/u" : "/[^\w\s\']/", implode('', $urlparts))) {
  //IT'S CATEGORY PAGE
  if ($parts == 2 && $urlparts[0] == 'category' && preg_match("/^".$urlparts[1]."$/im".UTFRE, $categories)) {
    define('THIS_PAGE', 'category.php');
    define('THIS_PAGE_KEYWORD', 'Category');
    $category = ( UTF && TRANSLIT && $i = array_isearch($urlparts[1], explode("\n", $categories)) ) ? $catarr[$i] : ucwords($urlparts[1]);
  }
  //IT'S REGULAR PAGE
  elseif ( $parts == 1 && preg_match("/^".(CATEGORIES ? '(.+?),' : '').$urlparts[0]."$/im".UTFRE, $keywords, $cat) ) {
    define('THIS_PAGE', 'page.php');
    define('THIS_PAGE_KEYWORD', ( UTF && TRANSLIT && $i = array_isearch($urlparts[0], strip_cats(explode("\n", $keywords))) ) ? cut_cat($keyarr[$i]) : ucwords($urlparts[0]));
    $category = ( UTF && TRANSLIT && $i = array_isearch($cat[1], explode("\n", $categories)) ) ? $catarr[$i] : ucwords($cat[1]);
  }
  //IT'S ARCHIVE PAGE
  elseif ($parts == 2 && preg_match("/^\d+$/", implode('', $urlparts))) {
    define('THIS_PAGE', 'archive.php');
    define('THIS_PAGE_KEYWORD', 'Archive');
    $category = strtotime($urlparts[0].'-'.$urlparts[1].'-01');
  }
  //IT'S SOME PAGE FROM $pages ARRAY
  elseif ($parts == 1 && in_array(adashes($urlparts[0]), $pages)) {
    define('THIS_PAGE', adashes($urlparts[0].'.php'));
    define('THIS_PAGE_KEYWORD', ucfirst($urlparts[0]));
  }
  //IT'S HOMEPAGE
  elseif ($parts == 0) {
    define('THIS_PAGE', 'index.php');
    define('THIS_PAGE_KEYWORD', SITE_NAME);
  }
  else {
    $notfound = true;
  } 
}
else {
  $notfound = true;
}
//IT'S 404 ERROR PAGE
if ($notfound) {
  define('THIS_PAGE', '404.php');
  define('THIS_PAGE_KEYWORD', 'Page not found');
}
define('THIS_PAGE_CATEGORY', $category);
/* */

/* LOADING TEMPLATE PAGE & CACHING */
if (INDENT) {
  ob_start('indenter');
} else {
  ob_start();
}

//LOAD CACHED PAGE
$cachefile_path = LOCAL_CACHE.str_replace(' ', '-', THIS_PAGE_KEYWORD).'.html';
if (CACHE && THIS_PAGE == 'page.php' && file_exists($cachefile_path) && (time() - CACHE_TIME < filemtime($cachefile_path))) {  
  print @file_get_contents($cachefile_path);
  if (DEBUG) print "\n".'<!-- Cached on '.date('F jS, Y H:i', filemtime($cachefile_path)).' -->';
  exit;
}
// PICK HOOKS
if (PICK_HOOKS) {
  foreach ($hooks as $hook)
    require_once LOCAL_HOOKS.$hook;
} elseif ($dh = @opendir(LOCAL_HOOKS)) {
  while (($file = readdir($dh)) !== false) 
    if (substr($file, - 4) == '.php') require_once LOCAL_HOOKS.$file;
  closedir($dh);
}
// LOAD TEMPLATE
require_once LOCAL_TEMPLATE.THIS_PAGE;
//SAVE THE OUTPUT
if (INDENT) {
  if (CACHE && THIS_PAGE == 'page.php') file_put_contents($cachefile_path, indenter(ob_get_contents()));
} else {
  if (CACHE && THIS_PAGE == 'page.php') file_put_contents($cachefile_path, ob_get_contents());
}

/* */
if (DEBUG) print "\n".'<!-- Generated in '.(gettime() - $start_time).' seconds -->';
?>