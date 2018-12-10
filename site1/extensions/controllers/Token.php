<?php
use app\services\session\SessionService;

/**
 * This class is designed to check the CSFR Token to ensure there is not an
 * attack on the system.
 */
class Token {
	
	/**
	 * Executs the check for the token
	 */
	public function check($key, $token) {
		
		$stored_token = SessionService::read('csrf_'. $key);
		
		
		return hash_equals($token, $stored_token);
		
	}
}
