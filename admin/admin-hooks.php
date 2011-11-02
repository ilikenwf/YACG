<?php //ADMIN HOOKS

function fetch_admin($url){
	require_once('snoopy.class.php');
	$snoop=new Snoopy;
	$snoop->agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20061010 Firefox/2.0";
	$snoop->fetch($url);
	return $snoop->results;
	}
	
function markov_admin($gran='5',$num='200'){
	if (is_dir("../".LOCAL_ARTICLES."")){
		if ($dh = opendir("../".LOCAL_ARTICLES."")){
      			while (($file = readdir($dh)) !== false){
				if($file == "." || $file == ".." || empty($file)){$my_dump[] = $file;}
       	    			elseif(substr($file, -4) == '.txt'){
			 	$combo .= file_get_contents("../".LOCAL_ARTICLES."".$file);
       	   			}
       	     		if($i >= $nr_files){$i = 0;} elseif($i < $nr_files){
        			++$i; }
       				}
       			closedir($dh);
   				}
	}
	$combo = utf8_encode($combo);
	$combo = htmlentities($combo);
	$combo = preg_replace('/\s\s+/', ' ', $combo);
	$combo = preg_replace('/\n|\r/', '', $combo);
	$G = $gran;
	$O = $num;
	$output ="";
	$combo = $combo;
	$LETTERS_LINE = 65;
	$textwords = array();
	$textwords = explode(" ", $combo);
	$loopmax = count($textwords) - ($G - 2) - 1;
	$frequency_table = array();
	for ($j = 0; $j < $loopmax; $j++){
		$key_string = "";
		$end = $j + $G;
		for ($k = $j; $k < $end; $k++){
			$key_string .= $textwords[$k] . ' ';
			}
		$frequency_table[$key_string] .= $textwords[$j + $G] . " ";
		}
	$buffer="";
	$lastwords = array();
	for ($i = 0; $i < $G; $i ++){
		$lastwords[] = $textwords[$i];
		$buffer .= " " . $textwords[$i];
		}
	for ($i = 0; $i < $O; $i++){
		$key_string = "";   
		for ($j = 0; $j < $G; $j++){
			$key_string .= $lastwords[$j] . " ";
			}
	if ($frequency_table[$key_string]){
		$possible = explode(" ", trim($frequency_table[$key_string]));
		mt_srand();
		$c = count($possible);
		$r = mt_rand(1, $c) -1;
		$nextword = $possible[$r];
		$buffer .= " $nextword";
		if(strlen($buffer) >= $LETTERS_LINE){
			$output .= $buffer;
			$buffer = "";
			}
		for($l = 0; $l < $G - 1; $l++){ 
			$lastwords[$l] = $lastwords[$l+1]; 
			}
		$lastwords[$G - 1] = $nextword;
		}
	else{    
		$lastwords = array_splice($lastwords, 0, count($lastwords));
		for ($l = 0; $l < $G; $l++){
			$lastwords[] = $textwords[$l]; 
			$buffer .= ' ' . $textwords[$l];
			}
		}
	}

	return trim($output);
	}
?>