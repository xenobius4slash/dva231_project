<?php
//require_once CONFIG_PATH.'mysql_login.inc.php';
require_once '../config/mysql_login.inc.php';

class Database {
	private $db;
	protected $columns = '*';
	protected $order = '';

	/** create the connection with the database and set the charset to "utf8"
	*/
	function __construct() {
		error_log(DB_HOST.", ".DB_USER.", ".DB_PASS.", ".DB_BASE);
		$this->db =  mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_BASE);
		if( mysqli_connect_errno() ) { 
			error_log("Failed to connect to MySQL: (" . mysqli_connect_errno() . ") " . mysqli_connect_error()); 
		} else {
//			error_log("Database connection established.");
			if (!$this->db->set_charset("utf8")) {
				error_log("Error loading character set utf8: ". $this->db->error);
			} 
//			else { error_log("Current character set: ". $this->db->character_set_name() ); }
		}
	}

	/** close the connection to the database
	*/
	function __destruct() {
		mysqli_close($this->db);
	}

	public function getDb() {
		return $this->db;
	}

	/** get the current columns
	*	@return		String
	*/
	public function getColumns() {
		return $this->columns;
	}

	/** set specific columns for a query
	*	@param		$stringColumns		String
	*/
	public function setDbColumns($stringColumns) {
		if($stringColumns != '') {
			$this->columns = $stringColumns;
		}
	}

	/** reset the columns to "*"
	*/
	public function resetDbColumns() {
		$this->columns = '*';
	}

	/** get the current order
	*   @return     String
	*/
	public function getOrder() {
		return $this->order;
	}

	/** set a specific order for a query
	*	@param		$stringOrder	String
	*/
	public function setOrder($stringOrder) {
		if($stringOrder != '') {
			$this->order = 'ORDER BY '.$stringOrder;
		}
	}

	/** reset the order to ""
	*/
	public function resetOrder() {
		$this->order = '';
	}

	/** get a masked string for the database query
	*	@param		$string		String
	*	@return		String
	*/
	public function escapeString($string) {
		return mysqli_real_escape_string($this->db, $string);
	}

	/** create and get an assoc array from a database result
	*	@param		$result		Database result object
	*	@return		Array
	*/
	public function getArrayFromSqlResult($result) {
		$resultArray = array();
		while ($row = $result->fetch_assoc()) {
			$resultArray[] = $row;
		}
		return $resultArray;
	}

	/** create and get an assoc array from one special line of the result
	*	the target line is controled throug the parameter "row", default of "row" is the 0, so the first line.
	*	@param		$result		Database result object
	*	@param		$row		Integer (default: 0)
	*	@return		Array
	*/
	public function getOneRowArrayFromSqlResult($result, $row=0) {
		$result->data_seek($row);
		return $result->fetch_assoc();
	}
}

?>
