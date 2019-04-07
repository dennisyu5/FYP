<?php
require_once(dirname(__FILE__) . '/../database/conn.php');

if (isset($_GET['userID'])) {
	extract($_GET);
	if (isset($_GET['gameID'])) {
		$sql = "SELECT * FROM game WHERE UserID = $userID AND ID = $gameID LIMIT 1";
	} else {
		$sql = "SELECT * FROM game WHERE UserID = $userID ORDER BY StartDate DESC LIMIT 10";
	}
	$rs = mysqli_query($conn, $sql);

	$array = array();
	
	while ($rc = mysqli_fetch_assoc($rs)) {
		$gameID = $rc['ID'];
		$startDate = $rc['StartDate'];
		
		array_push($array, array("gameID" => $gameID, 
								 "startDate" => $startDate));
	}

	echo json_encode(array("list" => $array));
}

?>