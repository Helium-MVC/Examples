<?php
use app\services\session\SessionService;

/**
 * Access control keeps users from accessing parts of the sites that requires credentials. Per the ProdigyView Toolkit,
 * we can modify parts of the framework actions through adapters and observers.
 * 
 * In the example below, we are modifying the Router after it has finished processing the users request. We
 * are then going to check the current route and decide if the user has access.
 */

PVRouter::addObserver('PVRouter::setRoute', 'access_closure', function($final_route, $route_options){
	
	$allowed_routes = array(
		'',
		'/',
		'/posts',
		'/posts/view',
		'/posts/rss',
		'/users/profile',
		'/users/activate',
		'/register',
		'/login',
		'/api/login',
		'/api/register',
		'/index/contact',
		'/contact'
	);
	
	$non_recorded_routes = array(
		'/forgotpassword',
		'/resetpassword',
		'/login',
		'/register',	
	);
	
	//Route Variables
	$controller = '';
	$action = '';
	
	$current_route ='';
	
	//Get the route variables based on the PVRouter
	if(isset($final_route['route']) && !empty($final_route['route'])) {
		$current_route = '/' . $final_route['route']['controller'];
		$controller = $final_route['route']['controller'];
		if(isset($final_route['route']['action'])){
			$current_route .= '/'.$final_route['route']['action'];
			$action = $final_route['route']['action'];
		}
		
	} else if (isset($final_route['controller'])) {
		
		$current_route = '/' . $final_route['controller'];
		$controller = $final_route['controller'];
		if(isset($final_route['action'])) {
			$current_route .= '/'.$final_route['action'];
			$action = $final_route['controller'];
		}
	}
	
	$redirect = '';
	
	//Store the previous url
	if(!in_array($current_route, $non_recorded_routes)) {
		SessionService::write('previous_url', $current_route);
		$redirect = '?redirect=' . $current_route;
	}
	
	//If the user is not logged andin an open route
	if(!SessionService::read('is_loggedin') && !in_array($current_route, $allowed_routes)) {
		PVTemplate::errorMessage('The part of the site is restricted to members. Please login.');
		PVRouter::redirect('/login');
	}
	
}, array('type' => 'closure'));
