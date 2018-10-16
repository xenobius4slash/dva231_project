<?php
require_once 'DatabaseUser.php';

class User {
	function __construct() { }

	function __destruct() { }

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
	*	@param		$username		String
	*	@param		$password		String
	*	@return		Bool
	*/
	public function isLoginAuthenticated($username, $password) {
		$result = $this->getUserByUsername($username);
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
		$DBU->setDbColumns('username');
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

	/** get the user-id from a user by username
	*	@param		$username		String
	*	@return		Integer
	*/
	public function getUserIdByUsername($username) {
		$DBU = new DatabaseUser();
		$DBU->setDbColumns('id');
		$result = $DBU->getUserByUsername($username);
		if($result == null) {
			return false;
		} else {
			$result = $DBU->getOneRowArrayFromSqlResult($result);
			return $result['id'];
		}
	}

	/** insert a new user into the database
	*	@param		$username			String
	*	@param		$password			String
	*	@return		Bool
	*/
	public function insertNewUserInDb($username, $password) {
		if( ($this->isValidUsername($username)) && ($this->isValidPassword($password)) )
		{
			if( $this->existUserByUsername($username) ) {
				return false;
			} else {
				$DBU = new DatabaseUser();
				if( $DBU->insertNewUser($username, $this->getPasswordHashByPassword($password)) ) {
					return true;
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}
}
?>
