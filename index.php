<?php
include "session.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Sentiment Interaction</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Le styles -->
<link href="css/customize-template.css" type="text/css"
	media="screen, projection" rel="stylesheet" />
<link href="css/docs.css" rel="stylesheet">

<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="img/favicon.png">

</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar">

	<!-- Navbar
    ================================================== -->
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li class="active"><a href="#" class="brand"><i class="icon-leaf"></i>
								Welcome</a></li>
					</ul>
			<?php if(isLoginAsAdmin()) { ?>
			<ul class="nav pull-right">
						<li><a href="#"><span class="label label-info">Welcome <?php echo $_SESSION['myusername']; ?></span></a>
						</li>
						<li><a href="logout.php"> Logout </a></li>
					</ul>
			<?php } ?>  
          </div>
			</div>
		</div>
	</div>

	<div class="jumbotron masthead">
		<div class="container">
			<h1>Graph Visualization</h1>
			<br>
			<p>The Effects of Sentiment in Peer Code Review</p>
			<br><br><br><br><br> 
			<p> 
 
			<!--  
			<?php if(isLoginAsAdmin()) { ?>
			<a href="admin.php" class="btn btn-primary btn-large">Enter Admin</a>
			<?php } else { ?> 
			<a href="#signin" data-toggle="modal"
					class="btn btn-primary btn-large">Sign In</a> <a
					href="graph_gerrit.php" class="btn btn-success btn-large">Guest</a>
			<?php } ?>  
			<br><br> 
			-->
 			<a href="graph_gerrit.php" class="btn btn-success btn-large">Enter</a>
			</p>
		</div>
	</div>

	<!-- Footer
    ================================================== -->
	<footer class="footer">
		<div class="container">
        <?php  include('footer.php'); ?>
		<!-- Sign In modal content -->
			<!--
			<div id="signin" class="modal hide fade" tabindex="-1" role="dialog"
				aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h3 id="myModalLabel">Sign In Form</h3>
				</div>
				<div class="modal-body">

					<form class="form-horizontal" method="post" action="login.php">
						<div class="control-group">
							<label class="control-label" for="inputName">User Name</label>
							<div class="controls">
								<input type="text" name="inputName" id="inputName"
									placeholder="User Name" required="required">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="inputPassword">Password</label>
							<div class="controls">
								<input type="password" name="inputPassword" id="inputPassword"
									placeholder="Password" maxlength="15" minlength="6"
									required="required">
							</div>
						</div>
						
						<div class="control-group">
							<div class="controls">
								<button type="submit" class="btn btn-info">Sign in</button>
								<button class="btn btn-danger" data-dismiss="modal"
									aria-hidden="true">Cancel</button>
							</div>
						</div>
						
					</form>
				</div>
				<div class="modal-footer">
					<p>For trial please enter taufan:123qweasd</p>
				</div>
			</div> 
			-->
		</div>
	</footer>

	<!-- ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="js/jquery-1.8.3.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jqBootstrapValidation.js"></script>
	<script>
	$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
	</script>

</body>
</html>
