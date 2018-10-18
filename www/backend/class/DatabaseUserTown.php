<?php
require_once 'Database.php';

class DatabaseUserTown extends Database {

	/** get the combinations from a user
	*	@param		$userId			Integer
	*	@return		NULL || Array
	*/
	public function getByUserId($userId) {
		$query = sprintf("SELECT ".$this->getColumns()." FROM user_town WHERE user_id = %u", $this->escapeString($userId) );
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->getArrayFromSqlResult($result);
		} else {
			return null;
		}
	}

	/** get the combinations from a town
	*	@param		$townId			Integer
	*	@return		NULL || Array
	*/
	public function getByTownId($townId) {
		$query = sprintf("SELECT ".$this->getColumns()." FROM user_town WHERE town_id = %u", $this->escapeString($townId) );
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->getArrayFromSqlResult($result);
		} else {
			return null;
		}
	}

	/** get one combination by user-ids and town-id
	*	@param		$userId			Integer
	*	@param		$townId			Integer
	*	@return		NULL || Array
	*/
	public function getByUserIdAndTownId($userId, $townId) {
		$query = sprintf("SELECT ".$this->getColumns()." FROM user_town WHERE user_id = %u AND town_id = %u", 
					$this->escapeString($userId), $this->escapeString($townId) );
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->getOneRowArrayFromSqlResult($result);
		} else {
			return null;
		}
	}

	/** check for the existing of the given combination of user and town
	*	@param		$userId			Integer
	*	@param		$townId			Integer
	*	@return		Bool
	*/
	public function existByUserIdAndTownId($userId, $townId) {
		if($this->getByUserIdAndTownId($userId, $townId) !== null) {
			return true;
		} else {
			return false;
		}
	}

	/** get the town-ids from a user
	*	@param		$userId			Integer
	*	@return		NULL || Array of town-ids
	*/
	public function getTownIdsByUserId($userId) {
		$arrayResult = $this->getByUserId($userId);
		if($arrayResult === null) {
			return null;
		} else {
			$arrayTownIds = array();
			for($i=0; $i<count($arrayResult); $i++) {
				$arrayTownIds[] = $arrayResult[$i]['town_id'];
			}
			return $arrayTownIds;
		}
	}

	/** get the user-ids by a town-id
	*	@param		$townId			Integer
	*	@return		NULL || Array of user-ids
	*/
	public function getUserIdsByTownId($townId) {
		$arrayResult = $this->getByTownId($townId);
		if($arrayResult === null) {
			return null;
		} else {
			$arrayUserIds = array();
			for($i=0; $i<count($arrayResult); $i++) {
				$arrayUserIds[] = $arrayResult[$i]['user_id'];
			}
			return $arrayUserIds;
		}
	}

	/** delete all tuples from a user (user account deleted)
	*	@param		$userId			Integer
	*	@return		Bool
	*/
	public function deleteByUserId($userId) {
		$query = sprintf("DELETE FROM user_town WHERE user_id = %u", $this->escapeString($userId) );
		$result = $this->getDb()->query($query);
		if($result === true) {
			return true;
		} else {
			return false;
		}
	}

	/** delete one combination of town and user (user delete this town in the own view)
	*	@param		$userId			Integer
	*	@param		$townId			Integer
	*	@return		Bool
	*/
	public function deleteByUserIdAndTownId($userId, $townId) {
		$query = sprintf("DELETE FROM user_town WHERE user_id = %u AND town_id = %u", 
					$this->escapeString($userId), $this->escapeString($townId) );
		$result = $this->getDb()->query($query);
		if($result === true) {
			return true;
		} else {
			return false;
		}
	}

	/** insert a new tuple into the table, if not exist
	*	@param		$userId			Integer
	*	@param		$townId			Integer
	*	@return		Bool
	*/
	public function insertNewRow($userId, $townId) {
		if( $this->existByUserIdAndTownId($userId, $townId) === true ) {
			return false;
		} else {
			$newPosition = 1;
			$this->setDbColumns('position');
			$arrayByUser = $this->getByUserId($userId);
			if($arrayByUser !== null) {
				$newPosition = count($arrayByUser) + 1;
			}
			$query = sprintf("INSERT INTO user_town (user_id, town_id, position) VALUES(%u, %u, %u)", 
						$this->escapeString($userId), $this->escapeString($townId), $this->escapeString($newPosition) );
			return $this->getDb()->query($query);
		}
	}
}
?>
