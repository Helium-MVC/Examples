<?php

use app\models\basic\ActionLogger;
use app\services\Session\SessionService;

include('baseController.php');

class logsController extends baseController {
	
	/**
	 * In the construct, we are restricting access to certian routes of this controller.
	 */
	public function __construct($registry, $configurtion = array()) {
		parent::__construct($registry, $configurtion );
		
		if(!SessionService::read('is_loggedin')) {
			PVTemplate::errorMessage('The section is restricted to members. Please login.');
			PVRouter::redirect('/login');
		}
		
	}

	public function index() : array  {
		
		//Notice in this example, we are caching
		//the query results
		$logs = $this -> _models -> queryLogs();
		
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
