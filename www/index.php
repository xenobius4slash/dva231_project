<?php
// global constants
define('CONFIG_PATH', 'backend/config/');
define('TMPL_PATH', 'frontend/tmpl/');
define('CSS_PATH', 'frontend/css/');
define('JS_PATH', 'frontend/js/');
define('SCRIPT_PATH', 'backend/script/');
define('CLASS_PATH', 'backend/class/');

// choose template
$page = '';
$phpfile = false;
if( isset($_GET['page']) ) {
	$p = htmlspecialchars($_GET['page']);
	switch($p) {
		case 'home' : $page = 'home';  break;
		case 'town' : $page = 'town'; $phpfile = true; break;
//		case 'admin' : if($admin) { $page = 'admin'; $phpfile = true; } else { $page = 'home'; } break;
//		case 'imprint': $page = 'imprint'; break;
		default : $page = 'home';
	}
} else {
	$page = 'home';
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>DVA231 - Weather Compare</title>

		<!-- Bootstrap -->
		<link href="<?=CSS_PATH?>bootstrap.css" rel="stylesheet" type="text/css" />
		<link href="<?=CSS_PATH?>index.css" rel="stylesheet" type="text/css" />
		<!-- <link href="<?=CSS_PATH?>rpow.css" rel="stylesheet" type="text/css" /> -->

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

		<script src="<?=JS_PATH?>jquery-3.3.1.js"></script>

	</head>
	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<!-- TO GIVE THE NAVBAR SOME MARGIN FROM THE BORDERS -->
			<div class="container">

				<!-- NAVBAR LOGO -->

				<div class="navbar-header">
					<a href="#" class="navbar-brand">Weather</a>
				</div>

				<!-- LEFT PART OF THE NAVBAR -->

				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a href="#">About</a></li>
						<li><a href="#">Contact</a></li>
						<form class="navbar-form navbar-left" role="search">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Search">
							</div>
						<button type="submit" class="btn btn-default">Submit</button>
					</form>

					</ul>

					<!-- RIGHT PART OF THE NAVBAR -->

					<ul class="nav navbar-nav navbar-right">
						<!-- <li><a href="#">Sign Up</a></li> -->
						<li><a href="" data-toggle="modal" data-target="#login-modal">Login</a></li>
					</ul>

				</div>
			</div>
		</nav>

 		<div id="login-modal" class="modal fade" role="dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
 			<div class="modal-dialog">
 				<div class="loginmodal-container">
 					<h1 class="">Login to your account</h1>
 					<form>
					 	<input type="text" name="user" placeholder="Username">
					 	<input type="password" name="pass" placeholder="Password">
					 	<input type="submit" name="login" value="Login" class="btn btn-success loginmodal-submit">
					</form>
					<div class="loginmodal-a">
						<a href="#">Register</a> - <a href="#">Forgot Password</a>
					</div>
 				</div>
 			</div>
 		</div>



		<section id="main_container">
			<?php 
				if($phpfile) { 
					require_once SCRIPT_PATH.$page.'.php';
				}
				require_once TMPL_PATH.$page.'.tpl';
			?> 
		</section>

		<footer>
			FOOTER
		</footer>
		<!-- Include all compiled plugins (below), or include individual files as needed -->

		<script src="<?=JS_PATH?>bootstrap.js"></script>

	</body>
</html>
