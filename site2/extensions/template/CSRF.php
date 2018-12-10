<?php
/**
 * Generate a CSFR token to validate forms.
 * 
 * The version below generates a token and then stores that token in Redis
 */
class CSRF  {
	
	/**
	 * Generates as CSFR token and returns HTML to be injected
	 * into the form.
	 */
	public function getCSRFTokenInput() {
		$token_name = PVTools::generateRandomString().uniqid();
		$token_value = bin2hex(random_bytes(32));
		
		$redis = new Redis();
		$redis -> connect(PVConfiguration::getConfiguration('redis') -> host, PVConfiguration::getConfiguration('redis') -> port);
		$redis -> setex($token_name , 3600, $token_value); 
		
		return '
			<input type="hidden" name="csrf_name" value="' . $token_name . '" ng-init="data.csrf_name=\''. $token_name .'\'"  />
			<input type="hidden" name="csrf_value" value="' . $token_value . '" ng-init="data.csrf_value=\''. $token_value .'\'"  />
		';
		
	}
	
}