<?php
namespace app\models\mongo;

use app\models\HModel;

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
		'record_id' => array('type' => '_id', 'primary_key' => true, 'auto_increment' => true),
		'session_id' => array('type' => 'string',),
		'user_id' => array('type' => 'int',),
		'thread_id' => array('type' => 'string','default' => ''),
		'action' => array('type' => 'string', ),
		'entity_type' => array('type' => 'string', 'default' => ''),
		'entity_id' => array('type' => 'string',  'default' => ''),
		'entity_state' => array('type' => 'array', 'cast' => 'array'),
		'meta_data' => array('type' => 'array', 'cast' => 'array'),
		'date_recoded' => array('type' => 'datetime', 'default' => 'now()'),
		'record_ip' => array('type' => 'ip'),
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
	public function setState(array $state = array()) : void {
		
		$this -> update(array('entity_state' => $state));
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
	public function setMeta(array $meta = array()) : void {
		
		$this -> update(array('meta_data' => $meta));
			
	}
	
}//end class

//Filter to add on all create commands
ActionLogger::addFilter('app\models\uuid\ActionLogger', 'create','filter', function($data, $options) {
	
	$data['data']['record_ip'] = $_SERVER['REMOTE_ADDR'];
	
	return $data;
	
}, array('type' => 'closure', 'event' => 'args'));
