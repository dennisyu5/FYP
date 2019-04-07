<?php
require_once(dirname(__FILE__) . '/../model/Board.php');
require_once(dirname(__FILE__) . '/../database/conn.php');

if (isset($_GET['gameboardID']) and isset($_GET['nextX']) and isset($_GET['nextY']) and isset($_GET['player']) and isset($_GET['turn'])) {
	extract($_GET);
	
	//update gameboard
	$sql = "SELECT * FROM gameboard, board, game, ai WHERE gameboard.ID = $gameboardID AND " .
														  "gameboard.BoardID = board.ID AND " .
														  "gameboard.GameID = game.ID AND " .
														  "(game.AIID = ai.ID OR game.AIID IS NULL) AND " .
														  "gameboard.NextX IS NULL AND " .
														  "gameboard.NextY IS NULL " .
														  "LIMIT 1";
	$rs = mysqli_query($conn, $sql);
	if (mysqli_num_rows($rs) != 0) {
		$rc = mysqli_fetch_assoc($rs);
		$content = json_decode($rc['Content']);
		$copyContent = clone $content;
		
		$board = new Board();
		$board->setBoard($content);
		$updatedList = $board->move($nextX, $nextY, $player);
		
		$sql = "UPDATE gameboard SET NextX = $nextX, NextY = $nextY, Mover = $player WHERE ID = $gameboardID";
		mysqli_query($conn, $sql) or die(mysqli_error($conn));
		
		if ($player == 1)
			array_push($content->black, $nextX . $nextY);
		else
			array_push($content->white, $nextX . $nextY);
		
		foreach ($updatedList as $location) {
			if ($player == 1) {
				array_splice($content->white, array_search($location, $content->white), 1);
				array_push($content->black, $location);
			} else {
				array_splice($content->black, array_search($location, $content->black), 1);
				array_push($content->white, $location);
			}
		}
		$board = new Board();
		$board->setBoard($content);
		
		$blackChessList = $board->getChessList(1);
		$whiteChessList = $board->getChessList(2);
		
		$content = json_encode(array("black" => $blackChessList, 
									 "white" => $whiteChessList));
		$copyContent = json_encode($copyContent);

		//update record
		$sql = "SELECT * FROM board WHERE Content = '$copyContent' LIMIT 1";
		$rs = mysqli_query($conn, $sql);
		$rc = mysqli_fetch_assoc($rs);
		$boardID = $rc['ID'];
		
		$sql = "SELECT * FROM record WHERE BoardID = $boardID AND NextX = $nextX AND NextY = $nextY";
		$rs = mysqli_query($conn, $sql);
		if (mysqli_num_rows($rs) == 0) {
			$sql = "INSERT INTO record (BoardID, NextX, NextY, Mover, TotalCount) VALUES ($boardID, $nextX, $nextY, $player, 1)";
			mysqli_query($conn, $sql) or die(mysqli_error($conn));
		} else {
			$rc1 = mysqli_fetch_assoc($rs);
			$totalCount = $rc1['TotalCount'];
			$ID = $rc1['ID'];
			
			$sql = "UPDATE record SET TotalCount = " . ($totalCount+1) . " WHERE ID = $ID";
			mysqli_query($conn, $sql) or die(mysqli_error($conn));
		}
		
		//update board
		$sql = "SELECT * FROM board WHERE Content = '$content' LIMIT 1";
		$rs = mysqli_query($conn, $sql);
		
		if (mysqli_num_rows($rs) == 0) {
			$sql = "INSERT INTO board (Content) VALUES ('$content')";
			if (mysqli_query($conn, $sql)) {
				$boardID = $conn->insert_id;
			} else {
				die(mysqli_error($conn));
			}
		} else {
			$rc = mysqli_fetch_assoc($rs);
			$boardID = $rc['ID'];
		}
		
		//create new gameboard
		if ($player%2 == 0) $turn++;
		
		$sql = "SELECT * FROM gameboard WHERE ID = $gameboardID ORDER BY ID DESC LIMIT 1";
		$rs = mysqli_query($conn, $sql);
		
		if (mysqli_num_rows($rs) != 0) {
			$rc = mysqli_fetch_assoc($rs);
			$gameID = $rc['GameID'];

			$sql = "INSERT INTO gameboard (GameID, BoardID, Turn) VALUES ($gameID, $boardID, $turn)";
			if (mysqli_query($conn, $sql)) {
				$gameboardID = $conn->insert_id;
				echo json_encode(array("status" => 1, "gameboardID" => $gameboardID)); // update successfully
			} else {
				echo json_encode(array("status" => 0)); //update failed
			}
		} else {
			echo json_encode(array("status" => 0)); //update failed
		}
	} else {
		echo json_encode(array("status" => 2)); //have already moved
	}
}
?>