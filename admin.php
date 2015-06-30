<?php
include "session.php";
if(!isLoginAsAdmin())
	header("location:index.php"); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Github Network Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="layout" content="main" />
	<link rel="shortcut icon" href="img/favicon.png">
	<link href="css/customize-template.css" rel="stylesheet" />	
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
				<div id="app-nav-top-bar" class="nav-collapse">
					<ul class="nav">
						<li class="dropdown"><a href="#" class="dropdown-toggle"
							data-toggle="dropdown">Menu <b class="caret hidden-phone"></b>
						</a>
							<ul class="dropdown-menu">
								<li><a href="admin.php">Admin Page</a></li>
								<li><a href="graph_pull_request.php">Graph Page</a></li>
							</ul></li>
					</ul>
					<ul class="nav pull-right">
						<li><a href="#"><span class="label label-info">Welcome <?php echo $_SESSION['myusername']; ?></span></a>
						</li>
						<li><a href="logout.php"> Logout </a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div id="body-container">
		<div id="body-content">

			<div class="body-nav body-nav-vertical body-nav-fixed">
				<div class="container">
					<ul>
						<li><a href="#box-user-management" id="user-management-side-btn">
								<script>
									$('#user-management-side-btn').click(function() {
										$( "#box-user-management" ).attr( "class", "box-user-management box-content in collapse" ); 
										$( "#box-user-management" ).attr( "style", "height: auto;" );  
										$( "#box-citation-management" ).attr( "class", "box-citation-management box-content out collapse" ); 
										$( "#box-citation-management" ).attr( "style", "height: 0px;" ); 
									});
									</script> <i class="icon-user icon-large"></i> User Management
						</a></li>
						<li><a href="#box-citation-management"
							id="citation-management-side-btn"> <script>
									$('#citation-management-side-btn').click(function() {
										$( "#box-user-management" ).attr( "class", "box-user-management box-content out collapse" ); 
										$( "#box-user-management" ).attr( "style", "height: 0px;" ); 
										$( "#box-citation-management" ).attr( "class", "box-citation-management box-content in collapse" ); 
										$( "#box-citation-management" ).attr( "style", "height: auto;" ); 
									});
									</script> <i class="icon-bar-chart icon-large"></i> Graph
								Management
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
									Github Network<br /> <small>Graph Visualization</small>
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
									<li class="active"><a href="admin.php"><i class="icon-dashboard"></i>Admin Page</a></li>
									<li><a href="graph_pull_request.php"><i class="icon-bar-chart"></i>Graph Pull Request</a></li>
									<li><a href="graph_commit.php"><i class="icon-bar-chart"></i>Graph Commit</a></li>
									<li><a href="graph_issue.php"><i class="icon-bar-chart"></i>Graph Issue</a></li> 
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
								<h5>User Management</h5>
								<button class="btn btn-box-right" data-toggle="collapse"
									data-target=".box-user-management">
									<i class="icon-reorder"></i>
								</button>
							</div>
							<div id="box-user-management"
								class="box-user-management box-content collapse in">
								<h3>
									Data User <a href="#dialog-user" id="0" class="btn add"
										data-toggle="modal"> <i class="icon-plus"></i> Add User
									</a>
								</h3>

								<!-- Place to show users table asynchronously -->
								<div id="data-user"></div>

								<!-- start for modal dialog -->
								<div id="dialog-user" class="modal hide fade" tabindex="-1"
									role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"
											aria-hidden="true">×</button>
										<h3 id="myModalLabel">Add User</h3>
									</div>
									
									<!-- Place to show popup form asynchronously -->
									<div class="modal-body"></div>
									
									<div class="modal-footer">
										<button class="btn btn-danger" data-dismiss="modal"
											aria-hidden="true">Cancel</button>
										<button type="submit" id="save-user" class="btn btn-success">Save</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<!-- 
				<div class="row">
					<div class="span16">
						<div class="box">
							<div class="box-header">
								<i class="icon-sign-blank"></i>
								<h5>Graph Management</h5>
								<button class="btn btn-box-right" data-toggle="collapse"
									data-target=".box-citation-management">
									<i class="icon-reorder"></i>
								</button>
							</div>
							<div id="box-citation-management"
								class="box-citation-management box-content collapse out">

								<h4>Code for graph database</h4> 

							</div>
						</div>
					</div>
				</div>
				-->
			</section>
		</div>
	</div>
 
	<footer class="application-footer">  
			<?php  include('footer.php'); ?>
        </footer>
 
	<script src="js/bootstrap/bootstrap-transition.js"></script>
	<script src="js/bootstrap/bootstrap-alert.js"></script>
	<script src="js/bootstrap/bootstrap-modal.js"></script>
	<script src="js/bootstrap/bootstrap-dropdown.js"></script>
	<script src="js/bootstrap/bootstrap-scrollspy.js"></script>
	<script src="js/bootstrap/bootstrap-tab.js"></script>
	<script src="js/bootstrap/bootstrap-tooltip.js"></script>
	<script src="js/bootstrap/bootstrap-popover.js"></script>
	<script src="js/bootstrap/bootstrap-button.js"></script>
	<script src="js/bootstrap/bootstrap-collapse.js"></script>
	<script src="js/bootstrap/bootstrap-carousel.js"></script>
	<script src="js/bootstrap/bootstrap-typeahead.js"></script>
	<script src="js/bootstrap/bootstrap-affix.js"></script>
	<script src="js/bootstrap/bootstrap-datepicker.js"></script>
	<script src="js/jquery/jquery-tablesorter.js"></script>
	<script src="js/jquery/jquery-chosen.js"></script>
	<script src="js/jquery/virtual-tour.js"></script> 
	<script>
        $(function() {
            $('#sample-table').tablesorter();
            $('#datepicker').datepicker();
            $(".chosen").chosen();
        });
    </script>

</body>
</html>