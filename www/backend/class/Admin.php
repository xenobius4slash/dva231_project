<?php

require_once 'DatabaseTown.php';
require_once 'DatabaseUser.php';
require_once 'DatabaseUserTown.php';
require_once 'DatabaseWeatherDataCurrent.php';

class Admin {

	/* removes a registered user
	*	@param		$userId				Integer
	*	@return		Array
	*/
	public function removeUserById($userId) {
		$return = array('status' => null, 'msg' => null);
		if( !intval($userId) ) {
			$return['status'] = false;
			$return['msg'] = 'not a valid user id';
		} else {
			$DBU = new DatabaseUser();
			if( !$DBU->existUserById($userId)  ) {
				$return['status'] = false;
				$return['msg'] = 'the user does not exist';
			} else {
				$DBUT = new DatabaseUserTown();
				if( !$DBUT->deleteByUserId($userId) ) {
					$return['status'] = false;
					$return['msg'] = 'error while deleting tuples from the database table "user_town"';
				} else {
					if( !$DBU->deleteUserById($userId) ) {
						$return['status'] = false;
						$return['msg'] = 'error while deleting user from the database table "user"';
					} else {
						$return['status'] = true;
					}
				}
			}
		}
		return $return;
	}

	/* removes all stored weather datas
	*	@return		Bool
	*/
	public function removeAllWeatherDatas() {
		$DBWDC = new DatabaseWeatherDataCurrent();
		return $DBWDC->deleteAllWeatherDatas();
	}

	/* removes all towns
	*
	*/
	public function removeAllTowns() {
		$DBT = new DatabaseTown();
		return $DBT->deleteAllTowns();
	}

	public function removeAllTownsAndWeatherDatas() {
		$return = array('status' => null, 'msg' => null);
		if( !$this->removeAllTowns() ) {
			$return['status'] = false;
			$return['msg'] = 'error while removing all saved towns';
		} else {
			if( !$this->removeAllWeatherDatas() ) {
				$return['status'] = false;
				$return['msg'] = 'error while removing all saved weather datas';
			} else {
				$return['status'] = true;
			}
		}
	}
	
}
?>
