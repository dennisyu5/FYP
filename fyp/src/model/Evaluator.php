<?php
class Evaluator {
	
	public function __construct() {
		$this->size = 8;
		if ($this->size == 6) {
			$this->MAP = array(
							array(-500, -500, -500, -500, -500, -500, -500, -500),
							array(-500, 200, -100, 100, 100, -100, 200, -500), //a
							array(-500, -100, -200, -50, -50, -200, -100, -500), //b
							array(-500, 100, -50, 0, 0, -50, 100, -500), //c
							array(-500, 100, -50, 0, 0, -50, 100, -500), //d
							array(-500, -100, -200, -50, -50, -200, -100, -500), //e
							array(-500, 200, -100, 100, 100, -100, 200, -500), //f
							array(-500, -500, -500, -500, -500, -500, -500, -500)
						 );
		} else if ($this->size == 8) {
			$this->MAP = array(
							array(-500, -500, -500, -500, -500, -500, -500, -500, -500, -500),
							array(-500, 200, -100, 100, 50, 50, 100, -100, 200, -500), //a
							array(-500, -100, -200, -50, -50, -50, -50, -200, -100, -500), //b
							array(-500, 100, -50, 100, 0, 0, 100, -50, 100, -500), //c
							array(-500, 50, -50, 0, 0, 0, 0, -50, 50, -500), //d
							array(-500, 50, -50, 0, 0, 0, 0, -50, 50, -500), //e
							array(-500, 100, -50, 100, 0, 0, 100, -50, 100, -500), //f
							array(-500, -100, -200, -50, -50, -50, -50, -200, -100, -500), //g
							array(-500, 200, -100, 100, 50, 50, 100, -100, 200, -500), //h
							array(-500, -500, -500, -500, -500, -500, -500, -500, -500, -500)
						 );
		}
	}
	
	public function getMap() {
		return $this->MAP;
	}
	
	public function getScore($board, $player) {
		$scoreOfMobility = $this->getMobility($board, $player);
		$scoreOfDiscDiff = $this->getDiscDiff($board, $player);
		$scoreOfCorner = $this->getCorner($board, $player);
		$scoreOfPlaceCorner = $this->getPlaceCornerScore($board, $player);
		$scoreOfPlace = $this->getPlaceScore($board, $player);
		return 2 * $scoreOfMobility + $scoreOfDiscDiff + 100 * $scoreOfCorner - 200 * $scoreOfPlaceCorner + 10 * $scoreOfPlace;
	}
	
	public function getMobility($board, $player) {
		$opponent = $player%2+1;
		$myMoveCount = count($board->getListOfValidLocation($player));
		$opponentMoveCount = count($board->getListOfValidLocation($opponent));
		return 100 * ($myMoveCount - $opponentMoveCount) / ($myMoveCount + $opponentMoveCount + 1);
	}
	
	public function getDiscDiff($board, $player) {
		$opponent = $player%2+1;
		$myChessCount = count($board->getChessList($player));
		$opponentChessCount = count($board->getChessList($opponent));
		return 100 * ($myChessCount - $opponentChessCount) / ($myChessCount + $opponentChessCount);
	}
	
	public function getCorner($board, $player) {
		$opponent = $player%2+1;
		$myCorners = $board->getCornerCount($player);
        $opponentCorners = $board->getCornerCount($opponent);
		return 100 * ($myCorners - $opponentCorners) / ($myCorners + $opponentCorners + 1);
	}
	
	public function getPlaceCornerScore($board, $player) {
		$opponent = $player%2+1;
		$listOfValidLocation = $board->getListOfValidLocation($opponent);
		$opponentNumberOfCorner = 0;
		foreach ($listOfValidLocation as $location) {
			list($x, $y) = str_split($location);
			if ($board->isCorner($x, $y, $this->size)) {
				$opponentNumberOfCorner++;
			}
		}
		return 100 * $opponentNumberOfCorner;
	}
	
	public function getPlaceScore($board, $player) {
		$opponent = $player%2+1;
		$score = 0;
		
		$listOfValidLocation = $board->getChessList($player);
		foreach ($listOfValidLocation as $location) {
			list($x, $y) = str_split($location);
			$score += $this->MAP[$y][$x];
		}
		
		$listOfValidLocation = $board->getChessList($opponent);
		foreach ($listOfValidLocation as $location) {
			list($x, $y) = str_split($location);
			$score -= $this->MAP[$y][$x];
		}
		
		return $score;
	}
}
?>