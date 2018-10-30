<?php
include_once 'defines.php';
require_once CLASS_PATH.'Session.php';
require_once CLASS_PATH.'User.php';

if( isset($_POST['login_submit']) ) {
	$return = array('error' => null, 'code' => null, 'msg' => null);
	$email = htmlspecialchars($_POST['login_email']);
	$password = htmlspecialchars($_POST['login_password']);
	$return['error'] = false;
	
	$U = new User();
	
	if( !$U->isLoginAuthenticated($email, $password) ) {
		error_log('[user] access denied');
		$return['error'] = true;
		$return['code'] = 1;
		$return['msg'] = 'access denied';
	} else {
		$userId = $U->getUserIdByEmail($email);
		$S = new Session();
		if( $S->generateNewSessionId() === false ) {
			error_log('[session] Error while generating a new session id.');
			$return['error'] = true;
			$return['code'] = 2;
			$return['msg'] = 'Error while generating a new session id';
		} else {
			if( !$S->setSessionUserId($userId) ) {
				error_log("[session] Error while set the user-id in session.");
				$return['error'] = true;
				$return['code'] = 3;
				$return['msg'] = 'Error while set the user-id in session';
			} else {
				header('Location: index.php');
			}
		}
	}
	if($return['error'] === false) {
		header('Location: '.INDEX_PATH.'index.php');
	} else {
	$return['msg'] = 'i dont know';
		header('Location: '.INDEX_PATH.'login.php?login_fail=1&error_code='.$return['code'].'&msg='.$return['msg']);
	}
}

?>
