<?php

namespace app\services\session;

/**
 * RedisSessionService
 * 
 * This class utilizes Redis for storing and retrieving session data. Most notably, is use
 * the Redis hash with hGet and hSet
 */
class RedisSessionService implements SessionInterface {
	
	//The session class
	private static $_redis;
	
	/**
	 * Initalizes the redis storage database
	 * 
	 * @param Redis $redis An instance of a redis object
	 * @param boolean $write Writes it the session to a cookie
	 * 
	 * @return void
	 */
	public static function initializeSession($redis, $write_to_cookie = true) {
		self::$_redis = $redis;
		
		//Get the current session id
		$id = self::getID();
		
		//Execute if no session id
		if(!$id) {
			//Get Session id
			$session_id = session_id();
			
			//Generate a new session id if none
			if(!$session_id) {
				$session_id = \PVTools::generateRandomString(20);
				session_id( session_id);
				session_start();
			}
			
			//Write session to cookie and session
			\PVSession::writeCookie('session_id', $session_id);
			\PVSession::writeSession('session_id', $session_id);
			
			//Create API Token
			self::write('api_token', \PVSecurity::generateToken(20));
		}
		
		
		return get_class();
	}
	
	/**
	 * Reads the data for the current session based on the key
	 * 
	 * @param string $key The key to reference the data
	 * 
	 * @return mixed
	 */
	public static function read($key) {
		$id = static::getID();
		
		return self::$_redis->hGet($id, $key);
	}
	
	/**
	 * Writes the data to the session db
	 * 
	 * @param string $key
	 * @param string $value
	 * 
	 * @return void
	 */
	public static function write($key, $values) {
		$id = static::getID();
		
		self::$_redis->hSet($id , $key, $values);
	}
	
	/**
	 * Gets the id of the current session.
	 * 
	 * @return string $id
	 */
	public static function getID() {
		$session_id = \PVSession::readCookie('session_id');
		
		if(!$session_id){
			$session_id = \PVSession::readSession('session_id');
		}
		
		return $session_id;
	}
	
	public static function refresh() {
		
	}
	
	/**
	 * Ends the session by deleting the current session
	 * id from the db
	 */
	public static function endSession(){
		$id = static::getID();
		self::$_redis->delete($id);
	}
	
}
