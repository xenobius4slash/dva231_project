<?php
require_once 'Database.php';

class DatabaseUser extends Database {
	/**	get user by username
	*	@param		$username		String
	*	@return		DB-Objekt
	*/
	public function getUserByUsername($username) {
		$sqlQuery = sprintf("SELECT ".$this->getColumns()." FROM user WHERE username = '%s'", $this->escapeString($username) );
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
	*	@param		$username				String
	*	@param		$passwordHash			String
	*	@return		Bool
	*/
	public function insertNewUser($username, $passwordHash) {
		$sqlQuery = sprintf("INSERT INTO user (username, password) 
							VALUES('%s', '%s')", $this->escapeString($username), $this->escapeString($passwordHash) );
		if( $this->getDb()->query($sqlQuery) ) {
			return true;
		} else {
			return false;
		}
	}
}
?>
