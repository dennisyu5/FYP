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
			require_once(dirname(__FILE__) . '/common.php');
			if (isset($_GET['numOfRecords'])) {
				extract($_GET);
				$numOfRecords = "?numOfRecords=$numOfRecords";
			} else {
				$numOfRecords = "";
			}
			$json = json_decode(file_get_contents("http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/src/api/getBoards.php$numOfRecords"));
			$list = $json->list;
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
				<h1>AI</h1>
			</div>
			
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span12">
						<div class="widget-box">
							<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
								<h5>Records</h5>
							</div>
							<div class="widget-content nopadding">
								<table class="table table-bordered data-table">
									<thead>
										<tr>
										  <th>ID</th>
										  <th>Board</th>
										  <th>Choices</th>
										</tr>
									</thead>
									<tbody>
										<?php
											if ($list != null) {
												for ($i = 0; $i < count($list); $i++) {
													$content = $list[$i]->content;
													$choices = $list[$i]->choices;
													$mover = ($list[$i]->mover == 1)? "Black" : "White";
													
													$boardString = getBoardString(json_decode($content), $size);
													echo "<tr><td style='text-align:center'>" . ($i+1) . "</td>" . //print ID
														 "<td style='text-align:center;vertical-align: middle;'>" . $boardString . "<br /></td>"; //print board
													
													echo "<td>";
													if (count($choices) != 0) {
														echo "<div class='widget-box'><div class='widget-title'> <span class='icon'><i class='icon-th'></i></span>" . 
																"<h5>" . $mover . "</h5>" . 
															"</div>" . 
															"<div class='widget-content nopadding'>" . 
															"<table class='table table-bordered'>" .
															 "<thead><tr>" . 
																"<th>Location</th>" .
																"<th>Win</th>" .
																"<th>Total</th>" .
																"<th>Percentage</th>" . 
																"<th>Average Percentage</th>" . 
															 "</tr></thead>" . 
															 "<tbody>";
														
														$totalPercentage = 0;
														$max = 0;
														foreach ($choices as $choice) {
															$totalPercentage += $choice->winCount/$choice->totalCount;
															if ($max < $choice->winCount/$choice->totalCount) {
																$max = $choice->winCount/$choice->totalCount;
															}
														}
														
														$totalPercentage = ($totalPercentage == 0)? 1 : $totalPercentage;
														
														foreach ($choices as $choice) {
															echo "<tr";
															
															if ($max == $choice->winCount/$choice->totalCount) {
																echo " class='alert alert-success alert-block'";
															}
															
															echo "><td style='text-align:center'>" . chr(96+$choice->nextX) . $choice->nextY . "</td>" . 
																 "<td style='text-align:center'>" . $choice->winCount . "</td>" . 
																 "<td style='text-align:center'>" . $choice->totalCount . "</td>" . 
																 "<td style='text-align:center'>" . round($choice->winCount/$choice->totalCount*100, 4) . "%</td>" . 
																 "<td style='text-align:center'>" . round($choice->winCount/$choice->totalCount/$totalPercentage*100, 4) . "%</td></tr>";
														
														}

														echo "</tbody></table></div></div>";
													}
													
													if (count($choices) == 0){
														$winner = -1;
														if (count(json_decode($content)->black) > count(json_decode($content)->white)) {
															$winner = "Black";
														} else if (count(json_decode($content)->black) < count(json_decode($content)->white)) {
															$winner = "White";
														}
														$numberOfChess = array("Black" => count(json_decode($content)->black),
																			   "White" => count(json_decode($content)->white));
														foreach ($numberOfChess as $mover => $count) {
															echo "[$mover]: $count";
															if ($mover == $winner) {
																echo " - <b>Win</b>";
															}
															echo "<br />";
														}
														
														if ($winner == -1) {
															echo "The game ended in a standoff.";
														}
													}
													echo "</td></tr>";
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
	</body>
</html>