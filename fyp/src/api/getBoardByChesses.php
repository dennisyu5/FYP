<?php
require_once(dirname(__FILE__) . '/../database/conn.php');

if (isset($_GET['blackChesses']) || isset($_GET['whiteChesses'])) {
	
	$blackChesses = array();
	$whiteChesses = array();
	
	if (isset($_GET['blackChesses'])) $blackChesses = explode("-", $_GET['blackChesses']);
	if (isset($_GET['whiteChesses'])) $whiteChesses = explode("-", $_GET['whiteChesses']);
	
	$whereClause = "";

	for ($i = 0; $i < count($blackChesses); $i++) {
		if ($i != 0) {
			$whereClause .= " AND";
		}
		$whereClause .= " SUBSTRING(Content, POSITION('black' IN Content)-1, POSITION('white' IN Content)-1) LIKE '%" . $blackChesses[$i] . "%'";
	}
	
	for ($i = 0; $i < count($whiteChesses); $i++) {
		if (count($blackChesses) != 0 || $i != 0) {
			$whereClause .= " AND";
		}
		$whereClause .= " SUBSTRING(Content, POSITION('white' IN Content)-1) LIKE '%" . $whiteChesses[$i] . "%'";
	}
	
	$sql = "SELECT * " . 
			"FROM board " .  
			"WHERE" . $whereClause . " ORDER BY ID LIMIT 1";
	
	$rs = mysqli_query($conn, $sql);
	if (mysqli_num_rows($rs) != 0) {
		$rc = mysqli_fetch_assoc($rs);
		echo $rc['Content'];
	} else {
		echo "";
	}
}
?>