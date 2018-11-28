<?php
use app\services\LoggingService;
use app\factories\FirebaseFactory;
use prodigyview\helium\He2Controller;

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

/**
 * baseController
 * 
 * This is an controller that should never be accessed by the user. The controller should
 * be extended to other controllers for universal functions. 
 */
 
class baseController extends He2Controller {
	
	protected $_apiRoutes = array();
	
	protected $_firebase = null;
	
	protected $_factory = null;
	
	/**
	 * In the constructor we are going to assign the factory and the firebase
	 * connection. Please setup your Firebase preferences in the app/config/config.php
	 */
	public function __construct($registry, $configurtion = array()) {
		parent::__construct($registry, $configurtion );
		
		//Setup the Firebase Connection
		$serviceAccount = ServiceAccount::fromJsonFile(PVConfiguration::getConfiguration('firebase') -> jsonFile);
		$firebase = (new Factory)->withServiceAccount($serviceAccount)->create();
		$this -> _firebase = $firebase->getDatabase();
		
		//Call the Firebase factory created in app/factories folder
		$this-> _factory = new FirebaseFactory($this -> _firebase);
		
	}
	
	public function index() {
		exit();
	}
	
	/**
	 * The function to be called when the page error 404 outs. Normal causes of 404 are an object not existing
	 * or the object being registered as deleted.
	 * 
	 * @param array $data Data to be stored in the state of the log
	 * @param string $message Additonal information about the event.
	 * 
	 * @return void
	 */
	public function error404(array $data = array(), string $message = '') : array {
		header("HTTP/1.0 404 Not Found");
		
		//Changes the view to 'pages' with error404.html.php
		$this -> _renderView(array('view' => 'pages', 'prefix' => 'error404'));
		
		$controller = get_class($this);
		$action = $this -> _getStateRoute();
		$message = $this -> _formatLogMessage($message);
		
		LoggingService::logController($controller, $action, '404'.$message, $data);
		
		return array();
	}
	
	/**
	 * To be called when a page is illegally access. This normal happens when a user
	 * is not the owner of a resrouce.
	 * 
	 * @param array $data Data to be stored in the state of the log
	 * @param string $message Additonal information about the event.
	 * 
	 * @return void
	 */
	public function accessdenied(array $data = array(), string $message = '') : array {
		
		//Changes the view to 'pages' with accessdenined.html.php
		$this -> _renderView(array('view' => 'pages', 'prefix' => 'accessdenied'));
		
		$controller = get_class($this);
		$action = $this -> _getStateRoute();
		$message = $this -> _formatLogMessage($message);
		
		LoggingService::logController($controller, $action, 'Illegal Access'.$message, $data);
		
		return array();
	}
	
	/**
	 * Gets the state that is passed into the log. The state
	 * is the route that the user is currently on.
	 * 
	 * @return string
	 */
	private function _getStateRoute() : string {
		
		$route = PVRouter::getRoute();
		$action = $route['action'];
		
		if(!$action ) {
			$action = PVRouter::getRouteVariable('action');
		}
		
		return $action;
	}
	
	/**
	 * Formats a log message if it is not empty to have extra data.
	 * 
	 * @param string $message The message
	 * 
	 * @return $string
	 */
	private function _formatLogMessage(string $message) : string {
		
		if(trim($message)) {
			$message = ' : ' . $message;
		}
		
		return $message;
	}
	
}
