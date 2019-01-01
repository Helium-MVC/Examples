<?php

use prodigyview\helium\He2App;

use prodigyview\system\Configuration;

//Turn on error reporting
if(isset($_SERVER['ENV']) && $_SERVER['ENV'] == 'production'){
	ini_set('display_errors','Off');
} else {
	ini_set('display_errors','On');
}

error_reporting(E_ALL  & ~E_DEPRECATED ); 

//Define Directory Seperator
define('DS', DIRECTORY_SEPARATOR);
//Define Prodigy View Root
define('PV_ROOT', dirname(dirname(__DIR__)) );
//Set the path for this project
define ('SITE_PATH', dirname(dirname ( __FILE__ )).DS);
//Set the location of the public folder
define('PUBLIC_HTML', SITE_PATH.DS.'public_html'.DS);
//Set the location of  local libraries
define('PV_LIBRARIES', SITE_PATH.DS.'libraries'.DS);
//Define Template Directory
define('PV_TEMPLATES', SITE_PATH.DS.'templates'.DS);
//Set the temp directory
define('PV_TMP', PUBLIC_HTML.'tmp'.DS);
//Define heliums root
define('HELIUM', PV_ROOT.DS.'vendor'.DS.'prodigyview'.DS.'helium'.DS );

include(PV_ROOT.DS.'vendor'.DS. 'autoload.php');
 
 

prodigyview\helium\He2App::addObserver('prodigyview\helium\He2App::_initRegistry', 'read_closure', function() {
  
  //Load the site boostrap
  include SITE_PATH.'config/bootstrap.php';
   
   $redis = new \Redis();
   $redis->connect(Configuration::getConfiguration('redis') -> host, Configuration::getConfiguration('redis') -> port);
   
  //Set the model and service used for session handling
  app\services\session\SessionService::initializeSession(app\services\session\RedisSessionService::initializeSession($redis));
  
}, array('type' => 'closure'));


prodigyview\helium\He2App::init();
 