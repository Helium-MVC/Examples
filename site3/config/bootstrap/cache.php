<?php
/**
 * Important Explanation
 * 
 * All the models in Helium use PVCache when the caching is enabled. PVCache by default
 * used the file system - which has limitations.
 * 
 * For this basic site, we are just goign to stick with file cache.
 */

PVCache::init(array());

/*
 * Specailized Controller Adapter
 * 
 * Below we are going to use Helium's implemented adapter to rewrite the function in He2Router executeControllerAction.
 * In short, we are going to cache the controller output to file, and call cached output instead of
 * runing through the controllers functions.
 */
 
 prodigyview\helium\He2Router::addAdapter('prodigyview\helium\He2Router', 'executeControllerAction', function($controller, $action) {
 	
	//Create a cache key from the controller and action
	$cache_name = md5(get_class($controller).''. $action.''.PVRouter::getRouteVariables()[0]);
	
	//Create a new cache if cache does not exist
	if(PVCache::hasExpired($cache_name)) {
		//Call the controller and get the data
		$data = $controller -> $action();
		
		//Check if redirect was sent
		if($data instanceof prodigyview\helium\Redirect) {
			$data -> executeRedirect();
		}else if(isset($data['disable_cache'])) {
			//Returned if cache is distabled
			return $data;
		} else {
			//Write the data to a cache file
			PVCache::writeCache($cache_name, $data);
		}
		
		return $data;
	} else {
		//Return cached data
		//Never has to call the controller
		return PVCache::readCache($cache_name);
	}
	
 }, array('type' => 'closure'));
