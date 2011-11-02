<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <? title() ?>
  <? metadescription() ?>
  <? feed() ?>
  <link rel="stylesheet" href="<? domain() ?>css/style.css" type="text/css" media="screen" />
</head>
<body>
  <div id="header">
    <div class="inside">
      <div id="search">
        <form method="get" id="sform" action="<? domain() ?>">
          <div class="searchimg"></div>
          <input type="text" id="q" value="" name="s" size="15" />
        </form>
      </div>

      <h2><a href="<? domain() ?>"><? sitename() ?></a></h2>
      <p class="description"><? description() ?></p>
      <br />
      <div style="text-align:center"><? adsense("728x90_as", '000000', '000000', 'FFFFFF', 'FFFFFF', 'BFBFBF') ?></div>
    </div>
  </div>
  <? 
  $firstkey = end($keyarr); 
  $secondkey = $keyarr[key($keyarr)-1]; 
  ?>
  <div id="primary" class="twocol-stories">
    <div class="inside">
      <div class="story first">
        <h3><a href="<?=k2url($firstkey) ?>" rel="bookmark" title="Permanent Link to <?=cut_cat($firstkey) ?>"><?=cut_cat($firstkey) ?></a></h3>
        <p><div style="float:left;margin:5px;"><? adsense("200x200_as", '000000', '000000', 'FFFFFF', 'FFFFFF', 'BFBFBF') ?></div> <? cache('wikipedia', array(cut_cat($firstkey)), cut_cat($firstkey)) ?></p>
        <div class="details">
          Posted at <?=date('ga \o\n j/m/y', ktime($firstkey, 'wikipedia'))?> | Filed Under: <a href="<?=c2url($firstkey) ?>" title="View all posts in <?=cut_key($firstkey) ?>" rel="category tag"><?=cut_key($firstkey) ?></a> <span class="read-on"><a href="<?=k2url($firstkey) ?>">read on</a></span>
        </div>
      </div>
      <div class="story">
        <h3><a href="<?=k2url($secondkey) ?>" rel="bookmark" title="Permanent Link to <?=cut_cat($secondkey) ?>"><?=cut_cat($secondkey) ?></a></h3>
        <p><div style="float:left;margin:5px;"><? adsense("200x200_as", '000000', '000000', 'FFFFFF', 'FFFFFF', 'BFBFBF') ?></div> <? cache('wikipedia', array(cut_cat($secondkey)), cut_cat($secondkey)) ?>
        </p>
        <div class="details">
          Posted at <?=date('ga \o\n j/m/y', ktime($secondkey, 'wikipedia'))?> | Filed Under: <a href="<?=c2url($secondkey) ?>" title="View all posts in <?=cut_key($secondkey) ?>" rel="category tag"><?=cut_key($secondkey) ?></a> <span class="read-on"><a href="<?=k2url($secondkey) ?>">read on</a></span>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
  
  <hr class="hide" />
  <div id="ancillary">
    <div class="inside">
      <div class="block first">
        <h2>About</h2>
        <p>This is the default YACG template. Use it for testing, learning, but please replace it with another one if you don't want to get banned.</p>
        <h2>Pages</h2>
        <ul class="counts">
        <? pages(false) ?>
        </ul>
      </div>

      <div class="block">
        <h2>Recently</h2>
        <ul class="dates">
          <? foreach(links(7, false, false, true) as $link): ?>
          <li><a href="<?=$link['url']?>"><span class="date"><?=date('j.m', $link['timestamp'])?></span> <?=cut_cat($link['key'])?> </a></li>
          <? endforeach; ?>
        </ul>
      </div>

      <div class="block">
        <h2>Categories</h2>
        <ul class="counts">
          <? categories(false) ?>
        </ul>
      </div>

      <div class="clear"></div>
    </div>
  </div>

  <hr class="hide" />
  <div id="footer">
    <div class="inside">
      <p class="copyright">Powered by <a href="http://warpspire.com/hemingway">Hemingway</a> flavored <a href="http://getyacg.com">YACG</a>.</p>
      <p class="attributes"><a href="feed:<? domain() ?>feed.xml">Entries RSS</a></p>
    </div>
  </div>
</body>
</html>