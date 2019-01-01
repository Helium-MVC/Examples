<?php

namespace app\services\session;

use prodigyview\system\Session;

class SessionService implements SessionInterface {
	
	private static $_hander = null;
	
	public static function initializeSession($handler, $write_to_cookie = true){
		
		 self::$_hander = $handler;
	}
	
	public static function read($key) {
		$handler = self::$_hander;
		return $handler::read($key);
	}
	
	public static function write($key, $values = '') {
		$handler = self::$_hander;
		return $handler::write($key, $values);
	}
	
	public static function getID() {
		$handler = self::$_hander;
		return $handler::getID();
	}
	
	public static function refresh() {
		$handler = self::$_hander;
		$handler::refresh();
	}
	
	public static function endSession() {
		$handler = self::$_hander;
		$handler::endSession();
	}
	
	
}
