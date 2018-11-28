<?php
/**
 * Navigation
 * 
 * This class is a template extention classes that is called in the view of the application. The purpose
 * of this class get information from the router and determine if this is the correct
 * page.
 * 
 * When using an MVC routes, we have the controllers and actions. Controllers are basically the classes
 * used in a page, the action are the function called within that controller.
 */
class Navigation {
	
	private $_controller = null;
	
	private $_action = null;
	
	/**
	 * getController
	 * 
	 * Gets the current controller the application is using
	 * 
	 * @return string
	 */
	public function getController() {
		
		if(!$this -> _controller) {
			$route = PVRouter::getRoute();
			$controller = $route['controller'];
			
			if(!$controller) {
				$controller = PVRouter::getRouteVariable('controller');
			}
			
			$this -> _controller = $controller;
		
		}
		
		
		return $this -> _controller;
	}
	
	/**
	 * getAction
	 * 
	 * Determines the current action in the route.
	 * 
	 * @retrun strig
	 */
	public function getAction() {
		
		if(!$this -> _action) {
			$route = PVRouter::getRoute();
			$action = $route['action'];
			
			if(!$action ) {
				$action = PVRouter::getRouteVariable('action');
			}
		
			$this -> _action = $action;
		}
		
		return $this -> _action;
	}
	
}
