<?php

if (isset($_GET['number']) || isset($argv)){
	
	$number = 1;
	
	if (isset($_GET['number'])) {
		extract($_GET);
	} else {
		$number = $argv[1];
	}
	
	if (isset($argv[2])) {
		$ai = $argv[2];
	}
	
	$mode = 2;
	
	for ($i = 0; $i < $number; $i++) {
		if (!isset($argv[2])) {
			$ai = rand(1,4);
		}
		
		$startMemory = memory_get_usage();
		$start = microtime(true);
		$result = "";
		
		$json = json_decode(file_get_contents("http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/src/api/startGame.php?mode=$mode&ai=$ai&userID=$ai"));
		$gameboardID = $json->gameboardID;
		$turn = 1;
		
		$currentplayer = 1;
		while(!isEnd($gameboardID)) {
			if ($currentplayer == 1) {
				$json = json_decode(file_get_contents("http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/src/api/getAIChoice.php?ai=5&gameboardID=$gameboardID&id=$currentplayer"));
			} else {
				$json = json_decode(file_get_contents("http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/src/api/getAIChoice.php?ai=$ai&gameboardID=$gameboardID&id=$currentplayer"));
			}
			$location = $json->location;
			$gameboardID = $json->gameboardID;
			
			if ($location != null) {
				list($x, $y) = str_split($location);
				//echo "[" . $x . $y . "]";
				
				$json = json_decode(file_get_contents("http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/src/api/move.php?gameboardID=$gameboardID&nextX=$x&nextY=$y&player=$currentplayer&turn=$turn"));
				$gameboardID = $json->gameboardID;
			}
			
			$currentplayer = $currentplayer%2+1;
			
			//if back to the first ai, the turn will be added 1
    	    if ($currentplayer == 1) {
    	    	$turn += 1;
    		}
		}
		$json = json_decode(file_get_contents("http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/src/api/getCurrentBoard.php?gameboardID=$gameboardID"));
		
		$numOfBlack = count($json->black);
		$numOfWhite = count($json->white);

		if ($numOfBlack == $numOfWhite) {
			$winner = 3;
		} else if ($numOfBlack > $numOfWhite) {
			$winner = 1;
		} else {
			$winner = 2;
		}
		
		$json = json_decode(file_get_contents("http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/src/api/updateRecord.php?gameboardID=$gameboardID&winner=$winner"));

		if ($json->status == 1) {
			$time = microtime(true) - $start;
			$memory = memory_get_usage() - $startMemory;
			
			$result .= "Success [" . ($i+1) . "]\t";
			$result .= '[Time]: ' . $time . "s\n";
			//$result .= '[Memory]: ' . $memory . " bytes\n";
			
			echo $result;
		}
		
		sleep(1);
	}
	
	if (isset($_GET['number'])) {
		header("Location: http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/web/ai.php");
	}
}

function isEnd($gameboardID) {
	$list1 = getListOfValidLocation($gameboardID, 1);
	$list2 = getListOfValidLocation($gameboardID, 2);
	return count($list1->list[0]->choices) == 0 && count($list2->list[0]->choices) == 0;
}

function getListOfValidLocation($gameboardID, $player) {
	return json_decode(file_get_contents("http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/src/api/getListOfValidLocation.php?gameboardID=$gameboardID&player=$player"));
}
?>