<?php
require_once(dirname(__FILE__) . '/../database/conn.php');

$sql = "SELECT * FROM record";
$rs = mysqli_query($conn, $sql);
$array = array();

while ($rc = mysqli_fetch_assoc($rs)) {
	array_push($array, array("boardID" => $rc['BoardID'],
							 "nextX" => $rc['NextX'],
							 "nextY" => $rc['NextY'],
							 "mover" => $rc['Mover'],
							 "winCount" => $rc['WinCount'],
							 "totalCount" => $rc['TotalCount']));
}

echo json_encode(array("list" => $array));
?>