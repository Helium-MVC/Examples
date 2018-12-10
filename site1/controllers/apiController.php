<?php

use app\models\basic\Users;
use app\models\basic\UserPasswords;
use app\models\basic\Posts;
use app\models\basic\ActionLogger;
use app\services\AuthenticationService;
use app\services\Session\SessionService;

include('baseController.php');

/**
 * Th API Route is a specialized route for AJAX calls from the frontend of the site. Security measures are up
 * to you.
 */
class apiController extends baseController {
	
	
	public function __construct($registry, $configurtion = array()) {
		parent::__construct($registry, $configurtion );
		
		//Create our own customer error handler to ensure a 200 in not returned
		/*set_error_handler(function($errno, $errstr, $errfile, $errline ) {
			$message = $errstr. ' in '. $errfile . ' at ' . $errline;
		   
			echo PVResponse::createResponse(400,   $message);
			xdebug_print_function_stack($message,XDEBUG_STACK_NO_DESC);
		   
			exit();
		});*/
		
	}
	
	public function index() : array  {
		
		
		return array();
	}
	
	/**
	 * The API route called for registering a user
	 */
	public function registerUser() : array {
		
		$model = new Users();
		
		$data = file_get_contents("php://input");
		$data = $this -> _formatInput($data);
		
		if($model -> create($data, array('validate_options' => array('display' => false, 'event' => 'create')))) {
			AuthenticationService::forceLogin($model -> email);
			$this -> _jsonResponse($model -> getIterator() -> getData());
		} else {
			$this -> _errorResponse($model);
		}
		
		exit();
	}
	
	/**
	 * The api route called for updating a user
	 */
	public function updateUser() : array {
		
		$model = Users::findOne(array(
			'conditions' => array('user_id' => $this -> registry -> route['id'])
		));
		
		$data = file_get_contents("php://input");
		$data = $this -> _formatInput($data);
		
		if($model && $model -> update($data, array('validate_options' => array('display' => false, 'event' => 'update')))) {
			$this -> _jsonResponse($model -> getIterator() -> getData());
		} else if(!$model) {
			echo PVResponse::createResponse(404, 'User Not Found' );
		} else {
			$this -> _errorResponse($model);
		}
		
		exit();
	}

	/*
	 * The api route called for updating a users email
	 */
	public function updateEmail() : array {
		
		$model = Users::findOne(array(
			'conditions' => array('user_id' => $this -> registry -> route['id'])
		));
		
		$data = file_get_contents("php://input");
		$data = $this -> _formatInput($data);
		
		$tmp_user = Users::findOne(array(
			'conditions' => array('email' => $data['email'] )
		));
				
		if(!$tmp_user || $tmp_user -> user_id = SessionService::read('user_id')) {
			if($model -> update(array('email' => $data['email']), array('validate_options' => array('display' => false, 'event' => 'update')))){
				$this -> _jsonResponse($model -> getIterator() -> getData());
			} else {
				$this -> _errorResponse($model);
			}
		} else {
			echo PVResponse::createResponse(404, 'Another user has that email');
		}
		
		exit();
	}
	
	/**
	 * API route called for updated a users password
	 */
	public function updatePassword() : array {
		
		$model = UserPasswords::findOne(array(
			'conditions' => array('user_id' => $this -> registry -> route['id'])
		));
		
		$data = file_get_contents("php://input");
		$data = $this -> _formatInput($data);
		
		if($model && $model -> update($data, array('validate_options' => array('display' => false, 'event' => 'update')))) {
			$this -> _jsonResponse($model -> getIterator() -> getData());
		} else if(!$model) {
			echo PVResponse::createResponse(404, 'User Not Found' );
		} else {
			$this -> _errorResponse($model);
		}
		
		exit();
	}
	
