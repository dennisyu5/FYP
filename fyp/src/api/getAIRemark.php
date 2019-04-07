<?php
require_once(dirname(__FILE__) . '/../database/conn.php');

if (isset($_GET['ai'])) {
	extract($_GET);
	
	$sql = "SELECT Remark FROM ai WHERE ID = $ai";
	$rs = mysqli_query($conn, $sql);
	
	if(mysqli_num_rows($rs) > 0) {
		$rc = mysqli_fetch_assoc($rs);
		$remark = $rc['Remark'];
		echo json_encode(array("remark" => $remark));
	} else {
		echo json_encode(array("remark" => ""));
	}
}
?>