<?php
namespace app\models\uuid;

use app\models\HModel;

/**
 * Session
 * 
 * The Session class controls the session information tied to an account. There can only be one account, but an account can have multiple
 * sessions, ie logging in through different accounts.
 * 
 * The sessions are stored in a different database because speed is important when accessing sessions.
 */
class Sessions extends HModel {
	
	//Virtual Schema
	protected $_schema = array(
		'session_id' => array('type' => 'bigint', 'primary_key' => true, 'default' => 'shard_1.id_generator()' , 'execute_default' => true, 'auto_increment' => true),
		'user_id' => array('type' => 'bigint', 'default' =>0, ),
		'token' => array('type' => 'string', 'precision' => 255, 'default' => '', ),
		'is_guest' => array('type' => 'tinyint', 'default' => 0, ),
		'api_token' => array('type' => 'string', 'precision' => 255, 'default' => '', ),
		'previous_url' => array('type' => 'string', 'precision' => 255, 'default' => '', ),
		'is_loggedin' => array('type' => 'tinyint', 'default' => 0),
		'failed_login_attempts' => array('type' => 'integer', 'default' => 0),
	);
	
}

//Add a filter to execute on creation
Sessions::addFilter('app\models\uuid\Sessions', 'create','filter', function($data, $options) {
	
	$data['data']['api_token'] = \PVTools::generateRandomString();

	return $data;
	
}, array('type' => 'closure', 'event' => 'args'));
