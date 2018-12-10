<?php
/**
 * Generate a CSFR token to validate forms.
 * 
 * The version below generates a token and then stores that token in Redis
 */
class CSRF extends He2Template {
	
	private static $_token = null;
	
	
	public function tokenInput() {
		$token_name = PVTools::generateRandomString().uniqid();
		$token_value = PVTools::generateRandomString();
		
		$redis = new Redis();
		$redis -> connect(PVConfiguration::getConfiguration('redis') -> host, PVConfiguration::getConfiguration('redis') -> port);
		$redis -> setex($token_name , 3600, $token_value); 
		
		return '<input type="hidden" name="fn_csrf_name" value="' . $token_name . '" ng-init="fn_csrf_name=\''. $token_name  .'\'"  /><input type="hidden" name="fn_csrf_value" value="' . $token_value . '" ng-init="fn_csrf_value=\''. $token_value  .'\'" />';
		
	}
	
	private function _generateToken() {
		if(!self::$_token) {
			Session::write('csrf_check_token', null);
			$token = PVTools::generateRandomString();
			self::$_token = $token;
			Session::write('csrf_check_token', $token);
			PVSession::writeSession('csrf_check_token', $token);
			
			
			
		}
	}
	
	public function token() {
		$this -> _generateToken();
		return self::$_token;
	}
	
	public function clear() {
		//$self::$_token = null;
		echo Session::read('csrf_check_token');
		//Session::write('csrf_token', null);
	}
	
}