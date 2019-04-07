<?php
require_once(dirname(__FILE__) . '/../database/conn.php');

if (isset($_GET['userID'])) {
	extract($_GET);
	
	if (isset($_GET['gameID'])) {
		$sql = "SELECT * FROM game WHERE UserID = $userID";
	} else {
		$sql = "SELECT * FROM game WHERE UserID = $userID LIMIT 1";
	}
	$rs = mysqli_query($conn, $sql);
	
	if (isset($_GET['gameID'])) {
		$sql = "SELECT * FROM gameboard, board, game WHERE game.UserID = $userID AND game.ID = $gameID AND gameboard.BoardID = board.ID AND gameboard.GameID = game.ID ORDER BY gameboard.ID";
	} else {
		while ($rc = mysqli_fetch_assoc($rs)) {
			$sql = "SELECT * FROM gameboard, board, game WHERE game.UserID = $userID AND game.ID = " . $rc["ID"] . " AND gameboard.BoardID = board.ID AND gameboard.GameID = game.ID ORDER BY gameboard.ID";
		}
	}
	$rs = mysqli_query($conn, $sql);
	
	$array = array();
	
	while ($rc = mysqli_fetch_assoc($rs)) {
		$content = $rc['Content'];
		$nextX = $rc['NextX'];
		$nextY = $rc['NextY'];
		$mover = $rc['Mover'];
		array_push($array, array("content" => $content,
								 "nextX" => $nextX,
								 "nextY" => $nextY,
								 "mover" => $mover));
	}
	
	echo json_encode(array("list" => $array));
}

?>