<?php
require_once 'Database.php';

class DatabaseTown extends Database {
	private $expirationTime = 60;	// in minutes

	private function getExpirationTime() {
		return $this->expirationTime;
	}

	/** get all towns
	*	@return		NULL || Array
	*/
	public function getAllTowns() {
		$query = sprintf("SELECT ".$this->getColumns()." FROM town");
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->getArrayFromSqlResult($result);
		} else {
			return null;
		}
	}

	/** get one town by town-id
	*	@param		$id			Integer
	*	@return		NULL || Array
	*/
	public function getTownById($id) {
		$query = sprintf("SELECT ".$this->getColumns()." FROM town WHERE id = %u", $this->escapeString($id) );
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->getOneRowArrayFromSqlResult($result);
		} else {
			return null;
		}
	}

	/** check for existing of a town by tonw-id
	*	@param		$id		Integer
	*	@return		Bool
	*/
	public function existTownById() {
		if($this->getTownById($id) === null) {
			return false;
		} else {
			return true;
		}
	}

	/** get one or more town/s by name (LIKE)
	*	@param		$name		String
	*	@return		Array
	*/
	public function getTownsByLikeName($name) {
		$query = sprintf("SELECT ".$this->getColumns()." FROM town WHERE LOWER(name) LIKE LOWER('%%%s%%')", $this->escapeString($id) );
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->getArrayFromSqlResult($result);
		} else {
			return null;
		}
	}

	/** get one town by name (not LIKE)
	*	@param		$name		String
	*	@return		Array
	*/
	public function getTownByName($name) {
		$query = sprintf("SELECT ".$this->getColumns()." FROM town WHERE LOWER(name) = LOWER('%s')", $this->escapeString($name) );
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->getOneRowArrayFromSqlResult($result);
		} else {
			return null;
		}
	}

	/** insert a new town into the table
	*	@param		$name		String
	*	@return		Bool
	*/
	public function insertNewTown($name) {
		if($this->getTownByName($name) === null) {
			$query = sprintf("INSERT INTO town (name, last_update) VALUES('%s', NOW())", $this->escapeString($name) );
			return $this->getDb()->query($query);
		} else {
			return false;
		}
	}

	/** get all outdated towns regarding the validity ($this->expirationTime)
	*	@return		Array
	*/
	public function getAllOutdatedTowns() {
		// condition: last_update < (now - expirationTime)
		$query = sprintf("SELECT ".$this->getColumns()." FROM town WHERE last_update < DATE_SUB(NOW(), INTERVAL %u MINUTE)", $this->escapeString($this->getExpirationTime()) );
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->igetArrayFromSqlResult($result);
		} else {
			return null;
		}
	}

	/** get all up-to-date towns regarding the validity ($this->expirationTime)
	*	@return		Array
	*/
	public function getAllUptodateTowns() {
		// condition: last_update >= (now - expirationTime)
		$query = sprintf("SELECT ".$this->getColumns()." FROM town WHERE last_update >= DATE_SUB(NOW(), INTERVAL %u MINUTE)", $this->escapeString($this->getExpirationTime()) );
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->getArrayFromSqlResult($result);
		} else {
			return null;
		}
	}

	/** set one town to up-to-date
	*	@param		$id			Integer
	*	@return		Bool
	*/
	public function setTownToUptodateById($id) {
		$query = sprintf("UPDATE town SET last_update = NOW() WHERE id = %u", $this->escapeString($id) );
		return $this->getDb()->query($query);
	}

	/** is the given town up-to-date?
	*	@param		$dbResult		Array
	*	@return		Bool
	*/
	public function isTownUptodateByDbResult($array) {
//		echo "if(".$array['last_update']." >= ".date('Y-m-d H:i:s', strtotime('-60 minutes') ).")\n";
		if( strtotime($array['last_update']) >=  strtotime("-".$this->getExpirationTime()." minutes") ) {
			return true;
		} else {
			return false;
		}
	}

	/** delete one town by id
	*	@param		$id			Integer
	*	@return		Bool
	*/
	public function deleteTownById($id) {
		$query = sprintf("DELETE FROM town WHERE id = %u", $this->escapeString($id) );
		return $this->getDb()->query($query);
	}

	/** delete all towns
	*	@return		Bool
	*/
	public function deleteAllTowns() {
		$query = sprintf("DELETE FROM town");
		$result = $this->getDb()->query($query);
		if($result === true) {
			return true;
		} else {
			return false;
		}
	}
}
?>
