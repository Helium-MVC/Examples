<?php
namespace app\models\uuid;

use prodigyview\database\Database;

/**
 * class ActionLogger
 * 
 * This class is responsible for recording all actions in the system and logging them to a database. This includes
 * all CRUD Actions, Try Catch, Invalid Pages found, etc.
 * 
 * In a distributed system, the logger can be tied to other systems or exported to other logging service to help give
 * a complete idea on whats oging on.
 */
class ActionLogger extends PGModel {
	
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
		'record_id' => array('type' => 'bigint', 'primary_key' => true, 'default' => 'shard_1.id_generator()' , 'execute_default' => true, 'auto_increment' => true),
		'session_id' => array('type' => 'string', 'precision' => 255),
		'user_id' => array('type' => 'bigint', 'not_null' => false, 'default' => null),
		'thread_id' => array('type' => 'string', 'precision' => 50, 'default' => ''),
		'action' => array('type' => 'string', 'precision' => 255),
		'entity_type' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'entity_id' => array('type' => 'string', 'precision' => 50, 'default' => ''),
		'entity_state' => array('type' => 'hstore', 'exclude' => true, 'default' => 'hstore(array[]::varchar[])', 'execute_default' => true),
		'meta_data' => array('type' => 'hstore', 'exclude' => true, 'default' => 'hstore(array[]::varchar[])', 'execute_default' => true),
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
	public function setState(array $state = array()) {
		
		$tablename = $this -> getTableName();
		
		$dbconn = Database::getDatabaseLink();
		
		$query = 'UPDATE ' . $tablename . ' SET entity_state = entity_state || hstore($1, $2) WHERE record_id=$3 ;';
		
		foreach($state as $key => $value) {
			
			if(is_string($value)) {
				$params = array($key, $value, $this -> record_id);
			
				$result = pg_query_params($dbconn, 'SELECT name FROM pg_prepared_statements WHERE name = $1', array('set_' . $tablename . '_state'));
	
				if (pg_num_rows($result) == 0) {
		    		$result = pg_prepare($dbconn, 'set_' . $tablename . '_state' , $query );
				}
					
				$results = pg_execute($dbconn, 'set_' . $tablename . '_state', $params);
			}//end if string
		
		}//endforeach
		
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
		
		$dbconn = Database::getDatabaseLink();
		
		$query = 'UPDATE ' . $tablename . ' SET meta_data = meta_data || hstore($1, $2) WHERE record_id=$3 ;';
		
		foreach($meta as $key => $value) {
			
			if(is_string($value)) {
				$params = array($key, $value, $this -> record_id);
			
				$result = pg_query_params($dbconn, 'SELECT name FROM pg_prepared_statements WHERE name = $1', array('set_' . $tablename . '_meta'));
	
				if (pg_num_rows($result) == 0) {
		    			$result = pg_prepare($dbconn, 'set_' . $tablename . '_meta' , $query );
				}
					
				$results = pg_execute($dbconn, 'set_' . $tablename . '_meta', $params);
			}//end ifstring
		
		}//enforeach
		
	}
	
}//end class

//Filter to add on all create commands
ActionLogger::addFilter('app\models\uuid\ActionLogger', 'create','filter', function($data, $options) {
	
	$data['data']['record_ip'] = $_SERVER['REMOTE_ADDR'];
	
	return $data;
	
}, array('type' => 'closure', 'event' => 'args'));
