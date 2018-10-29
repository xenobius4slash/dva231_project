<?php
include_once 'defines.php';
require_once CLASS_PATH.'Session.php';
require_once CLASS_PATH.'User.php';

if( isset($_POST['settings_saved']) ) {
	
	$backgroundColor = $_POST['backgroundColor'];
	$unit = $_POST['unit'];
	$msg = '';
	
	if (!$unit == "celsius" || !$unit == "fahrenheit"){
		$unit = "celsius";
	}//Error bad unit, Set to default
	
	$settingsObj->backgroundColor = $backgroundColor;
	$settingsObj->unit = $unit;
	
	$settingsJSON = Json_encode($settingsObj);
	
	$U = new User();
	if(!getSessionUserId()) {
		$return['error'] = true;
		$return['code'] = 1;
		$return['msg'] = 'No Signed-in User';
		if(!$U->existUserById(getSessionUserId()))
		{
			$return['error'] = true;
			$return['code'] = 2;
			$return['msg'] = 'No valid userId';
			if(!$U->setUserSettings(getSessionUserId(), $settingsJSON )
			{
				$return['error'] = true;
				$return['code'] = 3;
				$return['msg'] = 'Unable to save settings';
				
			}else{
				$return['error'] = false;
			}
		}
	}
}
	
	


header('Location: '.INDEX_PATH.'settings.php?settings_fail=1&msg='.$msg);
?>