<?php

use app\models\uuid\ActionLogger;

include('baseController.php');

class logsController extends baseController {
	

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
