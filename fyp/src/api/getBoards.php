<?php
require_once(dirname(__FILE__) . '/../database/conn.php');

if (isset($_GET['numOfRecords'])) {
	extract($_GET);
} else {
	$numOfRecords = 50;
}
	
$sql = "SELECT * FROM board ORDER BY LENGTH(Content) LIMIT $numOfRecords";
$rs = mysqli_query($conn, $sql);

$array = array();
	
while ($rc = mysqli_fetch_assoc($rs)) {
	$id = $rc['ID'];
	$content = $rc['Content'];
	
	$sql = "SELECT * FROM board, record WHERE record.BoardID = board.ID AND board.ID = $id";
	$rs1 = mysqli_query($conn, $sql);
	
	$choices = array();
	
	while ($rc1 = mysqli_fetch_assoc($rs1)) {
		$nextX = $rc1['NextX'];
		$nextY = $rc1['NextY'];
		$mover = $rc1['Mover'];
		$winCount = $rc1['WinCount'];
		$totalCount = $rc1['TotalCount'];
		
		array_push($choices, array("nextX" => $nextX,
								   "nextY" => $nextY,
								   "winCount" => $winCount,
								   "totalCount" => $totalCount));
	}
	
	array_push($array, array("id" => $id,
							 "content" => $content,
							 "mover" => $mover,
							 "choices" => $choices));
}

echo json_encode(array("list" => $array));
?>