	/**
	 * API Route called for retrieving a user
	 */
	public function findUser() : array {
		
		$model = Users::findOne(array(
			'conditions' => array('user_id' => $this -> registry -> route['id'])
		));
		
		if($model) {
			$this -> _jsonResponse($model -> getIterator() -> getData());
		} else {
			echo PVResponse::createResponse(404, 'User Not Found' );
		}
		
		exit();
	}
	
	/**
	 * API route called for logging in
	 */
	public function loginUser() {
		
		$data = file_get_contents("php://input");
		$data = $this -> _formatInput($data);
		
		if($data && AuthenticationService::authenticate($data['email'], $data['password'])) {
			$model = Users::findOne(array(
				'conditions' => array('email' => $data['email'])
			));
			
			$this -> _jsonResponse($model -> getIterator() -> getData());
		} else {
			echo PVResponse::createResponse(404, 'Invalid Username/Password' );
		}
		
		exit();
	}
	
	/**
	 * API route called for creating a post
	 */
	public function createPost() : array {
		
		$model = new Posts();
		
		$data = file_get_contents("php://input");
		$data = $this -> _formatInput($data);
		
		if($model -> create($data, array('validate_options' => array('display' => false, 'event' => 'create')))) {
			$this -> _jsonResponse($model -> getIterator() -> getData());
		} else {
			$this -> _errorResponse($model);
		}
		
		exit();
	}
	
	/**
	 * API called to update a post
	 */
	public function updatePost() : array {
		
		$model = Posts::findOne(array(
			'conditions' => array('post_id' => $this -> registry -> route['id'])
		));
		
		$data = file_get_contents("php://input");
		$data = $this -> _formatInput($data);
		
		if($model && $model -> update($data, array('validate_options' => array('display' => false, 'event' => 'update')))) {
			$this -> _jsonResponse($model -> getIterator() -> getData());
		} else if(!$model) {
			echo PVResponse::createResponse(404, 'Post Not Found' );
		} else {
			$this -> _errorResponse($model);
		}
		
		exit();
	}
	
	/**
	 * API route called for finding a single post
	 */
	public function findPost() : array {
		
		$model = Posts::findOne(array(
			'conditions' => array('post_id' => $this -> registry -> route['id'])
		));
		
		if($model) {
			$this -> _jsonResponse($model -> getIterator() -> getData());
		} else {
			echo PVResponse::createResponse(404, 'Post Not Found' );
		}
		
		exit();
	}
	
	/**
	 * Retrieves the current session information
	 */
	public function session() {
		
		$this -> _jsonResponse(array(
			'user_id'=> SessionService::read('user_id'),
			'is_loggedin'=> SessionService::read('is_loggedin'),
			'session_id' => SessionService::getID()
		));
		
		exit();
		
	}
	
	/**
	 * Formats the input to be ingested by the mosted
	 */
	protected function _formatInput($data) {
		if(is_string($data)){
			$data = json_decode($data,true);
		}
		
		return $data;
	}
	
	/**
	 * Creates a JSON Response
	 */
	protected function _jsonResponse($data, $convert = true) {
		header('Content-type: application/json; charset=utf-8');
		
		$json = json_encode($data, JSON_UNESCAPED_UNICODE);
		
		if (extension_loaded("zlib") && (ini_get("output_handler") != "ob_gzhandler")) {
    			ini_set("zlib.output_compression", 1);
		}
		
		if( ! isset($_GET['callback'])){
   			exit($json);
		}


		if($this -> _isValidCallback($_GET['callback']))
		    exit("{$_GET['callback']}($json)");

		header('status: 400 Bad Request', true, 400);
	}

	//Creates an Error response
	protected function _errorResponse($model) {
		
		$errors = $model -> getValidationErrors();
		$string = '';

		foreach ($errors as $error) {
			foreach($error as $suberror) {
				$string .= '<div class="alert alert-danger">' . $suberror . '</div>';
			}
		}
		
		echo PVResponse::createResponse(425, $string  );
	}
	
	
	
}
