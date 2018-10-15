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
<!--
		<link href="<?=CSS_PATH?>bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="<?=CSS_PATH?>rpow.css" rel="stylesheet" type="text/css" />
-->
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--
		<script src="<?=JS_PATH?>jquery-2.1.4.min.js"></script>
-->
	</head>
	<body>
		<nav>
			NAVIGATION
		</nav>

		<section id="main_container">
			<?php 
			if($phpfile) { require_once SCRIPT_PATH.$page.'.php'; }
			require_once TMPL_PATH.$page.'.tpl';
			?>
		</section>

		<footer>
			FOOTER
		</footer>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
<!--
		<script src="<?=JS_PATH?>bootstrap.min.js"></script>
-->
	</body>
</html>
