<?php
require_once(dirname(__FILE__) . '/../model/Board.php');
require_once(dirname(__FILE__) . '/../database/conn.php');

if (isset($_GET['gameboardID'])){
	extract($_GET);
	
	$sql = "SELECT * FROM gameboard, board WHERE gameboard.ID = $gameboardID AND gameboard.BoardID = board.ID LIMIT 1";
	$rs = mysqli_query($conn, $sql);
	if (mysqli_num_rows($rs) != 0) {
		$rc = mysqli_fetch_assoc($rs);
		echo $rc['Content'];
	} else {
		echo "";
	}
}
?>