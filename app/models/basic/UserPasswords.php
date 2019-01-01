<?php
namespace app\models\basic;

use app\models\HModel;
use app\services\LoggingService;

use prodigyview\util\Tools;
use prodigyview\system\Security;

/**
 * UserPasswords
 * 
 * Passwords are kept in a seperate table and given a both a special hash and salt
 * to decrease the likely of a successful dictionary attack.
 */
class UserPasswords extends HModel {
	
	//Virtual Schema
	protected $_schema = array(
		'user_id' => array('type' => 'int', 'primary_key' => true),
		'user_password' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'salt' => array('type' => 'string', 'precision' => 255, 'default' => ''),
	);
	
	//Validation against the virtual schema
	protected $_validators = array(
		'user_id' => array(
			'notempty' => array('error' => 'A user is required to create a password.'),
		), 
		'user_password' => array(
			'notempty' => array('error' => 'A password is required for the user.'),
		), 
	);
	
	//Relation to other models
	protected $_joins = array(
		//JOIN users ON users user_passwords.user_id = users.user_id
		'user' => array('type' => 'join', 'model' => 'app\models\basic\Users', 'on' => 'user_passwords.user_id = users.user_id')
	);
	
}

//Filter to be executed on creation
UserPasswords::addFilter('app\models\basic\UserPasswords', 'create','filter', function($data, $options) {
	
	//Generate a random salt
	$data['data']['salt'] = Tools::generateRandomString();
	
	//Encrypt the password from plain text
	if(isset($data['data']['user_password']) && !empty($data['data']['user_password'])) {
		$data['data']['user_password'] = Security::hash(trim($data['data']['user_password']));
	}
	
	return $data;
	
}, array('type' => 'closure', 'event' => 'args'));

//Filter to be executed on update
UserPasswords::addFilter('app\models\basic\UserPasswords', 'update','filter', function($data, $options) {
	
	//Encrypt the password from plain text
	if(isset($data['data']['user_password']) && !empty($data['data']['user_password'])) {
		$data['data']['user_password'] = Security::hash(trim($data['data']['user_password']));
	}
	
	return $data;
	
}, array('type' => 'closure', 'event' => 'args'));



//Observer to be executed after CRUD create operation
UserPasswords::addObserver('app\models\basic\UserPasswords::create', 'read_closure', function($model, $result, $id, $data, $options) {
	
	//Only execute if successful
	if($result){
		//Log a new user has successfully be created
		LoggingService::logModelAction($model, ActionLogger::ACTION_CREATED_SUCCESS);
		
	} else {
		//Log the user failed to be created
		LoggingService::logModelAction($model, ActionLogger::ACTION_CREATED_FAILED);
	}
	
}, array('type' => 'closure'));


//Observer to execute on CRUD update action
UserPasswords::addObserver('app\models\basic\UserPasswords::update', 'read_closure', function($model, $result, $data, $conditions, $options) {
	
	//Checks to make sure the update was a success
	if($result){
		//Log successfull update	
		LoggingService::logModelAction($model, ActionLogger::ACTION_UPDATED_SUCCESS);
	} else {
		//Log failure to update
		LoggingService::logModelAction($model, ActionLogger::ACTION_UPDATED_FAILED);
	}
	
}, array('type' => 'closure'));
