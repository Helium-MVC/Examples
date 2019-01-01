<?php

use app\models\basic\ActionLogger;
use app\services\Session\SessionService;

use prodigyview\template\Template;
use prodigyview\network\Router;
use prodigyview\network\Request;
use prodigyview\network\Response;

include('baseController.php');

class logsController extends baseController {
	
	/**
	 * In the construct, we are restricting access to certian routes of this controller.
	 */
	public function __construct($registry, $configurtion = array()) {
		parent::__construct($registry, $configurtion );
		
		if(!SessionService::read('is_loggedin')) {
			Template::errorMessage('The section is restricted to members. Please login.');
			Router::redirect('/login');
		}
		
	}

	public function index() : array  {
		
		//Notice in this example, we are caching
		//the query results
		$logs = ActionLogger::findAll(array(
			'order_by' => 'date_recoded DESC'
		), array('cache' => true));
		
		return array('logs' => $logs);
	}
	
	public function view() : array {
		
		$log = ActionLogger::findOne(array(
		  'conditions' => array('record_id' => $this -> registry -> route['id'])
		));
		
		if(!$log) {
			return $this -> error404(array('post_id' => $this -> registry -> route['id']),  'Log Not Found');
		}
		
		return array('log' => $log);	
		
	}
	
	
	
}
