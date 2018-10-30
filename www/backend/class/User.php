<?php
require_once 'DatabaseUser.php';

class User {
	function __construct() { }

	function __destruct() { }
	
	
	/**	check for valid email
	*	@param		$email		String
	*	@return		Bool
	*/
	public function isValidEmail($email) {
		if(strlen($email) > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**	check for valid username
	*	@param		$username		String
	*	@return		Bool
	*/
	public function isValidUsername($username) {
		if(strlen($username) > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**	check for valid password
	*	@param		$password		String
	*	@return		Bool
	*/
	public function isValidPassword($password) {
		if( strlen($password) > 0 ) {
			return true;
		}
	}

	/** create a hash from the password for saving in the database
	*	@param		$password		String
	*	@return		String
	*/
	public function getPasswordHashByPassword($password) {
		return password_hash($password, PASSWORD_BCRYPT, array('cost' => 10));
	}

	/** check for password and hash match
	*	@param		$password		String
	*	@param		$hash			String
	*	@return		Bool
	*/
	public function isPasswordVerify($password, $hash) {
		if( password_verify($password, $hash) ) {
			return true;
		} else {
			return false;
		}
	}

	/** check if the authentication is possible
	*	@param		$email			String
	*	@param		$password		String
	*	@return		Bool
	*/
	public function isLoginAuthenticated($email, $password) {
		$result = $this->getUserByEmail($email);
		if($result == null) {
			return false;
		} else {
			if( $this->isPasswordVerify($password, $result['password']) ) {
				return true;
			} else {
				return false;
			}
		}
	}

	/**	exist user by user-id
	*	@param		$userId		Integer
	*	@return		Bool
	*/
	public function existUserById($userId) {
		$DBU = new DatabaseUser();
		$DBU->setDbColumns('id');
		if( $DBU->existUserById($userId) ) {
			return true;
		} else {
			return false;
		}
	}

	/** get user by user-id
	*	@param		$userId		Integer
	*	@return		Array
	*/
	public function getUserById($userId) {
		$DBU = new DatabaseUser();
		$result = $DBU->getUserById($userId);
		if($result == null) {
			return false;
		} else {
			return $DBU->getOneRowArrayFromSqlResult($result);
		}
	}

	/**	exist user by username
	*	@param		$username		String
	*	@return		Bool
	*/
	public function existUserByUsername($username) {
		$DBU = new DatabaseUser();
		$DBU->setDbColumns('name');
		if( $DBU->existUserByUsername($username) ) {
			return true;
		} else {
			return false;
		}
	}

	/** get user by username
	*	@param		$username		String
	*	@return		Array
	*/
	public function getUserByUsername($username) {
		$DBU = new DatabaseUser();
		$result = $DBU->getUserByUsername($username);
		if($result == null) {
			return false;
		} else {
			return $DBU->getOneRowArrayFromSqlResult($result);
		}
	}

	/**	exist user by email
	*	@param		$email		String
	*	@return		Bool
	*/
	public function existUserByEmail($email) {
		$DBU = new DatabaseUser();
		$DBU->setDbColumns('email');
		if( $DBU->existUserByEmail($email) ) {
			return true;
		} else {
			return false;
		}
	}

	/** get user by email
	*	@param		$email		String
	*	@return		Array
	*/
	public function getUserByEmail($email) {
		$DBU = new DatabaseUser();
		$result = $DBU->getUserByEmail($email);
		if($result == null) {
			return false;
		} else {
			return $DBU->getOneRowArrayFromSqlResult($result);
		}
	}

	/** get the user-id from a user by email
	*	@param		$email		String
	*	@return		Integer
	*/
	public function getUserIdByEmail($email) {
		$DBU = new DatabaseUser();
		$DBU->setDbColumns('id');
		$result = $DBU->getUserByEmail($email);
		if($result == null) {
			return false;
		} else {
			$result = $DBU->getOneRowArrayFromSqlResult($result);
			return $result['id'];
		}
	}

	/** insert a new user into the database
	*	@param		$email				String
	*	@param		$username			String
	*	@param		$password			String
	*	@return		Bool
	*/
	public function insertNewUserInDb($email, $username, $password) {
		$DBU = new DatabaseUser();
		if( $DBU->insertNewUser($email, $username, $this->getPasswordHashByPassword($password)) ) {
			return true;
		} else {
			return false;
		}
	}
	
	/** get the settings from a user by user-id
	*	@param		$user-id		integer
	*	@return		JSONObject
	*/
	
	public function getUserSettings($userId){
		$DBU = new DatabaseUser();
		$DBU->setDbColumns('settings');
		$result = $DBU->getUserSettings($userId);
		if($result == null) {
			return false;
		} else {
			$result = $DBU->getOneRowArrayFromSqlResult($result);
			return $result['settings'];
		}
	}
	
	/** set the settings for a user by user-id
	*	@param		$user-id		integer
	*	@param		JSONObject
	*	@return 		bool
	*/
	
	Public function setUserSettings($userId, $settings){
		$DBU = new DatabaseUser();
		if( $DBU->setUserSettings($userId, $settings) ) {
			return true;
		}else {
			return false;
		}
	}
	
	/** set the settings for a user by user-id
	*	@param		$user-id		integer
	*	@return 		bool
	*/
	
	public function setDefaultSettings($userId)
	{
		$defaultSettings = new \stdClass();
		$defaultSettings->unit = "celsius";
		$defaultSettings = Json_encode($defaultSettings);	//Default settings for users
		
		$U = new User();
		if( $U->setUserSettings($userId, $defaultSettings ) ) {
			return true;
		}else {
			return false;
		}
	}
	
	public function changeUsername($userId, $newUsername)
	{
		$DBU = new DatabaseUser();
		if( $DBU->changeUsername($userId, $newUsername) ) {
			return true;
		}else {
			return false;
		}
	}
	
}
?>
