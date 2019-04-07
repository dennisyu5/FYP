<!DOCTYPE html>
<html>
	<head>
		<title>Reversi</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="../css/bootstrap.min.css" />
		<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
		<link rel="stylesheet" href="../css/uniform.css" />
		<link rel="stylesheet" href="../css/select2.css" />
		<link rel="stylesheet" href="../css/matrix-style.css" />
		<link rel="stylesheet" href="../css/matrix-media.css" />
		<link href="../font-awesome/css/font-awesome.css" rel="stylesheet" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
	
		<link rel="icon" href="http://www.freezingblue.com/reversi/images/reversi_128/reversi_128.png">
		<?php
			require(dirname(__FILE__) . '/common.php');
			$json = json_decode(file_get_contents("http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/src/api/getGames.php"));
			$gameList = $json->list;
			$list = [];
			$game = [];
			$gameSelectedlist = [];
			if (isset($_GET['userID'])) {
				extract($_GET);
				if (isset($_GET['gameID'])) {
					$json = json_decode(file_get_contents("http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/src/api/getGamesByUserID.php?userID=$userID&gameID=$gameID"));
					$gameSelectedlist = $json->list;
				}
				$json = json_decode(file_get_contents("http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/src/api/getGamesByUserID.php?userID=$userID"));
				$game = $json->list;
				
				if (isset($_GET['gameID'])) {
					$json = json_decode(file_get_contents("http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/src/api/getBoardsByUserID.php?userID=$userID&gameID=$gameID"));
				} else {
					$json = json_decode(file_get_contents("http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/src/api/getBoardsByUserID.php?userID=$userID"));
				}
				$list = $json->list;
			}
			$size = 8;
		?>
	
	</head>
	
	<body>
		<!--main-container-part-->
		<div id="content">
			<!--Action boxes-->
			<div class="container-fluid">
				<div class="quick-actions_homepage">
					<ul class="quick-actions">
						<li class="bg_lb span3"> <a href="index.php"> <i class="icon-dashboard"></i> Dashboard</a> </li>
						<li class="bg_lo span3"> <a href="game.php"> <i class="icon-tablet"></i> Game</a> </li>
						<li class="bg_lg span3"> <a href="analysis.php"> <i class="icon-signal"></i> Analysis</a> </li>
						<li class="bg_ly span3"> <a href="ai.php"> <i class="icon-eye-open"></i> AI</a> </li>
						<li class="bg_db span3"> <a href="settings.php"> <i class="icon-cog"></i> Settings</a> </li>
					</ul>
				</div>
				<!--End-Action boxes-->
			</div>
			<hr>
			<div id="content-header">
				<h1>Game</h1>
			</div>
			
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span12">
						<div class="btn-group">
							<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><?php echo isset($userID)? $userID : "User ID";?> <span class="caret"></span></button>
							<ul id="userID" name="userID" class="dropdown-menu">
								<?php
									if (count($gameList) != 0) {
										for ($i = 0; $i < count($gameList); $i++) {
											echo "<li value=\"" . $gameList[$i] . "\"><a>" . $gameList[$i] . "</a></li>";
										}
									} else {
										echo "<li class='disabled'><a>No record</a></li>";
									}
								?>
							</ul>
						</div>
						<div class="btn-group">
							<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><?php echo (count($gameSelectedlist) == 1)? $gameSelectedlist[0]->startDate : "Start Date";?> <span class="caret"></span></button>
							<ul id="gameID" name="gameID" class="dropdown-menu">
								<?php
									if (count($game) != 0) {
										for ($i = 0; $i < count($game); $i++) {
											echo "<li value=\"" . $game[$i]->gameID . "\"><a>" . $game[$i]->startDate . "</a></li>";
										}
									} else {
										echo "<li class='disabled'><a>No record</a></li>";
									}
								?>
							</ul>
						</div>
						<div class="widget-box">
							<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
								<h5>Game</h5>
							</div>
							<div class="widget-content nopadding">
								<table class="table table-bordered data-table">
									<thead>
										<tr>
										  <th style='display:none'>ID</th>
										  <th>Board</th>
										  <th>Move</th>
										  <th style='display:none'>ID</th>
										  <th>Board</th>
										  <th>Move</th>
										  <th style='display:none'>ID</th>
										  <th>Board</th>
										  <th>Move</th>
										  <th style='display:none'>ID</th>
										  <th>Board</th>
										  <th>Move</th>
										</tr>
									</thead>
									<tbody>
										<?php
											if ($list != null) {
												for ($i = 0; $i < count($list); $i++) {
													$boardString = getBoardString(json_decode($list[$i]->content), $size);
													if ($list[$i]->mover == 1) {
														$nextMover = "⚫ -> ";
													} else if ($list[$i]->mover == 2) {
														$nextMover = "⚪ -> ";
													} else {
														$nextMover = "";
													}
													$nextLocation = chr(96+$list[$i]->nextX) . $list[$i]->nextY;
													
													if ($i % 4 == 0) {
														echo "<tr>"; //check the first column in each row
													}
													echo "<td style='display:none'>" . 
															($i+1) . 
														 "</td>" . 
														 "<td style='text-align:center;vertical-align: middle;'>" . $boardString . "<br /></td>" . //print board
														 "<td style='text-align:center;vertical-align: middle;'>" . 
															"[⚫]: " . count(json_decode($list[$i]->content)->black) . "<br />" . 
															"[⚪]: " . count(json_decode($list[$i]->content)->white) . "<br /><br /><br />" . 
															$nextMover . 
															$nextLocation . 
														 "</td>"; //print ID
													if (($i+1) % 4 == 0) {
														echo "</tr>"; //check the last column in each row
													} else if (($i+1) == count($list)) {
														for ($j = 0; $j < (4 - count($list)%4)%4*4; $j++) {
															echo "<td style='display:none'></td>"; //print empty
														}
														for ($j = 0; $j < (4 - count($list)%4)%4*2; $j++) {
															echo "<td></td>"; //print empty
														}
														echo "</tr>"; //end
													}
												}
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="../js/jquery.min.js"></script> 
		<script src="../js/jquery.ui.custom.js"></script> 
		<script src="../js/bootstrap.min.js"></script> 
		<script src="../js/jquery.uniform.js"></script> 
		<script src="../js/select2.min.js"></script> 
		<script src="../js/jquery.dataTables.min.js"></script> 
		<script src="../js/matrix.js"></script> 
		<script src="../js/matrix.tables.js"></script>
		
		<script type="text/javascript">
			$("#userID li").on("click", function(){
				if ($(this).attr('class') != "disabled") {
					var value = $(this).attr('value');
					window.location.href = window.location.pathname + "?userID=" + value;
				}
			});
			
			$("#gameID li").on("click", function(){
				if ($(this).attr('class') != "disabled") {
					<?php if (isset($userID)) { ?>
						var userID = <?php echo $userID;?>;
					<?php } else { ?>
						var userID = $("#userID li").attr('value');
					<?php } ?>
					var gameID = $(this).attr('value');
					window.location.href = window.location.pathname + "?userID=" + userID + "&gameID=" + gameID;
				}
			});
		</script>
	</body>
</html>