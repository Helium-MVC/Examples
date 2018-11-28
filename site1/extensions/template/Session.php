<?php
use app\services\session\SessionService;

/**
 * Session
 * 
 * Adds the ability to access variables from the current session from within a template;
 */
class Session {
	
	public function get($key) {
		return SessionService::read($key);
	}
}
