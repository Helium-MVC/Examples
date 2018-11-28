<?php
namespace app\models\basic;

use app\services\EmailService;
use app\services\LoggingService;

class ContactSubmissions extends PGModel {
	
	//Virtual Schema
	protected $_schema = array(
		'contact_id' => array('type' => 'int', 'primary_key' => true, 'auto_increment' => true),
		'name' => array('type' => 'string', 'precision' =>255, 'default' => '', 'cast' => 'sanitize'),
		'email' => array('type' => 'string', 'precision' =>255, 'default' => '', 'cast' => 'sanitize'),
		'phone' => array('type' => 'string', 'precision' =>255, 'default' => '', 'cast' => 'sanitize'),
		'message' => array('type' => 'text', 'cast' => 'sanitize_wysiwyg_ahref'),
		'date_sent' => array('type' => 'datetime'),
	);
	
	//Validators against the virtual schema
	protected $_validators = array(
		'name' => array(
			'notempty' => array('error' => 'Name is required to send a message.'),
		), 
		'email' => array(
			'notempty' => array('error' => 'Email is required to send a message.'),
		), 
		'phone' => array(
			'notempty' => array('error' => 'Phone number is required to send a message.'),
		), 
		'message' => array(
			'notempty' => array('error' => 'A message is required to send a message.'),
		),
	);
	
}

//Observer to be executed after CRUD create operation
ContactSubmissions::addObserver('app\models\basic\ContactSubmissions::create', 'read_closure', function($model, $result, $id, $data, $options) {
	
	//Only execute if successful
	if($result){
		//Log when contact has successfully been created
		LoggingService::logModelAction($model, ActionLogger::ACTION_CREATED_SUCCESS, $model -> contact_id);
		
	} else {
		//Log the user failed to be created
		LoggingService::logModelAction($model, ActionLogger::ACTION_CREATED_FAILED);
	}
	
}, array('type' => 'closure'));


//Observer to execute on CRUD update action
ContactSubmissions::addObserver('app\models\basic\ContactSubmissions::update', 'read_closure', function($model, $result, $data, $conditions, $options) {
	
	//Checks to make sure the update was a success
	if($result){
		//Log successfull update	
		LoggingService::logModelAction($model, ActionLogger::ACTION_UPDATED_SUCCESS, $model -> contact_id);
	} else {
		//Log failure to update
		LoggingService::logModelAction($model, ActionLogger::ACTION_UPDATED_FAILED, $model -> contact_id);
	}
	
}, array('type' => 'closure'));
