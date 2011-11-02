<?php // PEAKCLICK HOOK
if (!DEBUG) error_reporting(0);

function peakclick($keyword = THIS_PAGE_KEYWORD, $items = 5) {
  if (SHOW_ADS == true) {
    $peakclick = '';
    $ip = $_SERVER["REMOTE_ADDR"];
    $url = 'http://feed.peakclick.com/res.php?aff='.PEAKCLICK_AFF.'&subaff='.PEAKCLICK_SUBAFF.($keyword != '' ? '&keyword='.urlencode($keyword): '').'&num='.$items.'&ip='.$ip;
    if (PEAKCLICK_THUMBS == true) {
      $url .= '&thumbs=1';
      $lines = fetch($url);
      $lines = explode("\n", $lines);
      if (!substr_count(join('', $lines), 'ERROR:')) {
        if (count($lines)) {
          foreach($lines as $line_num => $line) {
            $result = explode('|', $line);
            $tur = explode('/', str_replace('https://', '', $result[3]));
            $targetUrlReal = $tur[0];
            $targetUrl = str_replace('https://', '', str_replace('http://', '', $result[4]));
            if ($targetUrl && $targetUrlReal && $result[2]) {
              $peakclick .= '<table>'."\n";
              $peakclick .= '<tr><td><a href="http://'.$targetUrl.'">'.$result[6].'</a></td>'."\n";
              $peakclick .= '<td>'."\n";
              $peakclick .= '<p><strong><a href="http://'.$targetUrl.'">'.$result[1].'</a></strong><br />'."\n";
              $peakclick .= $result[2].'<br>'."\n";
              $peakclick .= '<a href="http://'.$targetUrl.'">'.$targetUrlReal.'</a>'."\n";
              $peakclick .= '</td>'."\n";
              $peakclick .= '</tr>'."\n";
              $peakclick .= '</table>'."\n";
            }
          }
        } else {
          return printerror(PEAKCLICK_ERROR_1);
        }
      } else {
        return printerror(PEAKCLICK_ERROR_2);
      }
    } else {
      $lines = file($url);
      if (!substr_count(join('', $lines), 'ERROR:')) {
        if (count($lines)) {
          foreach($lines as $line) {
            $result = explode('|', $line);
            $tur = explode('/', str_replace('https://', '', $result[3]));
            $targetUrlReal = $tur[0];
            $targetUrl = str_replace('https://', '', str_replace('http://', '', $result[4]));
            if ($targetUrl && $targetUrlReal && $result[2]) {
              $peakclick .= '<p><strong><a href="http://'.$targetUrl.'">'.$result[1].'</a></strong><br />'."\n";
              $peakclick .= $result[2].'<br />';
              $peakclick .= '<a href="http://'.$targetUrl.'">'.$targetUrlReal.'</a></p>'."\n";
            }
          }
        } else {
          return printerror(PEAKCLICK_ERROR_1);
        }
      } else {
        return printerror(PEAKCLICK_ERROR_2);
      }
    }
    print $peakclick;
  }
}
?>