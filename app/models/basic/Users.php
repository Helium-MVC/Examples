<?php
namespace app\models\basic;

use app\models\HModel;
use app\models\basic\UserPassowrds;
use app\services\EmailService;
use app\services\LoggingService;

/**
 * Users
 * 
 * The users class is that defines the action of a user. Probably the most complex and involved model
 * in the system. All user information should coordinate with the Slack.
 */
class Users extends HModel {
	
	//Virtual Schema
	protected $_schema = array(
		'user_id' => array('type' => 'int', 'primary_key' => true, 'auto_increment' => true),
		'first_name' => array('type' => 'string', 'precision' =>255, 'default' => '', 'cast' => 'sanitize'),
		'last_name' => array('type' => 'string', 'precision' =>255, 'default' => '', 'cast' => 'sanitize'),
		'email' => array('type' => 'string', 'precision' =>255, 'default' => '', 'cast' => 'sanitize'),
		'github_profile' => array('type' => 'string', 'precision' =>255, 'default' => '', 'cast' => 'sanitize'),
		'bio' => array('type' => 'text', 'cast' => 'sanitize_wysiwyg_ahref'),
		'date_registered' => array('type' => 'datetime'),
		'is_active' => array('type' => 'tinyint', 'default' => 0),
		'activation_token' => array('type' => 'string', 'precision' =>255, 'default' => ''),
		'email_weekly_updates' => array('type' => 'tinyint', 'default' => 0),
		'email_comment_responses' => array('type' => 'tinyint', 'default' => 0),
	);
	
	//Checks against the virtual schema
	protected $_validators = array(
		'first_name' => array(
			'notempty' => array('error' => 'First name is required.'),
		), 
		'last_name' => array(
			'notempty' => array('error' => 'Last name is required.'),
		), 
		'password' => array(
			'notempty' => array(
				'error' => 'Password is required to register.',
				'event' => array('create')
			),
		),
		'email' => array(
			'notempty' => array('error' => 'Email name is required.'),
			'email'=>array(
				'error'=>'A valid email address is required.',
			),
			'unique_email' => array(
				'error' => 'Email address is already registered. Please login or use the forgot password',
				'event' => array('create')
			)
		),
		'github_profile' => array(
			'url_allow_empty' => array(
				'error' => 'Your Github account must be a valid url, including http.',
			),
		),
		
	);
	
	//How to join this model with other models
	protected $_joins = array(
		'post' => array('type' => 'join', 'model' => 'app\models\basic\Posts', 'on' => 'users.user_id = posts.user_id'), //JOIN posts ON users.user_id = posts.user_id
		'password' => array('type' => 'natural', 'model' => 'app\models\basic\UserPasswords'), //NATURAL JOIN user_passwords
		'image' => array('type' => 'join', 'model' => 'app\models\basic\Images', 'on' => 'users.user_id = images.entity_id AND entity_id=\'user\' '),
		'image_left' => array('type' => 'left', 'model' => 'app\models\basic\Images', 'on' => 'users.user_id = images.entity_id AND entity_type=\'user\' ')
	);
	
	
	/**
	 * sendWelcomeEmail
	 * 
	 * Sends the welcome email after the user has completed their registration.
	 */
	public function sendWelcomeEmail() {
			
		$email_service = new EmailService();
		$email_service -> sendActivationEmail($this, \PVConfiguration::getConfiguration('sites') -> site1);
		
	}
	
}//end class

//Data to filter on the creation of the user.
Users::addFilter('app\models\basic\Users', 'create','filter', function($data, $options) {
	
	//Generate a random string for their activation token
	$data['data']['activation_token'] = \PVTools::generateRandomString();
	
	//Set date registered
	$data['data']['date_registered'] = date("Y-m-d H:i:s");
	
	//Assign default values to recieving emails
	$data['data']['email_weekly_updates'] = 1;
	$data['data']['email_comment_responses'] = 1;
	
	//Format the email by making it lowercase and trimming white space
	if(isset($data['data']['email'])) {
		$data['data']['email'] = strtolower(trim($data['data']['email']));
	}
	
	//Return data to normal operations
	return $data;
	
}, array('type' => 'closure', 'event' => 'args'));


//Observer to be executed after CRUD create operation
Users::addObserver('app\models\basic\Users::create', 'read_closure', function($model, $result, $id, $data, $options) {
	
	//Only execute if successful
	if($result){
		
		//Log a new user has successfully be created
		LoggingService::logModelAction($model, ActionLogger::ACTION_CREATED_SUCCESS, $model -> user_id);
		
		//Create a new user password
		$password = new UserPasswords();
		$password -> create(array(
			'user_id' => $model -> user_id,
			'user_password' => $data['password']
		));
		
		
		//Send a welcome email to activate their account
		$model -> sendWelcomeEmail();
	} else {
		//Log the user failed to be created
		LoggingService::logModelAction($model, ActionLogger::ACTION_CREATED_FAILED);
	}
	
}, array('type' => 'closure'));


//Observer to execute on CRUD update action
Users::addObserver('app\models\basic\Users::update', 'read_closure', function($model, $result, $data, $conditions, $options) {
	
	//Checks to make sure the update was a success
	if($result){
		//Log successfull update	
		LoggingService::logModelAction($model, ActionLogger::ACTION_UPDATED_SUCCESS, $model -> user_id);
	} else {
		//Log failure to update
		LoggingService::logModelAction($model, ActionLogger::ACTION_UPDATED_FAILED, $model -> user_id);
	}
	
}, array('type' => 'closure'));
