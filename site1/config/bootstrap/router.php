<?php
/**
 * The router allows customization of the uri in Helium. To effectively utilize, make sure the .htaccess file is in place
 * if you are using Apache or have the path following enabled in Nginx.
 */

 
/*
 * Initialize the router and configure how the route is parsed. Add adapters, filters
 * and observers to the router. Add routes to the router.
 */
PVRouter::init(array());

//Basic Router, mimics that the basics of an MVC
PVRouter::addRouteRule(array('rule'=>'/:controller'));
PVRouter::addRouteRule(array('rule'=>'/:controller/:action'));
PVRouter::addRouteRule(array('rule'=>'/:controller/:action/:id'));

//Optional Rule Example - Shorten urls by only requiring controller and id
//PVRouter::addRouteRule(array('rule'=>'/:controller/:id', 'route' => array('action' => 'view')));

//Optional Rule Example - Have UUID be routed to a view to shorten the users
//PVRouter::addRouteRule(array('rule'=>'/:controller/[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}', 'route' => array('action' => 'view')));

//Optional Rule Example - For SEO have text in the url
//PVRouter::addRouteRule(array('rule'=>'/:controller/:action/:text/:id'));

PVRouter::addRouteRule(array('rule'=>'', 'route'=>array('controller'=>'index', 'action'=>'index')));
PVRouter::addRouteRule(array('rule'=>'/', 'route'=>array('controller'=>'index', 'action'=>'index')));

//Custom Routes
PVRouter::addRouteRule(array('rule'=>'/register', 'route'=>array('controller'=>'users', 'action'=>'register')));
PVRouter::addRouteRule(array('rule'=>'/login', 'route'=>array('controller'=>'users', 'action'=>'login')));
PVRouter::addRouteRule(array('rule'=>'/logout', 'route'=>array('controller'=>'users', 'action'=>'logout')));
PVRouter::addRouteRule(array('rule'=>'/profile/:id', 'route'=>array('controller'=>'users', 'action'=>'profile')));
PVRouter::addRouteRule(array('rule'=>'/contact', 'route'=>array('controller'=>'index', 'action'=>'contact')));









