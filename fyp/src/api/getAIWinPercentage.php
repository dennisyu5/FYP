<?php
require_once(dirname(__FILE__) . '/../database/conn.php');

$sql = "SELECT AIID, Winner, Description, Remark, COUNT(*) AS Count FROM game, ai WHERE game.AIID = ai.ID AND AIID IS NOT NULL AND Winner IS NOT NULL AND Winner != 3 GROUP BY AIID, Winner ORDER BY AIID, Winner";
$rs = mysqli_query($conn, $sql);

$list = array();

$currentAIID = 0;
$currentDescription = "";
$currentRemark = "";
$winCount = 0;
$totalCount = 0;

while ($rc = mysqli_fetch_assoc($rs)) {
	$AIID = $rc['AIID'];
	$winner = $rc['Winner'];
	$description = $rc['Description'];
	$remark = $rc['Remark'];
	
	if ($winner == 2) { //the ai wins the game
		$winCount = $rc['Count'];
		$totalCount += $rc['Count'];
		
		array_push($list, array("AIID" => $AIID,
								"description" => $description,
								"remark" => $remark,
								"winCount" => $winCount,
								"totalCount" => $totalCount));
		// reset
		$currentAIID = 0;
		$currentDescription = "";
		$currentRemark = "";
		$winCount = 0;
		$totalCount = 0;
	} else { //the ai loses the game
		if ($winner == 1 && $currentAIID != 0) { //the current ai does not win at least once
			array_push($list, array("AIID" => $currentAIID,
									"description" => $currentDescription,
									"remark" => $currentRemark,
									"winCount" => $winCount,
									"totalCount" => $totalCount));
			
			$winCount = 0;
		}
		// update the current ai
		$currentAIID = $AIID;
		$currentDescription = $description;
		$currentRemark = $remark;
		$totalCount = $rc['Count'];
	}
}

echo json_encode(array("list" => $list));
?>