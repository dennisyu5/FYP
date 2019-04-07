<?php
require_once(dirname(__FILE__) . '/../database/conn.php');

$sql = "SELECT ID FROM board ORDER BY ID DESC LIMIT 1";
$rs = mysqli_query($conn, $sql);

if(mysqli_num_rows($rs) > 0) {
	$rc = mysqli_fetch_assoc($rs);
	$id = $rc['ID'];
	echo json_encode(array("id" => $id));
} else {
	echo json_encode(array("id" => null));
}
?>