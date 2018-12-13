<?php
/**
 * This class is designed to check the CSFR Token to ensure there is not an
 * attack on the system.
 */
class Token {
	
	/**
	 * Executs the check for the token
	 */
	public function check($data) {
		
		$token_name = '';
		
		$token_value = '';
		if(!isset($data['csrf_name'])) {
			return false;
		}
		
		if(!isset($data['csrf_value'])) {
			return false;
		}
		
		$token_name = $data['csrf_name'];
		$token_value = $data['csrf_value'];
		
		$redis = new Redis();
		$redis -> connect(PVConfiguration::getConfiguration('redis') -> host, PVConfiguration::getConfiguration('redis') -> port);
		
		$stored_token = $redis -> get($token_name);
		
		if(!$stored_token) {
			return false;
		}
		
		return hash_equals($token_value, $stored_token);
		
	}
}
