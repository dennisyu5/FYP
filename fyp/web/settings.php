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
			$json = json_decode(file_get_contents("http://localhost:" . $_SERVER['SERVER_PORT'] . "/fyp/src/api/getAIRemark.php?ai=3"));
			$remark = $json->remark;
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
				<h1>Settings</h1>
			</div>
			
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span12">
						<?php
							if (isset($_GET['status'])) {
								extract($_GET);
								if ($status == 1) {
									echo "<div class='alert alert-success alert-block'> <a class='close' data-dismiss='alert' href='#'>×</a>" . 
										  "<h4 class='alert-heading'>Success!</h4>The depth has been set to the depth.</div>";
								} else {
									echo "<div class='alert alert-error alert-block'> <a class='close' data-dismiss='alert' href='#'>×</a>" . 
										  "<h4 class='alert-heading'>Error!</h4>The AI has already set to the depth.</div>";
								}
							}
						?>
						<div class="widget-box">
							<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
								<h5>AI depth (For Hard AI)</h5>
							</div>
							<div class="widget-content nopadding">
								<form action="../src/api/updateAIRemark.php" method="get" class="form-horizontal">
									<div class="control-group">
										<label class="control-label">Depth:</label>
										<div class="controls">
											<input id="ai" name="ai" type="hidden" value="3" />
											<input id="remark" name="remark" type="number" placeholder="Current depth: <?php echo $remark; ?>" max="2" required />
											<span class="help-inline">max: 2</span>
											<span class="help-block">Please input a depth for the Hard AI</span>
										</div>
										
										<div class="form-actions">
											<button type="submit" class="btn btn-success">Submit</button>
										</div>
									</div>
								</form>
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