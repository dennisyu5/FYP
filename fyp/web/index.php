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
			$json = json_decode(file_get_contents("http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/src/api/getAIWinPercentage.php"));
			$list = $json->list;

			$aiInformation = array();
			for ($i = 0; $i < count($list); $i++) {
				$AIID = $list[$i]->AIID;
				$description = substr($list[$i]->description, 0, strlen($list[$i]->description)-2) . " " . substr($list[$i]->description, strlen($list[$i]->description)-2);
				$winCount = $list[$i]->winCount;
				$totalCount = $list[$i]->totalCount;
				
				array_push($aiInformation, array("AIID" => $AIID,
												 "description" => $description,
												 "percentage" => round($winCount/$totalCount*100, 2)));
			}
			
			function getStyle($id) {
				if ($id == 1) {
					$style = array("color" => "bg_ly", "icon" => "icon-star-half");
				} else if ($id == 2) {
					$style = array("color" => "bg_lo", "icon" => "icon-star");
				} else if ($id == 3) {
					$style = array("color" => "bg_lr", "icon" => "icon-bolt");
				} else if ($id == 4) {
					$style = array("color" => "bg_lb", "icon" => "icon-signal");
				} else {
					$style = array("color" => "bg_lg", "icon" => "icon-tablet");
				}
				return $style;
			}
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
				<h1>Dashboard</h1>
			</div>
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="widget-box">
						<div class="widget-title bg_lg"><span class="icon"><i class="icon-trophy"></i></span>
							<h5>Win Rate</h5>
						</div>
						<div class="widget-content" >
							<div class="row-fluid">
								<div class="span12">
									<ul class="site-stats">
										<?php for ($i = 0; $i < count($aiInformation); $i++) { ?>
											<a href="analysis.php"><li class="<?= getStyle($aiInformation[$i]['AIID'])['color']; ?>">
												<i class="<?= getStyle($aiInformation[$i]['AIID'])['icon']; ?>"></i> 
												<strong><?= $aiInformation[$i]['percentage']; ?>%</strong> 
												<small><?= $aiInformation[$i]['description']; ?></small>
											</li></a>
										<?php } ?>
									</ul>
								</div>
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