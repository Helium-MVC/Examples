<?php

namespace app\factories;

use app\services\session\SessionService;
use app\services\storage\CloudStorageService;

/**
 * SeviceStorage
 * 
 * The StorageService class used the Factory Pattern that is designed to get various services used within your application.
 * 
 * Below we are going to go into two use cases, one statically defining the services, the other creating
 * a repositoray
 * 
 * Example Usage:
 * 
 * $session = ServiceFactory::getSessionService();
 * $session -> read('user_id');
 * 
 * or
 * 
 * // Add during the beginning of your application, maybe in the bootstrap
 * ServiceFactory::add('session', 'app\services\session\SessionService')
 * 
 * //Call later in your application, such as in a controller
 * $session = ServiceFactory::get('session');
 */
class ServiceFactory {
	
	//A repository of services added dynamically
	private static $_services = array();
	
	/*
	 * Dynamically add a service to the repository
	 * 
	 * @param string $key The name to reference the service by
	 * @param string $namespace The service namespace to call
	 * 
	 * @return void
	 */
	public static function add($key, $namespace) : void {
		static::$_services[$key] = $namespace;
	}
	
	/**
	 * Retrieves the called service by name space
	 */
	public static function get($key) {
		return new static::$_services[$key]();
	}
	
	public static function getSessionService() {
		
		return new SessionService;
		
	}
	
	public static function getStorageService() {
		
		return new CloudStorageService;
	}
	
}
