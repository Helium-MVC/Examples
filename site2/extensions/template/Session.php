<?php
use app\services\session\SessionService;

/**
 * Session
 * 
 * Adds the ability to access variables from the current session from within a template;
 */
class Session {
	
	/**
	 * Gets the value from the current session
	 * 
	 * @param string $key The key of the stored item
	 * 
	 * @return $value
	 */
	public function get($key) {
		return SessionService::read($key);
	}
	
	/**
	 * Retrieves an api token for calling the
	 * api
	 */
	public function generateApiToken() {
		
		$private_key = SessionService::read('api_token');
		
		$public_key = PVSecurity::generateToken(20);
		
		$signature = PVSecurity::encodeHmacSignature($public_key, $private_key);
		
		return '
			<input type="hidden" name="api_public_key" value="' . $public_key . '" id="api_public_key"  />
			<input type="hidden" name="api_signature" value="' . $signature . '"  id="api_signature" />
		';
		
	}
}
