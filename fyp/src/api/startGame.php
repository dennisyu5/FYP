<?php
require_once(dirname(__FILE__) . '/../model/Board.php');
require_once(dirname(__FILE__) . '/../database/conn.php');

if (isset($_GET['userID']) and isset($_GET['mode']) and isset($_GET['ai'])){
	extract($_GET);
	$turn = 1;
	$size = 8;
	$defaultContent = "{\"black\":[\"" . ($size/2) . ($size/2) . "\",\"" . ($size/2+1) . ($size/2+1) . "\"],\"white\":[\"" . ($size/2+1) . ($size/2) . "\",\"" . ($size/2) . ($size/2+1) . "\"]}";

	$sql = "INSERT INTO game (UserID, StartDate, ModeID, AIID) VALUES ($userID, '" . date('Y-m-d H:i') . "', $mode, $ai)";
	if (mysqli_query($conn, $sql)) {
		$gameID = $conn->insert_id;
	} else {
		die(mysqli_error($conn));
	}
	
	$sql = "SELECT * FROM board WHERE Content = '$defaultContent' LIMIT 1";
	$rs = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($rs) == 0) {
		$sql = "INSERT INTO board (Content) VALUES ('$defaultContent')";
		if (mysqli_query($conn, $sql)) {
			$boardID = $conn->insert_id;
		} else {
			die(mysqli_error($conn));
		}
	} else {
		$rc = mysqli_fetch_assoc($rs);
		$boardID = $rc['ID'];
	}
	
	$sql = "INSERT INTO gameboard (GameID, BoardID, Turn) VALUES ($gameID, $boardID, $turn)";
	if (mysqli_query($conn, $sql)) {
		$gameboardID = $conn->insert_id;
	} else {
		die(mysqli_error($conn));
	}
	
	echo json_encode(array('gameboardID' => $gameboardID));
}
?>