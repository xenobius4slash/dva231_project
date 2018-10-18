<?php
require_once 'Database.php';

class DatabaseUserLevel extends Database {

	/** get all user levels
	*	@return		NULL || Array
	*/
	public function getAllLevels() {
		$query = sprintf("SELECT ".$this->getColumns()." FROM user_level");
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->getArrayFromSqlResult($result);
		} else {
			return null;
		}
	}

	/** get one user level by id
	*	@param		$id			Integer
	*	@return		NULL || Array
	*/
	public function getLevelById($id) {
		$query = sprintf("SELECT ".$this->getColumns()." FROM user_level WHERE id = %u", $this->escapeString($id));
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->getOneRowArrayFromSqlResult($result);
		} else {
			return null;
		}
	}

	/** check for existing of a user level by id
	*	@param		$id			Integer
	*	@return		Bool
	*/
	private function existLevelById($id) {
		if($this->getLevelById($id) !== null) {
			return true;
		} else {
			return false;
		}
	}

	/** get one user level by level name
	*	@param		$name		String
	*	@return		NULL || Array
	*/
	public function getLevelByName($name) {
		$query = sprintf("SELECT ".$this->getColumns()." FROM user_level WHERE LOWER(name) = LOWER('%s')", $this->escapeString($name));
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->getOneRowArrayFromSqlResult($result);
		} else {
			return null;
		}
	}
}
?>
