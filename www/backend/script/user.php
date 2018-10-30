<?php
//include_once 'defines.php';
//require_once CLASS_PATH.'Session.php';
//require_once CLASS_PATH.'User.php';

if( isset($_POST['settings_saved']) ) {
	
	include_once 'defines.php';
	require_once CLASS_PATH.'Session.php';
	require_once CLASS_PATH.'User.php';

	$return = array('error' => null, 'code' => null, 'msg' => null);
	//$backgroundColor = $_POST['username'];
	$username = $_POST['username'];
	$unit = $_POST['degreeType'];
	$msg = '';
	
	/*if (!$unit == "celsius" || !$unit == "fahrenheit"){
		$unit = "celsius";
	}//Error bad unit, Set to default
	*/
	
	//$settingsObj->backgroundColor = $backgroundColor;
	$settingsObj = new \stdClass();
	$settingsObj->unit = $unit;
	$settingsObj = Json_encode($settingsObj);
	
	$U = new User();
	$S = new Session();
	if(!$unit == NULL)
	{
		if(!$S->getSessionUserId()) {
		$return['error'] = true;
		$return['code'] = 1;
		$return['msg'] = 'No Signed-in User';
		} else{
			if(!$U->existUserById($S->getSessionUserId()))
			{
				$return['error'] = true;
				$return['code'] = 2;
				$return['msg'] = 'No valid userId';
			} else{
				if(!$U->setUserSettings($S->getSessionUserId(), $settingsObj ) )
				{
					$return['error'] = true;
					$return['code'] = 3;
					$return['msg'] = 'Unable to save settings';
				
				}
			}
		}
	}
	
	if(!$username == NULL)
	{
		if($U->existUserByUsername($username))
		{
		$return['error'] = true;
		$return['code'] = 4;
		$return['msg'] = 'Username already exist';
		} else{
			if(!$U->isValidUsername($username))
			{
			$return['error'] = true;
			$return['code'] = 5;
			$return['msg'] = 'Invalid Username';
			} else{
				if(!$U->changeUsername($S->getSessionUserId(), $username))
				{
					$return['error'] = true;
					$return['code'] = 6;
					$return['msg'] = 'Unable to change Username';
				}else{
					$return['error'] = false;
				}
			} 
		}
	}else{
		$return['error'] = false;
	}
	
	
	
	if($return['error'] === false) {
		header('Location: '.INDEX_PATH.'index.php');
	} else {
	
		header('Location: '.INDEX_PATH.'user.php?settings_fail=1&error_code='.$return['code'].'&msg='.$return['msg']);
	}
}

?>
