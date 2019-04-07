<?php
require_once(dirname(__FILE__) . '/../database/conn.php');

$sql = "SELECT * FROM game GROUP BY UserID";
$rs = mysqli_query($conn, $sql);

$array = array();
	
while ($rc = mysqli_fetch_assoc($rs)) {
	$userID = $rc['UserID'];
	
	array_push($array, $userID);
}

echo json_encode(array("list" => $array));

?>