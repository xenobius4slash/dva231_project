<?php
// global constants
define('CONFIG_PATH', 'backend/config/');
define('TMPL_PATH', 'frontend/tmpl/');
define('CSS_PATH', 'frontend/css/');
define('JS_PATH', 'frontend/js/');
define('SCRIPT_PATH', 'backend/script/');
define('CLASS_PATH', 'backend/class/');
define('INDEX_PATH', '');

require_once CLASS_PATH.'Session.php';

#############
## Session ##
#############
$S = new Session();
//echo "Session-ID: ".session_id()."<pre>"; print_r($_SESSION); echo "</pre>";
$isLogin = false;
$admin = false;
if( (isset($_GET['logout'])) && ($_GET['logout'] == 1) ) {
	$S->destroySession();
} else {
	if( $S->isLoggedIn() === true ) { 
		require_once CLASS_PATH.'User.php';
		$userId = $S->getSessionUserId();
		$U = new User();
		$user = $U->getUserById($userId);
		$username = $user['name'];
		$isLogin = true;
		if( $U->isAdmin($userId) ) {
			$admin = true;
		} else {
			$admin = false;
		}
	} 
}

#####################
## choose template ##
#####################
$page = '';
$phpfile = false;
if( isset($_GET['page']) ) {
	$p = htmlspecialchars($_GET['page']);
	switch($p) {
		case 'home' : if($isLogin) { $page = 'member'; $phpfile = true; } else { $page = 'home'; } break;
		case 'town' : $page = 'town'; $phpfile = true; break;
		case 'test' : $page = 'test'; $phpfile = true; break;
		case 'user' : $page = 'user'; $phpfile = true; break;
		case 'admin' : if($admin) { $page = 'admin'; $phpfile = true; } else { $page = 'home'; } break;
		default : if($isLogin) { $page = 'member'; $phpfile = true; } else { $page = 'home'; }
	}
} else {
	if($isLogin) { $page = 'member'; $phpfile = true; } else { $page = 'home'; }
}
###########
## error ##
###########
$Url = "HTTP://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if (strpos($Url, "login_fail=1") == true )
{
	if(strpos($Url, "error_code=1") == true){
		echo '<div class="alert alert-info">Wrong username or password</div>';
	}
	if(strpos($Url, "error_code=2") == true){
		echo '<div class="alert alert-info">[Internal Error] #2 Try again later</div>';
	}
	if(strpos($Url, "error_code=3") == true){
		echo '<div class="alert alert-info">[Internal Error] #3 Try again later</div>';
	}
	if(strpos($Url, "error_code=4") == true){
		echo '<div class="alert alert-info">[Internal Error] #4 Try again later/div>';
	}
	
}

