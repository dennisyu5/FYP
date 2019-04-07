<?php
require_once(dirname(__FILE__) . '/../database/conn.php');

if (isset($_GET['gameboardID']) and isset($_GET['winner'])) {
	extract($_GET);
	
	$sql = "SELECT * FROM gameboard, game WHERE gameboard.GameID = game.ID AND gameboard.ID = $gameboardID LIMIT 1";
	$rs = mysqli_query($conn, $sql);
	if (mysqli_num_rows($rs) != 0) {
		$rc = mysqli_fetch_assoc($rs);
		$gameID = $rc['GameID'];
		
		$sql = "UPDATE game SET Winner = $winner WHERE ID = $gameID";
		mysqli_query($conn, $sql) or die(mysqli_error($conn));
		
		//keep and update ai move records
		$sql = "SELECT * FROM gameboard, game WHERE gameboard.GameID = game.ID AND game.ID = $gameID AND gameboard.Mover = $winner";
		$rs = mysqli_query($conn, $sql);
		
		while ($rc = mysqli_fetch_assoc($rs)) {
			$boardID = $rc['BoardID'];
			$nextX = $rc['NextX'];
			$nextY = $rc['NextY'];
			$mover = $rc['Mover'];
			
			if ($nextX != null && $nextY != null && $mover != null) {
				$sql = "UPDATE record SET WinCount = WinCount+1 WHERE BoardID = $boardID AND NextX = $nextX AND NextY = $nextY AND Mover = $mover";
				mysqli_query($conn, $sql) or die(mysqli_error($conn));
			}
		}
		echo json_encode(array("status" => 1));
	} else {
		echo json_encode(array("status" => 0));
	}
}
?>