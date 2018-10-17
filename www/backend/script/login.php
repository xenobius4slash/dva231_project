<?php
include_once 'defines.php';
require_once CLASS_PATH.'Session.php';
require_once CLASS_PATH.'User.php';

if( isset($_POST['login_submit']) ) {
	$username = htmlspecialchars($_POST['login_username']);
	$password = htmlspecialchars($_POST['login_password']);

	$U = new User();
	if( !$U->isLoginAuthenticated($username, $password) ) {
		error_log('[user] access denied');
	} else {
		$userId = $U->getUserIdByUsername($username);
		$S = new Session();
		if( $S->generateNewSessionId() === false ) {
			error_log('[session] Error while generating a new session id.');
		} else {
			if( $S->insertNewSessionInDb($userId) === false ) {
				error_log('[session] Error while saving the session id.');
			} else {
				if( !$S->setSessionUserId($userId) ) {
					error_log("[session] Error while set the username in session.");
				} else {
					header('Location: index.php');
				}
			}
		}
	}
}
header('Location: '.HTML_PATH.'login.php?login_fail=1');
?>
