<?php
require_once 'Database.php';

class DatabaseUser extends Database {

	/**	get user by email
	*	@param		$email			String
	*	@return		DB-Objekt
	*/
	public function getUserByEmail($email) {
		$sqlQuery = sprintf("SELECT ".$this->getColumns()." FROM user WHERE LOWER(email) = LOWER('%s')", $this->escapeString($email) );
		$result = $this->getDb()->query($sqlQuery);
		if($result->num_rows > 0) {
			return $result;
		} else {
			return null;
		}
	}

	/**	check for exist of a user by email
	*	@param		$email			String
	*	@return		Bool
	*/
	public function existUserByEmail($email) {
		if( $this->getUserByEmail($email) != null) {
			return true;
		} else {
			return false;
		}
	}

	/**	get user by username
	*	@param		$username		String
	*	@return		DB-Objekt
	*/
	public function getUserByUsername($username) {
		$sqlQuery = sprintf("SELECT ".$this->getColumns()." FROM user WHERE LOWER(name) = LOWER('%s')", $this->escapeString($username) );
		$result = $this->getDb()->query($sqlQuery);
		if($result->num_rows > 0) {
			return $result;
		} else {
			return null;
		}
	}

	/**	check for exist of a user by username
	*	@param		$username		String
	*	@return		Bool
	*/
	public function existUserByUsername($username) {
		if( $this->getUserByUsername($username) != null) {
			return true;
		} else {
			return false;
		}
	}

	/** get user by user-id
	*	@param		$userId		Integer
	*	@return		Database object
	*/
	public function getUserById($userId) {
		$sqlQuery = sprintf("SELECT ".$this->getColumns()." FROM user WHERE id = %u", $this->escapeString($userId) );
		$result = $this->getDb()->query($sqlQuery);
		if($result->num_rows > 0) {
			return $result;
		} else {
			return null;
		}
	}

	/**	check for exist of a user by id
	*	@param		$userId		Integer
	*	@return		Bool
	*/
	public function existUserById($userId) {
		if( $this->getUserById($userId) != null) {
			return true;
		} else {
			return false;
		}
	}

	/** insert a new user into the database
	*	@param		$email					String
	*	@param		$username				String
	*	@param		$passwordHash			String
	'	@param		$level					Integer (default: 3 => user)
	*	@return		Bool
	*/
	public function insertNewUser($email, $username, $passwordHash, $level = 3) {
		$sqlQuery = sprintf("INSERT INTO user (email, name, password, level) VALUES('%s', '%s', '%s', %u)", 
						$this->escapeString($email), $this->escapeString($username), $this->escapeString($passwordHash), $this->escapeString($level));
		if( $this->getDb()->query($sqlQuery) ) {
			return true;
		} else {
			return false;
		}
	}

	/** delete a user
	*	@param		$userId			Integer
	*	@return		Bool
	*/
	public function deleteUserById($userId) {
		$query = sprintf("DELETE FROM user WHERE id = %u", $this->escapeString($userId) );
		$result = $this->getDb()->query($query);
		if($result === true) {
			return true;
		} else {
			return false;
		}
	}
	
	
	/**	get user settings by userId
	*	@param		$UserId			String
	*	@return		JSONObject
	*/
	
	public function getUserSettings($userId) {
		$sqlQuery = sprintf("SELECT ".$this->getColumns()." FROM user WHERE LOWER(userId) = LOWER('%s')", $this->escapeString($userId) );
		$result = $this->getDb()->query($sqlQuery);
		if($result->num_rows > 0) {
			return $result;
		} else {
			return null;
		}
	}
	
	/**	set user settings for userId
	*	@param		$UserId			String
	*	@param		$settings		JSONObject
	*	@return		bool
	*/
	
	public function setUserSettings($userId, $settings) {
		$sqlQuery = sprintf("UPDATE user SET settings = '%s' WHERE id = %u", $this->escapeString($settings), $this->escapeString($userId)); 
		if( $this->getDb()->query($sqlQuery) ) {
			return true;
		} else {
			return false;
		}
	}
}
?>
