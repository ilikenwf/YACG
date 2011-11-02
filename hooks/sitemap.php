<?php // SITEMAP HOOK
function sitemap($return = false) {
	$sitemap = '';
	$sitemap = '<a href="##" title="#">#</a> <a href="#A" title="A">A</a> <a href="#B" title="B">B</a> <a href="#C" title="C">C</a> <a href="#D" title="D">D</a> <a href="#E" title="E">E</a> <a href="#F" title="F">F</a> <a href="#G" title="G">G</a> <a href="#H" title="H">H</a> <a href="#I" title="I">I</a> <a href="#J" title="J">J</a> <a href="#K" title="K">K</a> <a href="#L" title="L">L</a> <a href="#M" title="M">M</a> <a href="#N" title="N">N</a> <a href="#O" title="O">O</a> <a href="#P" title="P">P</a> <a href="#Q" title="Q">Q</a> <a href="#R" title="R">R</a> <a href="#S" title="S">S</a> <a href="#T" title="T">T</a> <a href="#U" title="U">U</a> <a href="#V" title="V">V</a> <a href="#W" title="W">W</a> <a href="#X" title="X">X</a> <a href="#Y" title="Y">Y</a> <a href="#Z" title="Z">Z</a><br />';
	$keyword_file = file(FILE_KEYWORDS);
	$keyword_file = array_map('trim', $keyword_file);
	$keyword_file = str_replace(" ", "-", $keyword_file);
	asort($keyword_file);
	$letters = "\d A B C D E F G H I J K L M N O P Q R S T U V W X Y Z";
	$letters = explode(" ", $letters);
	foreach($letters as $letter) {
		$links[$letter] = '';
		foreach($keyword_file as $keyword) {
			if (preg_match("/^$letter/i", $keyword)) {
				$links[$letter] .= $keyword." ";
			}
		}
		$links[$letter] = rtrim($links[$letter]);
		if ($links[$letter] !== '') {
			$links2 = explode(" ", $links[$letter]);
			$letter = str_replace("\d","#",$letter);
			$sitemap .= "\n"."<a name=\"".$letter."\" id=\"".$letter."\"></a>";
			$sitemap .= "\n"."<h1>".$letter."</h1>";
			foreach($links2 as $keyword) {
				$keyword = str_replace("-", " ", $keyword);
				$sitemap .= "\n".'<a href="http://'.THIS_DOMAIN.'/'.str_replace(" ", "-", $keyword).'" title="'.$keyword.'">'.$keyword.'</a><br />';
			}
		}
	}
	$sitemap .= "\n";
	if ($return !== true) {
		print $sitemap;
	}
	else {
		return $sitemap;
	}
}
?>