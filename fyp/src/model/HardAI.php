<?php
require_once(dirname(__FILE__) . '/AI.php');
require_once(dirname(__FILE__) . '/Evaluator.php');

class HardAI extends AI {
	
	public function __construct() {
		parent::__construct();
		require(dirname(__FILE__) . '/../database/conn.php');
		
		$sql = "SELECT * FROM ai WHERE ID = 3";
		$rs = mysqli_query($conn, $sql);
		$rc = mysqli_fetch_assoc($rs);
		$this->depth = $rc["Remark"];
		$this->evaluator = new Evaluator();
	}
	
	public function move($board) {
		$bestScore = -99999;
		$bestMove = null;
		
		$listOfValidLocation = $board->getListOfValidLocation($this->id);
		
		foreach ($listOfValidLocation as $location) {
			$boardCopy = clone $board;
			list($x, $y) = str_split($location);
			$updatedList = $boardCopy->move($x, $y, $this->id);
			
			$childScore = $this->getMinimaxValue($boardCopy, $this->id, $this->depth, false, -9999, 9999, $this->evaluator);
			if ($childScore > $bestScore) {
				$bestScore = $childScore;
				$bestMove = $location;
			}
		}
		
		if ($bestMove != null) {
			list($x, $y) = str_split($bestMove);
			$updatedList = $board->move($x, $y, $this->id);
			
			return array("bestLocation" => $bestMove,
						 "updatedList" => $updatedList);
		} else {
			require_once(dirname(__FILE__) . '/RandomAI.php');
			$ai = new RandomAI();
			$ai->setID($this->id);
			return $ai->move($board);
		}
    }
	
	public function getMinimaxValue($board, $player, $depth, $isMax, $min, $max, $evaluator) {
		if ($depth == 0 || $board->isEnd()) {
			return $evaluator->getScore($board, $player);
		}
		$opponent = $player%2+1;
		if (($isMax && !$board->canMove($player)) || (!$isMax && !$board->canMove($opponent))) {
			return $this->getMinimaxValue($board, $player, $depth-1, !$isMax, $min, $max, $evaluator);
		}
		
		$score = 0;
		if ($isMax) {
			$score = -9999;
			
			$listOfValidLocation = $board->getListOfValidLocation($player);
		
			foreach ($listOfValidLocation as $location) {
				$boardCopy = clone $board;
				list($x, $y) = str_split($location);
				$updatedList = $boardCopy->move($x, $y, $player);
				
				$childScore = $this->getMinimaxValue($boardCopy, $player, $depth-1, false, $min, $max, $evaluator);
				if ($childScore > $score) {
					$score = $childScore;
				}
				
				if ($score > $min) $min = $score;
				if ($max <= $min) break;
			}
		} else {
			$score = 9999;
			
			$listOfValidLocation = $board->getListOfValidLocation($opponent);
		
			foreach ($listOfValidLocation as $location) {
				$boardCopy = clone $board;
				list($x, $y) = str_split($location);
				$updatedList = $boardCopy->move($x, $y, $opponent);
				
				$childScore = $this->getMinimaxValue($boardCopy, $player, $depth-1, true, $min, $max, $evaluator);
				if ($childScore < $score) {
					$score = $childScore;
				}
				
				if ($score < $max) $max = $score;
				if ($max <= $min) break;
			}
		}
		return $score;
	}
}
?>