<?php
/**
 * AuthenticationService
 * 
 * Handles authenticating a user in the system and also assigns
 * initial session information.
 */
namespace app\services;

use app\services\LoggingService;
use app\services\session\SessionService;

class AuthenticationService {
	
	//The model used to peform the authentication
	private static $_authenticationModel;
	
	
	/**
	 * Set the model used to validate with when finding the users
	 * information.
	 * 
	 * @param mixed $authenticationModel A string or callable object that will be referenced in the class
	 * 
	 * @return void
	 */
	public static function init($authenticationModel) : void {
		self::$_authenticationModel = $authenticationModel;
	}
	
   /**
	 * Will authenticate the user based upon the credentials passed
	 * 
	 * @param string $email The emaill address of the user logging in
	 * @param string $password The password in plan text, the function will hash the password accordingly
	 * 
	 * @return boolean Returns true if loggedin, otherwise false
	 */
	public static function authenticate(string $email, string $password, bool $store_session = true) : bool {
		
		$model = self::$_authenticationModel;
		
		$user = $model::findOne(array(
			'conditions' => array('email' => strtolower(trim($email))),
			'join' => array('password')
		));
		
		if(!$user) {
			LoggingService::logsServiceAction(new AuthenticationService(), 'Login Failed/User Not Found', array('email' => $email));
			
			SessionService::write('failed_login_attempts', SessionService::read('failed_login_attempts') +1);
			
			return false;
		}
		
		$logged_in = password_verify($password, $user -> user_password);
		
		if($logged_in) {
				
			LoggingService::logsServiceAction(new AuthenticationService(), 'Successful Login', array('email' => $email));
			
			if($store_session) {
				self::_setSessionData($email);
			}
			
			SessionService::write('failed_login_attempts', 0);
			
		} else if($email) {
			LoggingService::logsServiceAction(new AuthenticationService(), 'Login Failed/Incorrect User Password', array('email' => $email));
			
			SessionService::write('failed_login_attempts', SessionService::read('failed_login_attempts') +1);
		}
		
		return $logged_in;
    }
    
    /**
	 * Will force a user's login and does not require an authneticated password
	 * 
	 * @param string $email The email address that will be used to login
	 * 
	 * @return void
	 */
	public static function forceLogin(string $email) : void {
		self::_setSessionData($email);
	}
	
	/**
	 * Writes the user information to a session cache to be retrieved
	 * 
	 * @param string $email The email address
	 * 
	 * @return void
	 */
	protected static function _setSessionData(string $email) : void {
		
		$model = self::$_authenticationModel;
		
		SessionService::write('is_loggedin', 1);
		
		$user = $model::findOne(array(
			'conditions' => array('email' => strtolower(trim($email)) ),
		));
		
		if($user){
			foreach($user -> getIterator() -> getData() as $key => $value) {
				SessionService::write($key, $value);
			}
		}
	}
}