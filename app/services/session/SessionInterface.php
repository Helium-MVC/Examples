<?php

namespace app\services\session;

/**
 * SessionInterface
 * 
 * The session interface is used to ensure all session handlers follow the same logic. When creating
 * a new session hander, please implement this interface and pass it into SessionService
 * for Dependency Injection.
 */
interface SessionInterface {
	
	public static function initializeSession($model, $write_to_cookie = true);
	
	public static function read($key);
	
	public static function write($key, $values);
	
	public static function getID();
	
	public static function refresh();
	
	public static function endSession();
	
	
}
