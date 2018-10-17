<?php
require_once '../class/Session.php';
require_once '../class/User.php';

if( isset($_POST['signup']) ) {
	$error = array('code' => null, 'msg' => null);
	$email = htmlspecialchars($_POST['email']);
	$username = htmlspecialchars($_POST['username']);
	$password1 = htmlspecialchars($_POST['pass']);
	$password2 = htmlspecialchars($_POST['passconfirm']);
	$registerError = false;

	$U = new User();
	if( !$U->isValidUsername($username) ) {
		$error['code'] = 1;
		$error['msg'] = 'No valid Username';
		$registerError = true;
	} else{
		if( $U->existUserByUsername($username) ) {
			$error['code'] = 2;
			$error['msg'] = 'The username already exists';
			$registerError = true;
		} else {
			if( (!$U->isValidPassword($password1)) || (!$U->isValidPassword($password2)) ) {
				$error['code'] = 3;
				$error['msg'] = 'No valid password';
				$registerError = true;
			} else {
				if($password1 != $password2) {
					$error['code'] = 4;
					$error['msg'] = 'The compare of passwords failed';
					$registerError = true;
				} else {
					if( !$U->insertNewUserInDb($username, $password1) ) {
						// ERROR: error while inserting the new user in the database
						$error['code'] = 5;
						$error['msg'] = 'Internal error #3';
						$registerError = true;
					} else {
						$userId = $U->getUserIdByUsername($username);
						$S = new Session();
						if( !$S->setSessionUserId($userId) ) {
							// ERROR: error while adding user-id in session (session doesn't exist)
							$error['code'] = 7;
							$error['msg'] = 'Internal error #4';
							$registerError = true;
						}
					}
				}
			}
		}
	}
	if($error['code'] == null && $registerError === true) {
		error_log("register => all ok");
//		header('Location: '.INDEX_PATH.'index.php');
	} else {
		error_log("register => errorcode: ".$error['code']." // msg: ".$error['msg']);
//		header('Location: '.HTML_PATH.'register.php?register_fail=1&error_code='.$error['code'].'&msg='.$error['msg']);
	}
}
?>
