<?php
namespace app\services\session;

use prodigyview\system\Session;

class DBSessionService implements SessionInterface {
	
	//The session class
	private static $_session;
	
	private static $_sessionToken = null;
	
	private static $_model = null;
	
	/**
	 * Initalizes a session to be used by the user. Every user requires a session to access the api
	 * and track their activity
	 */
	public static function initializeSession($model, $write_to_cookie = true) {
		self::$_model = $model;
		
		//Ensure that a session doesn't already exist
		if(!self::$_session) {
			
			//Find a current session
			$session_id = self::getID();
			
			//If a session is found
			if($session_id){
				
				$session = self::_findSession($session_id);
				
				if(!$session) {
					self::createSession();
				} else {
					
					if(session_id() != ''){
						session_id($session -> session_id);	
					}	
					
					self::$_session = $session;
					
				}
			} else {
				self::createSession();
			}
			
		}//end !self::$_session
		
		
		return get_class();
	}//end initializeSession

	/**
	 * Finds a session that is currently in the session database.
	 * 
	 * @param string $session_id The id of the session to fin
	 * 
	 * @return mixed Returns the session
	 */
	protected static function _findSession($session_id) {
		
		$model = self::$_model;		
			
		$session = $model::findOne(array(
			'conditions' => array('session_id' => $session_id)
		));
		
		if(!$session) {
			return false;
		} else {
			return $session;
		}
	}

	/**
	 * Creates a new session that will be assignto the user
	 * 
	 * @param string $session_id The id of a current session
	 * @param boolean $write_session Writes the session to cookie and local server stroage
	 * 
	 * @return $session Returns a session object
	 */
	public static function createSession($session_id = null, $write_session = true) {
		
		if(!self::$_session) {
			
			//Attempt to retrieve the session id from the session
			if(!$session_id) {
				$session_id = Session::readSession('session_id');
			}
			
			if($session_id) {
				
				self::$_session = Session::findOne(array(
					'conditions' => array('sessiond_id' => $session_id)
				));
			}

			if(!self::$_session) {
				
				$session = new self::$_model();
				
				$result = $session -> create(array());
				
				self::$_session = $session;
				
				if($write_session) {
				
					Session::writeCookie('session_id', (string)$session -> session_id );
					Session::writeSession('session_id', (string)$session -> session_id);
					
					session_id((string)$session -> session_id );
				}
				
				
				//self::$_session = $session;
			} else {
				
				$session = Session::findOne(array(
					'conditions' => array('sessiond_id' => $session_id)
				));
				
				if(!$session)  {
					$session = new Session();
					if($session -> create(array())) {
						Session::writeCookie('session_id', (string)$session -> session_id );
						Session::writeSession('session_id', (string)$session -> session_id );
					} else {
						echo 'No session';
						exit();
					}
				}
				
				self::$_session = $session;
				
				if(self::read('user_id')) {
					$account = Users::findOne(array(
						'conditions' => array('user_id' =>self::read('user_id') )
					));
					
					if(!$account) {
						self::endSession();
						Router::redirect('/');
					}
				}
			}
		}
		
		//session_write_close();
		return self::$_session;
		
	}
	
	/**
	 * Will reload the session with new data
	 */
	public static function refresh() {
		$session_id = Session::readCookie('session_id');
			
		if(!$session_id) {
			$session_id = Session::readSession('session_id');
		}
		
		if($session_id) {
			self::$_session = $session;
		}
	}
	

	/**
	 * Completely destroy the session and effectively logs the user out
	 */
	public static function endSession() {		
		
		//sets the session to inactive
		self::write('is_loggedin', 0);
		
		Session::deleteCookie('session_id');
		Session::deleteSession('session_id');

		setcookie('session_id', NULL, time() - 4800);
	    session_unset();
	    session_destroy();
	    session_write_close();
	    setcookie(session_name(),'',0,'/');
		
		if(session_id()) {
	    		session_regenerate_id(true);
		}
		
		if(isset($_SESSION) && session_id()) {
    			session_destroy();
		}
	}
	
	/**
	 * Gets a value associated with the current session model
	 * 
	 * @param string $key
	 * 
	 * @return $mixed
	 */
	public static function read($key) {
		
		if(!self::$_session){
			return null;
		}
		
		return self::$_session -> $key;
	}
	
	/**
	 * Writes a value to the current session and saves it in
	 * the DB.
	 * 
	 * @param string $key The key used to access the session
	 * @param mixed $value The value to be stored in the session db
	 */
	public static function write($key, $values = null) {
		
		self::$_session -> update(array($key => $values));
	}
	
	
	/**
	 * Returns the Session token 
	 * 
	 * @return session_id
	 */
	public static function getID() {
		$session_id = Session::readCookie('session_id');
		
		if(!$session_id){
			$session_id = Session::readSession('session_id');
		}
		
		return $session_id;
	}
	
	/**
	 * updateUserSessions
	 * 
	 * So keep the user data in sync, when a user updates there information, sessions should be
	 * updated as well. This function will update all the user sessions.
	 * 
	 * @param string user_id The uuid of the user to be updated
	 * 
	 */
	public static function updateUserSessions($user_id) {
		
		$user = Users::findOne(array(
			'conditions' => array('user_id' => $user_id)
		));
		
		if($user) {
			$model = self::$_model;
			
			$sessions = $model::findAll(array(
				'conditions' => array('user_id' => $user_id, 'is_loggedin' => 1)
			), array('results' => 'model'));
			
			foreach($sessions as $session) {
				$session -> update(array('account' => $user -> getIterator() -> getData()));
			}
		}
		
	}
	
}
