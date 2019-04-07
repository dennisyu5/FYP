<?php
require_once(dirname(__FILE__) . '/AI.php');

class RandomAI extends AI {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function move($board) {
        $listOfValidLocation = $board->getListOfValidLocation($this->id);
		if (count($listOfValidLocation) >= 1) {
			$randomNumber = rand(0,count($listOfValidLocation)-1);
			list($x, $y) = str_split($listOfValidLocation[$randomNumber]);
			
			$updatedList = $board->move($x, $y, $this->id);
			return array("bestLocation" => $x . $y,
						 "updatedList" => $updatedList);
		} else {
			return array("bestLocation" => null,
						 "updatedList" => null);
		}
    }
}
?>