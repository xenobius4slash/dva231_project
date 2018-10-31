<?php
require_once 'defines.php';
require_once CLASS_PATH.'Admin.php';

if( isset($_POST['op']) ) {
	$operation = htmlspecialchars($_POST['op']);
	switch($operation) {
		case 'del_user': delUser(); break;	// delete one user
		case 'del_wd': break;	// delete all weather data
		case 'del_towns': break;	//delete all towns
		case 'del_wd_towns': break;	// delete all weather data and all towns
	}
}

function delUser() {
	$return = array('status' => null, 'msg' => null);
	if( isset($_POST['user_id']) && intval($_POST['user_id'])) {
		$userId = intval($_POST['user_id']);
		$ADMIN = new Admin();
		$result = $ADMIN->removeUserById($userId);
		if($result['status'] === true) {
			$return['status'] = true;
		} else {
			$return['status'] = true;
			$return['msg'] = 'error while deleting the user => '.$result['msg'];
		}
	} else {
		$return['status'] = false;
		$return['msg'] = 'non valid parameter';
	}
	echo json_encode($return);
}


?>
