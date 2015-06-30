<?php
include "session.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>gerrit Network Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="layout" content="main" />
	<link rel="shortcut icon" href="img/favicon.png">
	<link href="css/customize-template.css" rel="stylesheet">
	<script src="js/visjs/vis.js"></script>
	<script src="js/jquery/jquery-1.8.2.min.js"></script>
	<script src="js/application.js"></script>
	<style>
	#body-content {
		padding-top: 40px;
	}
	</style>
</head>
<body>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a href="#" class="brand"><i class="icon-leaf">Dashboard</i></a>

				<!-- Dashboard menu -->
				<!--
				<div id="app-nav-top-bar" class="nav-collapse">
					<ul class="nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle"
								data-toggle="dropdown">Menu <b class="caret hidden-phone"></b>
							</a>
							<ul class="dropdown-menu">
	                            <?php if(isLoginAsAdmin()) { ?>
								<li><a href="admin.php">Admin</a></li>
								<?php } ?>
								<li><a href="graph_pull_request.php">Network Graph</a></li>
							</ul>
						</li>
					</ul>
					<ul class="nav pull-right">
						<li>
							<?php if(isLoginAsAdmin()) { ?>
							<a href="#"><span class="label label-info">Welcome <?php echo $_SESSION['myusername']; ?></span></a>
							<?php } else { ?>
							<a href="#">Welcome <b>Guest</b></a>
							<?php } ?>
						</li>
						<li>
							<?php if(isLoginAsAdmin()) { ?>
                        	<a href="logout.php">Logout</a>
							<?php } ?>
                        </li>
					</ul>
				</div>
				-->
 
			</div>
		</div>
	</div>

	<div id="body-container">
		<div id="body-content">
			<div class="body-nav body-nav-vertical body-nav-fixed">
				<div class="container">
					<ul>
						<li><a href="#box-gerritDeveloper" id="gerritDeveloper-side-btn">
								<script>
									$('#gerritDeveloper-side-btn').click(function() { 
										$( "#box-gerritDeveloper" ).attr( "class", "box-gerritDeveloper box-content out collapse" );
										$( "#box-gerritDeveloper" ).attr( "style", "height: 0px;" );
										$( "#box-projects" ).attr( "class", "box-projects box-content in collapse" );
										$( "#box-projects" ).attr( "style", "height: auto;" );
									});
									</script> <i class="icon-th-list icon-large"></i> List Project
						</a></li>
						<li><a href="#box-projects"
							id="citation-induced-side-btn"> <script>
									$('#citation-induced-side-btn').click(function() {
										$( "#box-gerritDeveloper" ).attr( "class", "box-gerritDeveloper box-content in collapse" );
										$( "#box-gerritDeveloper" ).attr( "style", "height: auto;" );
										$( "#box-projects" ).attr( "class", "box-projects box-content out collapse" );
										$( "#box-projects" ).attr( "style", "height: 0px;" );
									});
									</script> <i class="icon-bar-chart icon-large"></i> Network Graph
						</a></li>
					</ul>
				</div>
			</div>
			<section class="nav nav-page">
				<div class="container">
					<div class="row">
						<div class="span7">
							<header class="page-header">
								<h3>
									gerrit Network<br /> <small>Graph Visualization</small>
								</h3>
							</header>
						</div>
						<div class="page-nav-options">
							<div class="span9">
								<ul class="nav nav-pills">
									<li><a href="index.php"><i class="icon-home icon-large"></i></a>
									</li>
								</ul>
								<ul class="nav nav-pills">
									<?php if(isLoginAsAdmin()) { ?>
									<li><a href="admin.php"><i class="icon-dashboard"></i>Admin Page</a></li>
									<?php } ?>
									<li><a href="graph_gerrit.php"><i class="icon-bar-chart"></i>Graph Gerrit</a></li>
									<li><a href="graph_github.php"><i class="icon-bar-chart"></i>Graph Github</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</section>

			<section class="page container">
				<div class="row">
						<div class="span16">
							<div class="box">
								<div class="box-header">
									<i class="icon-sign-blank"></i>
									<h5>Gerrit Projects</h5>
									<button class="btn btn-box-right" data-toggle="collapse"
										data-target=".box-projects">
										<i class="icon-reorder"></i>
									</button>
								</div>
								<div id="box-projects"
									class="box-projects box-content collapse in">

									<!-- Place to show users table asynchronously -->
									<div id="data-projects"></div>

								</div>
							</div>
						</div>
					</div>

				<div class="row">
					<div class="span16">
						<div class="box">
							<div class="box-header">
								<i class="icon-sign-blank"></i>
								<h5>Sentiment Network Graph, Project Name : </h5><text id="project_name"></text>  
								<button class="btn btn-box-right" data-toggle="collapse"
									data-target=".box-gerritDeveloper">
									<i class="icon-reorder"></i>
								</button>
							</div>
							<div id="box-gerritDeveloper"
								class="box-gerritDeveloper box-content collapse in">

								<!-- gerritDeveloper graph code -->
								<div id="graph-view-gerritDeveloper"></div>

							</div>
						</div>
					</div>
				</div>

			</section>
		</div>
	</div>

	<footer class="application-footer">
			<?php  include('footer.php'); ?>
    </footer>

	<script>
	_GRAPH_TYPE = "gerrit";  
    </script>

</body>
</html>
