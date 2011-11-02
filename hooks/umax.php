<?php
function umax($keyword=THIS_PAGE_KEYWORD, $items=5) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $language=isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? rawurlencode($_SERVER['HTTP_ACCEPT_LANGUAGE']) : ''; 
        $ref = rawurlencode('http://'.$_SERVER['HTTP_HOST']).'/';
$url = ' http://xml.umaxfeed.com/xmlfeed.php?aid='._AFF_UMAX.'&qr='.$items.'&said='._SUBAFF_UMAX.'&ip='._IP.'&q='.urlencode($keyword).'&ref='.$ref.'&l='.$language.'&grw=0&qpw=0&t=txt ';

           $feed = fetch($url); 
           
                while($data = fgetcsv($feed, 3000, "|")){ 
       if(!$data[1] == 0 ){                       
        echo '<p><b><a href="'.$data[4].'">'.$data[1].'</a></b><br>'; 
        echo $data[2].'<br>';
        echo '<a href="'.$data[4].'">'.$data[3].'</a>'."\n";
     }
  }
}
?>