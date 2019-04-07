<?php

function getBoardString($content, $size) {
	$board = array();
	
	for ($i = 0; $i < $size; $i++) {
		for ($j = 0; $j < $size; $j++) {
			$board[$i][$j] = '💠';
		}
	}

	$blackLocation = $content->black;
	foreach ($blackLocation as $location) {
		list($y, $x) = str_split($location);
		$board[$x-1][$y-1] = '⚫';
	}
	
	$whiteLocation = $content->white;
	foreach ($whiteLocation as $location) {
		list($y, $x) = str_split($location);
		$board[$x-1][$y-1] = '⚪';
	}
	
	$s = "<br />";
	for ($i = 0; $i < $size; $i++) {
		
		if ($i == 0) {
			$s .= "🔻";
			for ($j = 0; $j < $size; $j++) {
				if ($j == 0) {
					$s .= chr(97+$j);
				} else {
					$s .= "　" . chr(97+$j);
				}
			}
			$s .= "🔻️<br />";
		}
			
		for ($j = 0; $j < $size; $j++) {
				
			if ($j == 0) {
				$s .= ($i+1) . " ";
			}
			
			if ($board[$i][$j] == '⚫' || $board[$i][$j] == '⚪') {
				$s .= $board[$i][$j] . " ";
			} else {
				$s .= $board[$i][$j];
			}
			
			if ($j == $size-1) {
				$s .= " " . ($i+1);
			}
		}
			
		$s .= "<br />";
			
		if ($i == $size-1) {
			$s .= "🔺️";
			for ($j = 0; $j < $size; $j++) {
				if ($j == 0) {
					$s .= chr(97+$j);
				} else {
					$s .= "　" . chr(97+$j);
				}
			}
			$s .= "🔺️<br />";
		}
	}
	
	return $s;
}

?>