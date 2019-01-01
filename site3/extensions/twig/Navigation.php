<?php
namespace site3\extensions\twig;

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
class Navigation extends Twig_Extension implements Twig_ExtensionInterface {
	
	private $_controller = null;
	
	private $_action = null;
	
	public function getFunctions() {
        return array(
            new Twig_SimpleFunction('navigation_get_controller', array($this, 'getController')),
            new Twig_SimpleFunction('navigation_get_action', array($this, 'getAction')),         
        );  
    }
	
	/**
	 * getController
	 * 
	 * Gets the current controller the application is using
	 * 
	 * @return string
	 */
	public function getController() {
		
		if(!$this -> _controller) {
			$route = Router::getRoute();
			$controller = $route['controller'];
			
			if(!$controller) {
				$controller = Router::getRouteVariable('controller');
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
			$route = Router::getRoute();
			$action = $route['action'];
			
			if(!$action ) {
				$action = Router::getRouteVariable('action');
			}
		
			$this -> _action = $action;
		}
		
		return $this -> _action;
	}
	
}
