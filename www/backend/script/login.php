<?php
include_once 'defines.php';
require_once CLASS_PATH.'Session.php';
require_once CLASS_PATH.'User.php';

if( isset($_POST['login_submit']) ) {
	$email = htmlspecialchars($_POST['login_email']);
	$password = htmlspecialchars($_POST['login_password']);
	$msg = '';

	$U = new User();
	if( !$U->isLoginAuthenticated($email, $password) ) {
		error_log('[user] access denied');
		$msg = 'access denied';
	} else {
		$userId = $U->getUserIdByEmail($email);
		$S = new Session();
		if( $S->generateNewSessionId() === false ) {
			error_log('[session] Error while generating a new session id.');
			$msg = 'Error while generating a new session id';
		} else {
			if( !$S->setSessionUserId($userId) ) {
				error_log("[session] Error while set the user-id in session.");
				$msg = 'Error while set the user-id in session';
			} else {
				header('Location: index.php');
			}
		}
	}
}
header('Location: '.INDEX_PATH.'login.php?login_fail=1&msg='.$msg);
?>
