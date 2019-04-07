<?php
require_once(dirname(__FILE__) . '/AI.php');

class EasyAI extends AI {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function move($board) {
        $listOfValidLocation = $board->getListOfValidLocation($this->id);
        $bestLocation = null;
		$maxNumberOfReverseChess = 0;
		foreach ($listOfValidLocation as $location) {
			list($x, $y) = str_split($location);
			$numberOfReverseChess = count($board->getTotalNumberOfReverseChessOfOpponent($x, $y, $this->id));
			if ($numberOfReverseChess > $maxNumberOfReverseChess) {
				$bestLocation = $location;
				$maxNumberOfReverseChess = $numberOfReverseChess;
			}
		}
		
		if ($bestLocation != null) {
			list($x, $y) = str_split($bestLocation);
			$updatedList = $board->move($x, $y, $this->id);
			
			return array("bestLocation" => $bestLocation,
						 "updatedList" => $updatedList);
		} else {
			return array("bestLocation" => null,
						 "updatedList" => null);
		}
    }
}
?>