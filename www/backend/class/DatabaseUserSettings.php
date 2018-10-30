<?php
require_once 'Database.php';

class DatabaseUserSettings extends Database {

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
		$sqlQuery = sprintf("UPDATE user SET settings = (settings) WHERE id = (userId)", 
						$this->escapeString($userId), $this->escapeString($settings));
		if( $this->getDb()->query($sqlQuery) ) {
			return true;
		} else {
			return false;
		}
	}


}
?>