if (strpos($Url, "register_fail=1") == true )
{
	if(strpos($Url, "error_code=1") == true){
		echo '<div class="alert alert-info">Invalid Email</div>';
	}
	if(strpos($Url, "error_code=2") == true){
		echo '<div class="alert alert-info">Invalid Username</div>';
	}
	if(strpos($Url, "error_code=3") == true){
		echo '<div class="alert alert-info">E-Mail address already in use</div>';
	}
	if(strpos($Url, "error_code=4") == true){
		echo '<div class="alert alert-info">Invalid Password</div>';
	}
	if(strpos($Url, "error_code=5") == true){
		echo '<div class="alert alert-info">Passwords do not match</div>';
	}
	if(strpos($Url, "error_code=6") == true){
		echo '<div class="alert alert-info">[Internal Error] #3 Try again later</div>';
	}
	if(strpos($Url, "error_code=7") == true){
		echo '<div class="alert alert-info">[Internal Error] #4 Try again later</div>';
	}
	if(strpos($Url, "error_code=8") == true){
		echo '<div class="alert alert-info">[Internal Error] #5 Try again later</div>';
	}
}
##############
## skeleton ##
##############
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>DVA231 - Weather Compare</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link href="<?=CSS_PATH?>bootstrap.css" rel="stylesheet" type="text/css" />
		<link href="<?=CSS_PATH?>index.css" rel="stylesheet" type="text/css" />

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="<?=JS_PATH?>jquery-3.3.1.js"></script>

	</head>
	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<!-- TO GIVE THE NAVBAR SOME MARGIN FROM THE BORDERS -->
			<div class="container">

				<!-- NAVBAR LOGO -->

				<div class="navbar-header">
					<a href="index.php" class="navbar-brand">Weather</a>
				</div>

				<!-- LEFT PART OF THE NAVBAR -->

				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a href="#">About</a></li>
						<li><a href="#">Contact</a></li>
						<?php
							if ($page != 'home' && $page != 'member') {
								echo '<form class="navbar-form navbar-left" role="search" method="POST" action="index.php?page=town">';
								echo '<div class="form-group">';
								echo '<input type="text" class="form-control" placeholder="Search" name="town">';
								echo '</div>';
								echo '&nbsp;';
								echo '<button type="submit" class="btn btn-default" name="town_search">Submit</button>';
								echo '</form>';
							}
						?>
					</ul>

					<!-- RIGHT PART OF THE NAVBAR -->
					<?php if($isLogin) { ?>
						<ul class="nav navbar-nav navbar-right" id="logout">
							<li><a href="index.php?logout=1">Logout</a></li>
						</ul>
						<ul class="nav navbar-nav navbar-right" id="user_page">
							<li><a href="index.php?page=user&id=<?=$userId?>"><?=$username?></a></li>
						</ul>
						<?php if($admin) { ?>
							<ul class="nav navbar-nav navbar-right" id="admin_page">
								<li><a href="index.php?page=admin">Administration</a></li>
							</ul>
						<?php } ?>
					<?php } else { ?>
						<ul class="nav navbar-nav navbar-right" id="login">
							<li><a href="" data-toggle="modal" data-target="#login-modal">Sign In</a></li>
						</ul>
					<?php } ?>
				</div>
			</div>
		</nav>

		<!-- main content -->
		<section id="main_container" class="container">
			<?php 
				if($phpfile) { 
					require_once SCRIPT_PATH.$page.'.php';
				}
				require_once TMPL_PATH.$page.'.tpl';
			?> 
		</section>

		<!-- login dialog box -->
 		<div id="login-modal" class="modal fade" role="dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
 			<div class="modal-dialog modal-m">
 				<div class="loginmodal-container">
					<!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
					 <!-- TABS LOGIN MODAL -->
					<div class="bs-example bs-example-tabs">
						<ul id="myTab" class="nav nav-tabs nav-justified">
							<li class="active"><a href="#signin" data-toggle="tab">Sign In</a></li>
							<li class=""><a href="#register" data-toggle="tab">Register</a></li>
							<li class=""><a href="#why" data-toggle="tab">Why?</a></li>
						</ul>
					</div>

					<!-- SWTICHING TABS -->
					<div id="myTabContent" class="tab-content">
						<!-- LOGIN TAB -->
						<div class="tab-pane fade active in" id="signin">
							<h1 class="">Login to your account</h1>
							<form method="post" action="backend\script\login.php" >
								<input type="text" name="login_email" placeholder="E-mail">
								<input type="password" name="login_password" placeholder="Password">
								<input type="submit" name="login_submit" value="Login" class="btn btn-success loginmodal-submit">
							</form>
							<div class="loginmodal-a">
								<a href="#">Forgot Password</a>
							</div>
						</div>

						<!-- REGISTER TAB -->

						<div class="tab-pane fade in" id="register">
							<h1 class="">Create an account</h1>
							<form method="post" action="backend/script/register.php">
								<input type="text" name="email" placeholder="E-mail">
								<input type="text" name="username" placeholder="Username">
								<input type="password" name="pass" placeholder="Password">
								<input type="password" name="passconfirm" placeholder="Re-Enter Password">
								<input type="submit" name="signup" value="Sign Up" class="btn btn-success loginmodal-submit">
							</form>
						</div>

						<!-- WHY TAB -->
						<div class="tab-pane fade in" id="why">
							<p>We need this information so that you can receive access to the site and its content. Rest assured your information will not be sold, traded, or given to anyone.</p>
							<p></p><br> Please contact <a mailto:href="info@weather.com">info@wheather.com</a> for any other inquiries.</p>
						</div>
					</div>
 				</div>
 			</div>
 		</div>

		<footer class="footer">
		  <div>© 2018 Copyright:
		    <a href="index.php"> Weather.com</a>
		  </div>
		</footer>

		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="<?=JS_PATH?>bootstrap.js"></script>
	</body>
</html>
