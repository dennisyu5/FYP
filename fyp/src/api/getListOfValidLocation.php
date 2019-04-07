<?php
require_once(dirname(__FILE__) . '/../model/Board.php');
require_once(dirname(__FILE__) . '/../database/conn.php');

if (isset($_GET['numOfRecords'])) {
	extract($_GET);
} else {
	$numOfRecords = 200;
}

$sql = "";

if (isset($_GET['gameboardID']) && isset($_GET['player'])) {
	extract($_GET);
	$sql = "SELECT * FROM gameboard, board WHERE gameboard.BoardID = board.ID AND gameboard.ID = $gameboardID LIMIT 1";
} else {
	$sql = "SELECT * FROM gameboard, board WHERE gameboard.BoardID = board.ID GROUP BY board.ID ORDER BY LENGTH(Content) LIMIT $numOfRecords";
}	
	
$rs = mysqli_query($conn, $sql);

$list = array();
while ($rc = mysqli_fetch_assoc($rs)) {
	
	if (isset($player) || $rc['Mover'] != null) {
		$content = json_decode($rc['Content']);
		if (!isset($player)) {
			$player = $rc['Mover'];
		}
		$mover = ($player == 1)? "Black" : "White";
			
		$board = new Board();
		$board->setBoard($content);
		
		$validLocationlist = $board->getListOfValidLocation($player);
		
		$array = array();
		foreach($validLocationlist as $location) {
			array_push($array, array("x" => $location[0],
									 "y" => $location[1]));
		}
		
		array_push($list, array("content" => $rc['Content'],
								"mover" => $mover,
								"choices" => $array));
	}
}

echo json_encode(array("list" => $list));
?>