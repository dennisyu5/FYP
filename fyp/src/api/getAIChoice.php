<?php
require_once(dirname(__FILE__) . '/../model/Board.php');
require_once(dirname(__FILE__) . '/../database/conn.php');

if (isset($_GET['ai']) && isset($_GET['gameboardID']) && isset($_GET['id'])) {
	extract($_GET);
	
	$sql = "SELECT * FROM gameboard, board, game WHERE gameboard.ID = $gameboardID AND gameboard.BoardID = board.ID AND gameboard.GameID = game.ID LIMIT 1";
	$rs = mysqli_query($conn, $sql);
	if (mysqli_num_rows($rs) != 0) {
		$rc = mysqli_fetch_assoc($rs);
		$content = json_decode($rc['Content']);
		
		$sql = "SELECT * FROM ai WHERE ID = $ai LIMIT 1";
		$rs = mysqli_query($conn, $sql);
		$rc = mysqli_fetch_assoc($rs);
		$aiClassName = $rc['Description'];
		
		require_once(dirname(__FILE__) . "/../model/$aiClassName.php");
		$aiObject = "$aiClassName";
		$ai = new $aiObject();
		$ai->setID($id);
		
		$board = new Board();
		$board->setBoard($content);

		$array = $ai->move($board);
		$targetLocation = $array['bestLocation'];
		
		if ($targetLocation != null) {
			echo json_encode(array("location" => "$targetLocation", "gameboardID" => $gameboardID));
		} else {
			echo json_encode(array("location" => null, "gameboardID" => $gameboardID));
		}
	} else {
		echo "error";
	}
} else if (isset($_GET['gameboardID']) && isset($_GET['id'])) {
	extract($_GET);
	
	$sql = "SELECT * FROM gameboard, board, game, ai WHERE gameboard.ID = $gameboardID AND gameboard.BoardID = board.ID AND gameboard.GameID = game.ID AND game.AIID = ai.ID LIMIT 1";
	$rs = mysqli_query($conn, $sql);
	if (mysqli_num_rows($rs) != 0) {
		$rc = mysqli_fetch_assoc($rs);
		$content = json_decode($rc['Content']);
		$aiClassName = $rc['Description'];
		
		require_once(dirname(__FILE__) . "/../model/$aiClassName.php");
		$aiObject = "$aiClassName";
		$ai = new $aiObject();
		$ai->setID($id);
		
		$board = new Board();
		$board->setBoard($content);

		$array = $ai->move($board);
		$targetLocation = $array['bestLocation'];
		
		if ($targetLocation != null) {
			echo json_encode(array("location" => "$targetLocation", "gameboardID" => $gameboardID));
		} else {
			echo json_encode(array("location" => null, "gameboardID" => $gameboardID));
		}
	} else {
		echo "error";
	}
}
?>