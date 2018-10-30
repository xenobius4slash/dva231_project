<?php
require_once 'defines.php';
require_once CLASS_PATH.'Session.php';
require_once CLASS_PATH.'User.php';

if( isset($_POST['signup']) ) {
	$return = array('error' => null, 'code' => null, 'msg' => null);
	$email = htmlspecialchars($_POST['email']);
	$username = htmlspecialchars($_POST['username']);
	$password1 = htmlspecialchars($_POST['pass']);
	$password2 = htmlspecialchars($_POST['passconfirm']);

	$U = new User();

	if( !$U->isValidEmail($email) ) {
		$return['error'] = true;
		$return['code'] = 1;
		$return['msg'] = 'No valid E-Mail address';
	} else {
		if( !$U->isValidUsername($username) ) {
			$return['error'] = true;
			$return['code'] = 2;
			$return['msg'] = 'No valid Username';
		} else {
			if( $U->existUserByEmail($email) ) {
				$return['error'] = true;
				$return['code'] = 3;
				$return['msg'] = 'The E-Mail address already exists';
			} else {
				if( (!$U->isValidPassword($password1)) || (!$U->isValidPassword($password2)) ) {
					$return['error'] = true;
					$return['code'] = 4;
					$return['msg'] = 'No valid password';
				} else {
					if($password1 != $password2) {
						$return['error'] = true;
						$return['code'] = 5;
						$return['msg'] = 'The compare of passwords failed';
					} else {
						if( !$U->insertNewUserInDb($email, $username, $password1) ) {
							// ERROR: error while inserting the new user in the database
							$return['error'] = true;
							$return['code'] = 6;
							$return['msg'] = 'Internal error #3';
						} else {
							$userId = $U->getUserIdByEmail($email);
							$S = new Session();
							if( !$S->setSessionUserId($userId) ) {
								// ERROR: error while adding user-id in session (session doesn't exist)
								$return['error'] = true;
								$return['code'] = 7;
								$return['msg'] = 'Internal error #4';
								if(!$U->setDefaultSettings($userId) ) {
									// ERROR: error while inserting default settings
									$return['error'] = true;
									$return['code'] = 8;
									$return['msg'] = 'Internal error #5';
								}
							} else {
								$return['error'] = false;
							}
						}
					}
				}
			}
		}
	}
	if($return['error'] === false) {
		header('Location: '.INDEX_PATH.'index.php');
	} else {
		header('Location: '.INDEX_PATH.'register.php?register_fail=1&error_code='.$return['code'].'&msg='.$return['msg']);
	}
}
?>
