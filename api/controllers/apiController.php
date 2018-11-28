<?php
use prodigyview\helium\He2Controller;

class apiController extends He2Controller {
	
	protected $_model = null;
	
	protected $_fields = '*';
	
	protected $_id = null;
	
	public function __construct($reg, $configuration = array()) {
		
		header('P3P: CP="NOI ADM DEV PSAi NAV OUR STP IND DEM HONK"');
		
		$route = PVRouter::getRoute();
		$controller = (isset($route['controller'])) ? $route['controller'] : null;
		
		if(!$controller) {
			$controller = PVRouter::getRouteVariable('controller');
		}
		
		$route = PVRouter::getRoute();
		$action = (isset($route['action'])) ? $route['action'] : null;
		
		if(!$action ) {
			$action = PVRouter::getRouteVariable('action');
		}
		
		$current_route = '/'.$controller .'/' . $action;

		$sites = (array)PVConfiguration::getConfiguration('adbience_sites');
		
		
		if( isset($_SERVER['HTTP_X_FOWARDED_HOST']) && !empty($_SERVER['HTTP_X_FOWARDED_HOST'])) {
	      $origin = $_SERVER['HTTP_X_FOWARDED_HOST'];
	    } else if( isset($_SERVER['HTTP_X_FORWARDED_HOST']) && !empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
	      $origin = $_SERVER['HTTP_X_FORWARDED_HOST'];
	    } else {
	      $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
	    }
		
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			
	        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']) && ($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'POST' || $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'DELETE' ||  $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'PUT' )) {
	                 header('Access-Control-Allow-Origin: ' . $origin);
	                 header("Access-Control-Allow-Credentials: true"); 
	                 header('Access-Control-Allow-Headers:  *,X-Requested-With,Content-Type');
	                 header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT'); // http://stackoverflow.com/a/7605119/578667
	                 header('Access-Control-Max-Age: 86400'); 
	         } 
	      echo PVResponse::createResponse(200, 'Successful Connection');
	      exit();
	    }
    
		header('Access-Control-Allow-Origin: '. $origin );
		header('Access-Control-Allow-Credentials: true' );
		header('Access-Control-Request-Method: *');
		header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: *,x-requested-with,Content-Type');
		//header('X-Frame-Options: SAMEORIGIN');
		//header('X-Frame-Options: DENY');
		
		return parent::__construct($reg, $configuration);
	}

	protected function _jsonResponse($data, $convert = true) {
		header('Content-type: application/json; charset=utf-8');
		if($convert) {
			$this -> utf8_encode_deep($data);
		}
		$json = json_encode($data, JSON_UNESCAPED_UNICODE);
		
		if (extension_loaded("zlib") && (ini_get("output_handler") != "ob_gzhandler")) {
    		ini_set("zlib.output_compression", 1);
		}
		
		if( ! isset($_GET['callback']))
   		 exit($json);


		if($this -> _isValidCallback($_GET['callback']))
		    exit("{$_GET['callback']}($json)");

		header('status: 400 Bad Request', true, 400);
	}

	protected function _isValidCallback($subject) {
		$identifier_syntax = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';

		$reserved_words = array('break', 'do', 'instanceof', 'typeof', 'case', 'else', 'new', 'var', 'catch', 'finally', 'return', 'void', 'continue', 'for', 'switch', 'while', 'debugger', 'function', 'this', 'with', 'default', 'if', 'throw', 'delete', 'in', 'try', 'class', 'enum', 'extends', 'super', 'const', 'export', 'import', 'implements', 'let', 'private', 'public', 'yield', 'interface', 'package', 'protected', 'static', 'null', 'true', 'false');

		return preg_match($identifier_syntax, $subject) && !in_array(mb_strtolower($subject, 'UTF-8'), $reserved_words);
	}

	public function index() {
					
		$method = strtolower($_SERVER['REQUEST_METHOD']);
		
		if($method == 'get') {
			if(isset($this -> registry -> get['single'])) {
				$this -> single($this -> registry -> get);
			} else {
				$this -> fetch($this -> registry -> get);
			}
		} else if ($method == 'post') {
			
			$data = file_get_contents("php://input");
			
			if(is_string($data)){
				$data = json_decode($data,true);
			}
			
			$this -> create($data);
		} else if ($method == 'put') {
			$this -> update(json_decode($this -> request -> getRequestData(), true));
			
			exit();
			//$this -> update(json_decode (file_get_contents("php://input"),true));
		} else if ($method == 'delete') {
			$data = $this -> registry -> get;
			
			if(empty($data)){
				$data = file_get_contents("php://input");
			}
			
			if(is_string($data)){
				$data = json_decode($data,true);
			}
			
			$this -> delete($data);
		}
		
		exit();
	}
	
	protected function create($data) {
		
		$model = new $this ->_model();
		
		if($model -> create($data)){
			return $this -> _jsonResponse($model -> getIterator() -> getData());
		}	else {
			//print_r($model -> getVadilationErrors());
			echo PVResponse::createResponse(410, $model -> getErrorsString());
		}	
			
		exit();
	}
	
	public function update($data) {
		
		if(!$this -> _id) {
			PVResponse::createResponse(410, 'An ID is required for updating. Set ID in controller' );
		}
		
		$object = $this ->_model;
		
		if(is_array($this -> _id)) {
			$conditions = array();
			
			foreach($this -> _id as $key => $value) {
				$conditions[$value] = $data[$value];
			}
		} else {
			$conditions = array($this -> _id => $data[$this -> _id]);
		}
		$options = (isset($data['options'])) ? $data['options'] : array();
		$predata = (isset($data['predata'])) ? $data['predata'] : array();
		
		$model = $object::findOne(compact('conditions'));
		
		if($model) {
			$model -> update($data);
			
			$errors = $model -> getVadilationErrors();
			
			if(!empty($errors)) {
				$message = json_encode($errors);
				echo PVResponse::createResponse(425, $model -> getErrorsString()  );
				exit();
			} else {
				$this -> _jsonResponse($model -> getIterator() -> getData());
			}
			
		}
		
		exit();
	}
	
	protected function fetch($data) {
		$object= $this ->_model;
		
		foreach($data as $key => $value) {
		
			if(is_object($value)) {
				$data[$key] = PVConversions::objectToArray($value);
			}
			
			if($this -> _isJson($value)) {
				$data[$key] = json_decode($value, true);
			}
		}
		
		$options = (isset($data['options'])) ? $data['options'] : array();
		$predata = (isset($data['predata'])) ? $data['predata'] : array();
		
		/*if(!isset($data['conditions']) || empty($data['conditions'])) {
			PVResponse::createResponse(470, 'Illegal arguements passed. Query logged.' );
			exit();
		}*/
		
		$models = $object::findAll($data, $options, $predata);
		
		return $this -> _jsonResponse($models);
		exit();
	}
	
	protected function single($data) {
		$object = $this ->_model;
		
		
		foreach($data as $key => $value) {
		
			if(is_object($value)) {
				$data[$key] = PVConversions::objectToArray($value);
			}
			
			if($this -> _isJson($value)) {
				$data[$key] = json_decode($value, true);
			}
		}
		
		$options = (isset($data['options'])) ? $data['options'] : array();
		$predata = (isset($data['predata'])) ? $data['predata'] : array();
		
		if(!isset($data['options'])) {
			$data['options'] = array();
		} else if(isset($data['options']) && !is_array($data['options'])) {
			$data['options'] = array();
		}
		
		$model = $object::findOne($data, $options, $predata);
		
		if($model)
			return $this -> _jsonResponse($model -> getIterator() -> getData());
		else {
			return $this -> _jsonResponse(array());
		}
		exit();
	}
	
	protected function delete($data) {
		
		$object= $this ->_model;
		
		
		
		foreach($data as $key => $value) {
		
			if(is_object($value)) {
				$data[$key] = PVConversions::objectToArray($value);
			}
			
			if($this -> _isJson($value)) {
				$data[$key] = json_decode($value, true);
			}
		}
		
		
		if(!isset($data['conditions']) || ( isset($data['conditions']) && empty($data['conditions']))) {
		
			exit();	
		}
		
		$model = new $object();
		
		$model -> delete($data);
		
	}
	
	public function validate() {
		
		$data = $this -> registry -> post;
			
		if(empty($data)){
			$data = file_get_contents("php://input");
		}
			
		if(is_string($data)){
			$data = json_decode($data,true);
		}
		
		$object= $this ->_model;
		
		$model = new $object();
		
		$result = $model -> validate($data, array('event' => 'create'));
		
		if($result) {
			$this -> _jsonResponse(array('status' => 0, 'message' => $model -> getValidationErrors() ));
		} else {
			$this -> _jsonResponse(array('status' => 1, 'message' => $model -> getValidationErrors() ));
		}
		
		exit();
		
	}
	
	protected function _isJson($string) {
		
		if(is_array($string)) {
			return false;
		}
		
 		json_decode($string);
 		return (json_last_error() == JSON_ERROR_NONE);
	}
	
	function utf8_encode_deep(&$input) {
	    if (is_string($input) || is_a($input,'\\MongoDB\\BSON\\ObjectID') || is_a($input,'MongoID') || is_a($input,'\\MongoDB\\BSON\\UTCDateTime')) {
	        $input = utf8_encode($input);
	    } else if (is_array($input)) {
	        foreach ($input as &$value) {
	            $this -> utf8_encode_deep($value);
	        }
	
	        unset($value);
	    } else if (is_object($input)) {
	        $vars = array_keys(get_object_vars($input));
	
	        foreach ($vars as $var) {
	            $this -> utf8_encode_deep($input->$var);
	        }
	    }
	}
	
	

}
