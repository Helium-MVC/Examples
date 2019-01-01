<?php
namespace app\services;

use prodgiyview\system\Configuration;

/**
 * QueueService
 * 
 * This class is responsible for adding items to a distributed queue that can be 
 * later processed.
 */
class QueueService {
	
	protected $_service = null;
	
	/**
	 * The constructure for instantiating the queuing service
	 */
	public function __construct() {
		$this -> _service = new \Redis();
		$this -> _service -> connect(Configuration::getConfiguration('redis') ->host , Configuration::getConfiguration('redis') ->port);
		
	}
	
	/**
	 * add
	 * 
	 * Adds data to the queue.
	 * 
	 * @param string $queue The name or key associated with the queue
	 * @param string $data The data to be stored in the queue, should be in string format
	 * 
	 * @return void
	 */
	public function add($queue, $data) {
		
		if(is_array($data)){
			$data = json_encode($data);
		}
		
		$this -> _service ->lPush($queue, $data);
	}
	
	/**
	 * pop
	 * 
	 * Gets the lastest item that has be interested into the queue.
	 * 
	 * @param string $queue The name of the queue to get the data from
	 * 
	 * @return mixed Returns the data as a string, array or returns null if empty
	 */
	public function pop($queue) {
		$data = $this -> _service ->lPop($queue);
		
		if($this -> isJson($data)) {
			$data = json_decode($data);
		}
		
		return $data;
	}
	
	/**
	 * Checks if the string is json
	 */
	public function isJson($string) {
 		json_decode($string);
 		return (json_last_error() == JSON_ERROR_NONE);
	}
	
}
