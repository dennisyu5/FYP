<?php
require_once(dirname(__FILE__) . '/../model/Board.php');
require_once(dirname(__FILE__) . '/../database/conn.php');

if (isset($_GET['boardID'])) {
	extract($_GET);
	
	$sql = "SELECT * FROM gameboard, board WHERE gameboard.BoardID = board.ID AND board.ID = $boardID GROUP BY board.ID ORDER BY LENGTH(Content)";
	
	$rs = mysqli_query($conn, $sql);

	$list = array();
	while ($rc = mysqli_fetch_assoc($rs)) {
		
		if ($rc['Mover'] != null) {
			$content = json_decode($rc['Content']);
			$player = $rc['Mover'];
			
			$mover = ($player == 1)? "Black" : "White";
				
			$board = new Board();
			$board->setBoard($content);
			
			$validLocationlist = $board->getListOfValidLocation($player);
			
			$array = array();
			foreach($validLocationlist as $location) {
				$boardCopy = clone $board;
				$contentCopy = clone $content;
				$updatedList = $boardCopy->move($location[0], $location[1], $player);
				
				if ($player == 1)
					array_push($contentCopy->black, $location[0] . $location[1]);
				else
					array_push($contentCopy->white, $location[0] . $location[1]);
				
				foreach ($updatedList as $updatedLocation) {
					if ($player == 1) {
						array_splice($contentCopy->white, array_search($updatedLocation, $contentCopy->white), 1);
						array_push($contentCopy->black, $updatedLocation);
					} else {
						array_splice($contentCopy->black, array_search($updatedLocation, $contentCopy->black), 1);
						array_push($contentCopy->white, $updatedLocation);
					}
				}
				$boardCopy = new Board();
				$boardCopy->setBoard($contentCopy);
				
				$blackChessList = $boardCopy->getChessList(1);
				$whiteChessList = $boardCopy->getChessList(2);
				
				$contentCopy = json_encode(array("black" => $blackChessList, 
												 "white" => $whiteChessList));

				//get board
				$sql = "SELECT * FROM board WHERE Content = '$contentCopy' LIMIT 1";
				$rs = mysqli_query($conn, $sql);
				$rc1 = mysqli_fetch_assoc($rs);
				$id = $rc1['ID'];
		
				array_push($array, array("boardID" => $id,
										 "content" => $contentCopy,
										 "x" => $location[0],
										 "y" => $location[1]));
			}
			
			array_push($list, array("boardID" => $boardID,
									"content" => $rc['Content'],
									"mover" => $mover,
									"choices" => $array));
		}
	}

	echo json_encode(array("list" => $list));
}
?>