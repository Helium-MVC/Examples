<?php
namespace app\models\uuid;

/**
 * EmailLogger
 * 
 * The email logger is a class used to record emails that are sent out through the system. Ideally,
 * this should also track when a reciever engages with the email.
 *  
 */
class EmailLogger extends PGModel {
	
	//Virtual Schema
	protected $_schema = array(
		'log_id' => array('type' => 'bigint', 'primary_key' => true, 'default' => 'shard_1.id_generator()' , 'execute_default' => true, 'auto_increment' => true),
		'sent_status' => array('type' => 'tinyint', 'default' => 0),
		'date_sent' => array('type' => 'datetime', 'default' => 'now()'),
		'subject' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'html_message' => array('type' => 'text', 'default' => ''),
		'text_message' => array('type' => 'text', 'default' => ''),
		'mail_id' => array('type' => 'string', 'precision' => 200, 'default' => ''),
		'mail_type' => array('type' => 'string', 'precision' => 50, 'default' => ''),
		'to_addresses' => array('type' => 'hstore', 'exclude' => true, 'default' => 'hstore(array[]::varchar[])', 'execute_default' => true),
		'cc_addresses' => array('type' => 'hstore', 'exclude' => true, 'default' => 'hstore(array[]::varchar[])', 'execute_default' => true),
		'bcc_addresses' => array('type' => 'hstore', 'exclude' => true, 'default' => 'hstore(array[]::varchar[])', 'execute_default' => true),
	);
	
	/**
	 * Sets the two addresses to be stored
	 * 
	 * @param array $to Key Value store of addresses
	 */
	public function setTo(array $addresses = array()) {
		
		$tablename = $this -> getTableName();
		
		$dbconn = \Database::getDatabaseLink();
		
		$query = 'UPDATE ' . $tablename . ' SET to_addresses = to_addresses || hstore($1, $2) WHERE log_id=$3 ;';
		
		foreach($addresses as $key => $value) {
			
			if(is_string($value)) {
				$params = array($key, $value, $this -> record_id);
			
				$result = pg_query_params($dbconn, 'SELECT name FROM pg_prepared_statements WHERE name = $1', array('set_' . $tablename . '_to_addresses'));
	
				if (pg_num_rows($result) == 0) {
		    			$result = pg_prepare($dbconn, 'set_' . $tablename . '_to_addresses' , $query );
				}
					
				$results = pg_execute($dbconn, 'set_' . $tablename . '_to_addresses', $params);
			}//end ifstring
		
		}//enforeach
		
	}//end setTo
	
	/**
	 * Sets the CC/Carborn copy of of addresses
	 * 
	 * @param array $addresses The addresses in key/store value
	 * 
	 * @return void
	 */
	public function setCC(array $addresses = array()) {
		
		$tablename = $this -> getTableName();
		
		$dbconn = \Database::getDatabaseLink();
		
		$query = 'UPDATE ' . $tablename . ' SET cc_addresses = cc_addresses || hstore($1, $2) WHERE log_id=$3 ;';
		
		foreach($addresses as $key => $value) {
			
			if(is_string($value)) {
				$params = array($key, $value, $this -> record_id);
			
				$result = pg_query_params($dbconn, 'SELECT name FROM pg_prepared_statements WHERE name = $1', array('set_' . $tablename . '_cc_addresses'));
	
				if (pg_num_rows($result) == 0) {
		    		$result = pg_prepare($dbconn, 'set_' . $tablename . '_cc_addresses' , $query );
				}
					
				$results = pg_execute($dbconn, 'set_' . $tablename . '_cc_addresses', $params);
			}//end ifstring
		
		}//enforeach
		
	}//end setTo
	
	/**
	 * Set the Blind coppied emails of emails
	 * 
	 * @param array $addresses The addresses to be stored
	 * 
	 * @return void
	 */
	public function setBCC(array $addresses = array()) {
		
		$tablename = $this -> getTableName();
		
		$dbconn = \Database::getDatabaseLink();
		
		$query = 'UPDATE ' . $tablename . ' SET bcc_addresses = bcc_addresses || hstore($1, $2) WHERE log_id=$3 ;';
		
		foreach($addresses as $key => $value) {
			
			if(is_string($value)) {
				$params = array($key, $value, $this -> record_id);
			
				$result = pg_query_params($dbconn, 'SELECT name FROM pg_prepared_statements WHERE name = $1', array('set_' . $tablename . '_bcc_addresses'));
	
				if (pg_num_rows($result) == 0) {
		    		$result = pg_prepare($dbconn, 'set_' . $tablename . '_bcc_addresses' , $query );
				}
					
				$results = pg_execute($dbconn, 'set_' . $tablename . '_bcc_addresses', $params);
			}//end ifstring
		
		}//enforeach
		
	}//end setTo
	
}
