<?php
require_once(dirname(__FILE__) . '/AI.php');

class ExpertAI extends AI {
	
	public function __construct() {
		parent::__construct();
	}

	public function move($board) {
		require(dirname(__FILE__) . '/../database/conn.php');
		
		$content = str_replace("\"", "\\\"", $this->getContent($board));
		$sql = "SELECT * FROM record, board WHERE record.BoardID = board.ID AND board.Content = '$content' AND record.Mover = " . $this->id;
		$rs = mysqli_query($conn, $sql);

		if (mysqli_num_rows($rs) != 0 && $this->id == 2) {
			$maxPercentage = 0;
			$targetX = null;
			$targetY = null;
			
			while ($rc = mysqli_fetch_assoc($rs)) {
				if ($rc['NextX'] != null && $rc['NextY'] != null) {
					if ($rc['WinCount']/$rc['TotalCount'] >= $maxPercentage) {
						$maxPercentage = $rc['WinCount']/$rc['TotalCount'];
						$targetX = $rc['NextX'];
						$targetY = $rc['NextY'];
					}
				}
			}
			
			$updatedList = $board->move($targetX, $targetY, $this->id);
			return array("bestLocation" => $targetX . $targetY,
						 "updatedList" => $updatedList);
		} else {
			require_once(dirname(__FILE__) . '/MediumAI.php');
			$ai = new MediumAI();
			$ai->setID($this->id);
			return $ai->move($board);
		}
    }
	
	public function getContent($board) {
		$blackChessList = $board->getChessList(1);
		$whiteChessList = $board->getChessList(2);
		
		return json_encode(array("black" => $blackChessList, 
								 "white" => $whiteChessList));
	}
}
?>