<?php

use app\facades\ValidationFacade;
use app\facades\FirebaseModelFacade;
use app\factories\ServiceFactory;
use prodigyview\helium\He2Controller;

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

use prodigyview\template\Template;
use prodigyview\network\Router;
use prodigyview\network\Request;
use prodigyview\network\Response;
use prodigyview\system\Configuration;

/**
 * baseController
 * 
 * This is an controller that should never be accessed by the user. The controller should
 * be extended to other controllers for universal functions. 
 */
 
class baseController extends He2Controller {
	
	protected $_apiRoutes = array();
	
	protected $_firebase = null;
	
	protected $_models = null;
	
	/**
	 * In the constructor we are going to assign the factory and the firebase
	 * connection. Please setup your Firebase preferences in the app/config/config.php
	 */
	public function __construct($registry, $configurtion = array()) {
		parent::__construct($registry, $configurtion );
		
		//Setup the Firebase Connection
		$serviceAccount = ServiceAccount::fromJsonFile(Configuration::getConfiguration('firebase') -> jsonFile);
		$firebase = (new Factory)->withServiceAccount($serviceAccount)->create();
		$this -> _firebase = $firebase->getDatabase();
		$auth = $firebase->getAuth();
		//Call the FirebaseModelFacade created in app/facades folder
		$this-> _models = new FirebaseModelFacade($this -> _firebase, $auth);
		
	}
	
	/**
	 * Validates an action and its data according to the rules in the ValidationFacade
	 * 
	 * @param string $type The type of validation, user, post or contact
	 * @param string $action. Normally either create or update, but custom validation rules can be added
	 * @param array $data And array of values to validate against
	 * @param boolean $display If set to true, will write the errors out to the template
	 * 
	 * @return boolean Etierh returns true or false
	 */
	public function validate($type, $action, $data, $display = true) {
		
		$validate = new ValidationFacade();
		
		if($type === 'user') {
			return $validate -> checkUser($action, $data, $display);
		} else if($type === 'post') {
			return $validate -> checkPost($action, $data, $display);
		} else if($type === 'contact') {
			return $validate -> checkContact($action, $data, $display);
		}
		
		return false;
		
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
		
		$data = array(
			'controller' => get_class($this),
			'action' => $this -> _getStateRoute(),
			'message' => $this -> _formatLogMessage($message)
		);
		
		$loggly_key = Configuration::getConfiguration('loggly') -> key;
		
		//PVCommunicator sends CURL call to loggly
		$communicator = new PVCommunicator();
		$result = $communicator -> send('POST', 'http://logs-01.loggly.com/inputs/'.$loggly_key.'/tag/http/', $data);
		
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
		
		$data = array(
			'controller' => get_class($this),
			'action' => $this -> _getStateRoute(),
			'message' => $this -> _formatLogMessage($message)
		);
		
		$loggly_key = Configuration::getConfiguration('loggly') -> key;
		
		//PVCommunicator sends CURL call to loggly
		$communicator = new PVCommunicator();
		$result = $communicator -> send('POST', 'http://logs-01.loggly.com/inputs/'.$loggly_key.'/tag/http/', $data);
		
		return array();
	}
	
	/**
	 * Gets the state that is passed into the log. The state
	 * is the route that the user is currently on.
	 * 
	 * @return string
	 */
	private function _getStateRoute() : string {
		
		$route = Router::getRoute();
		$action = (isset($route['action'])) ? $route['action'] : '';
		
		if(!$action ) {
			$action = (Router::getRouteVariable('action')) ?: '';
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
