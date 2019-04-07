<?php
require_once(dirname(__FILE__) . '/../database/conn.php');

if ($_GET['remark'] != "" && isset($_GET['ai'])) {
	extract($_GET);
	
	$sql = "UPDATE ai SET Remark = $remark WHERE ID = $ai";
	mysqli_query($conn, $sql) or die(mysqli_error($conn));
	
	if(mysqli_affected_rows($conn) > 0) {
		header("Location: http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/web/settings.php?status=1"); //successful
	} else {
		header("Location: http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/web/settings.php?status=0"); //failed
	}
}
?>