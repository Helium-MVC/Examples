<?php
//Turn on error reporting
if(isset($_SERVER['ENV']) && $_SERVER['ENV'] == 'production'){
	ini_set('display_errors','Off');
} else {
	ini_set('display_errors','On');
}

error_reporting(E_ALL); 

$_SERVER['HTTP_HOST'] = null;

//Define Directory Seperator
define('DS', DIRECTORY_SEPARATOR);
//Define Prodigy View ROot
define('PV_ROOT', dirname(__DIR__) );
//Define Core Direcoty
define('PV_CORE', PV_ROOT.DS.'vendor'.DS.'prodigyview'.DS.'prodigyview'.DS);
//Define heliums root
define('HELIUM', PV_ROOT.DS.'vendor'.DS.'prodigyview'.DS.'helium'.DS );

include(PV_ROOT.DS.'vendor'.DS. 'autoload.php');
include(PV_CORE.DS.'_classLoader.php');

define ('SITE_PATH', dirname ( __FILE__ ).DS);


 /*** include the controller class ***/
 include HELIUM .  'controller.class.php';

 /*** include the registry class ***/
 include HELIUM .  'registry.class.php';

 /*** include the router class ***/
 include HELIUM . 'router.class.php';

 /*** include the template class ***/
 include HELIUM. 'template.class.php';
 
  /*** include the template class ***/
 include HELIUM .  'model.class.php';
 
   /*** include the template class ***/
 include HELIUM .  'app.class.php';
 
 include HELIUM .  'console.class.php';

$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

prodigyview\helium\HeliumConsole::addObserver('prodigyview\helium\HeliumConsole::init', 'read_closure', function() {
  
  //Load the site boostrap
  include SITE_PATH.'config/bootstrap.php';
   
}, array('type' => 'closure'));

prodigyview\helium\HeliumConsole::init();

exit();
 