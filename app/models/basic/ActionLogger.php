<?php
namespace app\models\basic;

use app\models\HModel;

use prodigyview\system\Database;

/**
 * class ActionLogger
 * 
 * This class is responsible for recording all actions in the system and logging them to a database. This includes
 * all CRUD Actions, Try Catch, Invalid Pages found, etc.
 * 
 * In a distributed system, the logger can be tied to other systems or exported to other logging service to help give
 * a complete idea on whats oging on.
 */
class ActionLogger extends HModel {
	
	//The action to be recorded before save
	const ACTION_CREATED_PREFLIGHT = 'create_preflight';
	
	//The action to be created when a save is successful
	const ACTION_CREATED_SUCCESS = 'create_success';
	
	//The action associated with a creation failed to save
	const ACTION_CREATED_FAILED = 'create_failed';
	
	//The action to recored before an update
	const ACTION_UPDATED_PREFLIGHT = 'update_preflight';
	
	//The action to be recorded when an update is successful
	const ACTION_UPDATED_SUCCESS = 'update_success';
	
	//The action to be recorded when an update fails
	const ACTION_UPDATED_FAILED = 'update_failed';
	
	//The action to be recorded when a delete is requested
	const ACTION_DELETED_PREFLIGHT = 'delete_preflight';
	
	//The action to be recorded when a delete is successful
	const ACTION_DELETED_SUCCESS = 'delete_success';
	
	//The action to be recored when a delete fails
	const ACTION_DELETED_FAILED = 'delete_failed';
	
	//The action recorded when slack post failed in model
	const MODEL_SLACK_FAILED = 'slack_action_failed';
	
	//Virtual Schema
	protected $_schema = array(
		'record_id' => array('type' => 'int', 'primary_key' => true, 'auto_increment' => true),
		'session_id' => array('type' => 'string', 'precision' => 255),
		'user_id' => array('type' => 'int', 'not_null' => false, 'default' => null),
		'thread_id' => array('type' => 'string', 'precision' => 50, 'default' => ''),
		'action' => array('type' => 'string', 'precision' => 255),
		'entity_type' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'entity_id' => array('type' => 'string', 'precision' => 50, 'default' => ''),
		'entity_state' => array('type' => 'json'),
		'meta_data' => array('type' => 'json'),
		'date_recoded' => array('type' => 'datetime'),
		'record_ip' => array('type' => 'string', 'precision' => 50, 'default' => ''),
	);
	
	
	/**
	 * setState
	 * 
	 * The state is the entities data at the moment of recording that will be referenced later.
	 * 
	 * @param array $state The data from the entity
	 * 
	 * @return void
	 */
	public function setState(array $state = array()) {
		
		$tablename = $this -> getTableName();
		
		//Create the query
		$query = 'UPDATE ' . $tablename . ' SET entity_state = \' '.Database::makeSafe(json_encode($state)).' \' WHERE record_id='.$this -> record_id. ' ;';
		
		//Run the query directly
		Database::query($query);
		
		//Re-sync models data
		$this -> sync();
		
	}
	
	/**
	 * setMeta
	 * 
	 * Meta is the information about the environment the action takes place in. It includes information
	 * such as browser information, server information, etc.
	 * 
	 * @param array $meta The meta data to be recorded
	 * 
	 * @return void
	 */
	public function setMeta(array $meta = array()) {
		
		$tablename = $this -> getTableName();
		
		//Create the query
		$query = 'UPDATE ' . $tablename . ' SET meta_data = \' '.Database::makeSafe(json_encode($meta)).' \' WHERE record_id='.$this -> record_id. ' ;';
		
		//Run the query directly
		Database::query($query);
		
		//Re-sync models data
		$this -> sync();
		
	}
	
}//end class

//Filter to add on all create commands
ActionLogger::addFilter('app\models\basic\ActionLogger', 'create','filter', function($data, $options) {
	
	$data['data']['record_ip'] = $_SERVER['REMOTE_ADDR'];
	
	return $data;
	
}, array('type' => 'closure', 'event' => 'args'));
