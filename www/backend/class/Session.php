<?php

class Session {
	private $expirationSeconds = 600;	// how long is a session valid

	function __construct() {
		session_start();
		$this->setEmptyCookie();
	}

	function __destruct() { }

	/** get the limited lifetime of a session in seconds
	*	@return		Integer
	*/
	private function getExpirationSeconds() {
		return $this->expirationSeconds;
	}

	/** create a empty cookie */
	private function setEmptyCookie() {
		if(ini_get("session_use_cookies")) {		// if "session_use_cookies" is set in php.ini
			$params = session_get_cookie_params();	// get contents of the cookie
			// create empty cookie
			setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}
	}

	/**	delete all data in the current session	*/
	private function emptySession() {
		$_SESSION = array();
	}

	/** delete the complete session */
	public function destroySession() {
		$this->generateNewSessionId();
		$this->emptySession();
		$this->setEmptyCookie();
		session_destroy();
	}

	/**	check if a user is logged in
	*	@return		Bool
	*/
	public function isLoggedIn() {
		if( isset($_SESSION['timeout']) ) {
			$duration = time() - (int)$_SESSION['timeout'];
			if( (isset($_SESSION['uid'])) && (intval($_SESSION['uid'])) && ($duration <= $this->getExpirationSeconds()) ) {
				require_once CLASS_PATH.'DatabaseUser.php';
				$DBU = new DatabaseUser();
				$DBU->setDbColumns('id');
				if( $DBU->existUserById($_SESSION['uid']) ) {
					$_SESSION['timeout'] = time();
					return true;
				} else {
					$this->destroySession();
					return false;
				}
			}
		} else {
			$this->destroySession();
			return false;
		}
	}

	/**	generate a new session id
	*	@return		Bool
	*/
	public function generateNewSessionId() {
		return session_regenerate_id(true);
	}

	/**	get the current session id
	*	@return		String
	*/
	public function getSessionId() {
		return session_id();
	}

	/** get user-id in session
	*	@return		Integer
	*/
	public function getSessionUserId() {
		if( $this->getSessionId() != '') {
			return $_SESSION['uid'];
		} else {
			return false;
		}
	}

	/** set user-id in session
	*	@param		$userId		Integer
	*	@return		Bool
	*/
	public function setSessionUserId($userId) {
		if( $this->getSessionId() != '') {
			$_SESSION['uid'] = $userId;
			$_SESSION['timeout'] = time();
			return true;
		} else {
			return false;
		}
	}
	
	
	public function setSessionSettings(){
		$U = new User();
		$_SESSION['settings'] = $U->getUserSettings($this->getSessionUserId());
		return true;
	}

}
?>
