<?php
use app\services\session\SessionService;

/**
 * Generate a CSFR token to validate forms.
 * 
 * In the token validation version below, we are stored the CSFR in Session
 * related to the current user. We are prefixing the token so it does not conflict
 * with variables inserted the session.
 * 
 * We also allowing the developer to se there own key for accessesing the session.
 */
class CSRF  {
	
	/**
	 * Generates as CSFR token and returns HTML to be injected
	 * into the form.
	 */
	public function getCSRFTokenInput($key) {
		
		$token_value = bin2hex(random_bytes(32));
		
		SessionService::write('csrf_'.$key, $token_value);
		
		return $token_value;
		
	}
	
}