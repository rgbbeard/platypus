<?php
namespace Platypus\SessionManager;

use Platypus\Exceptions\InvalidSessionException;
use Platypus\Database\MySQL;

class SessionManager {
	protected MySQL $conn;

	public function __construct() {
		$this->conn = new MySQL();
	}
	
	/**
	 * @throws InvalidSessionException
	 */
	public static function check_session() {
		switch(session_status()) {
			case PHP_SESSION_DISABLED:
				throw new InvalidSessionException("Sessions are disabled");
			case PHP_SESSION_NONE:
				session_start();
				break;
			case PHP_SESSION_ACTIVE:
				return session_id();
		}
	}
	
	/**
	 * @throws InvalidSessionException
	 */
	public static function reset_session() {
		# an existing session was found
		if(self::check_session()) {
			$_SESSION = [];
		}
	}

	public static function restart_session() {
		session_destroy();
		session_start();
	}
	
	# used to store values before redirecting to another route
	public static function set_last_operation_result(mixed $result) {
		$_SESSION["LAST_OPERATION_RESULT"] = [];
		
		$_SESSION["LAST_OPERATION_RESULT"]["VALUE"] = $result;
		$_SESSION["LAST_OPERATION_RESULT"]["CONSUMED"] = false;
	}
	
	public static function consume_last_operation_result(): mixed {
		$result = $_SESSION["LAST_OPERATION_RESULT"]["VALUE"] ?? false;
		
		$_SESSION["LAST_OPERATION_RESULT"] = null;
		
		return $result;
	}
	
	/**
	 * @throws InvalidSessionException
	 */
	public function save_user_session(string $username, string $session_id): bool {
		$query = "insert into serjeant_user_sessions(user_id, value)
		select
		    id as user_id,
		    '$session_id' as value
		from serjeant_users
		where username = '$username';";
		
		$result = $this->conn->execute($query);
		
		if($result) {
			$this->set_user_session_cookie($session_id);
		} else {
			throw new InvalidSessionException("Unable to save login infos");
		}
		
		return true;
	}
	
	public function set_user_session_cookie(string $session_id): bool {
		return setcookie("JSESSIONID", $session_id);
	}
}