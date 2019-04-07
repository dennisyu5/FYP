<?php
require_once(dirname(__FILE__) . '/AI.php');
require_once(dirname(__FILE__) . '/Evaluator.php');

class MediumAI extends AI {
	
	public function __construct() {
		parent::__construct();
		$this->evaluator = new Evaluator();
		$this->MAP = $this->evaluator->getMAP();
	}
	
	public function move($board) {
		$power = 0;
		$powerOpponent = 0;
		
        $listOfValidLocation = $board->getListOfValidLocation($this->id);
		$locationObjects = array();
		
		foreach ($listOfValidLocation as $location) {
			$boardCopy = clone $board;
			list($x, $y) = str_split($location);
			$power = $this->MAP[$y][$x];
			
			if (!$boardCopy->isValidMove($x, $y, $this->id)) continue;
			
			$locationListOfReverseChess = $boardCopy->getTotalNumberOfReverseChessOfOpponent($x, $y, $this->id);
			
			foreach ($locationListOfReverseChess as $locationOfReverseChess) {
				list($x1, $y1) = str_split($locationOfReverseChess);
				$power += $this->MAP[$y1][$x1];
			}
			
			$boardCopy->move($x, $y, $this->id);
			
			if ($boardCopy->isCorner($x, $y, $this->evaluator->size)) {
				$updatedList = $board->move($x, $y, $this->id);
				
				return array("bestLocation" => $x . $y,
							 "updatedList" => $updatedList);
			}
			
			$opponentID = 1;
			$listOfValidLocationForOpponent = $boardCopy->getListOfValidLocation($opponentID);
			
			foreach ($listOfValidLocationForOpponent as $locationForOpponent) {
				list($x2, $y2) = str_split($locationForOpponent);
				$powerOpponent = $this->MAP[$y2][$x2];
				
				if (!$boardCopy->isValidMove($x2, $y2, $opponentID)) continue;
				
				$locationListOfReverseChessForOpponent = $boardCopy->getTotalNumberOfReverseChessOfOpponent($x2, $y2, $opponentID);
			
				foreach ($locationListOfReverseChessForOpponent as $locationOfReverseChessForOpponent) {
					list($x3, $y3) = str_split($locationOfReverseChessForOpponent);
					$powerOpponent += $this->MAP[$y3][$x3];
				}
			}
			
			$locationObject = array("x" => $x, "y" => $y, "power" => $power - $powerOpponent);
			array_push($locationObjects, $locationObject);
		}
		
		array_multisort (array_column($locationObjects, 'power'), SORT_DESC, $locationObjects);
		
		if (count($locationObjects) != 0) {
			$locationObject = $locationObjects[0];
			$targetX = $locationObject['x'];
			$targetY = $locationObject['y'];
		} else {
			$targetX = null;
			$targetY = null;
		}
		
		if ($targetX != null && $targetY != null) {
			$updatedList = $board->move($targetX, $targetY, $this->id);
			
			return array("bestLocation" => $targetX . $targetY,
						 "updatedList" => $updatedList);
		} else {
			require_once(dirname(__FILE__) . '/RandomAI.php');
			$ai = new RandomAI();
			$ai->setID($this->id);
			return $ai->move($board);
		}
    }
}
?>