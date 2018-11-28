<?php
namespace app\models\basic;

use app\models\HModel;
use app\services\LoggingService;


class Posts extends HModel {
	
	//Virtual Schema
	protected $_schema = array(
		'post_id' => array('type' => 'int', 'primary_key' => true, 'auto_increment' => true),
		'user_id' => array('type' => 'int', 'default' => 0),
		'title' => array('type' => 'string', 'precision' => 255, 'default' => 0, 'cast' => 'sanitize'),
		'content' => array('type' => 'text', 'cast' => 'sanitize_wysiwyg_ahref'),
		'date_created' => array('type' => 'datetime'),
		'is_published'=> array('type' => 'tinyint', 'default' => 0),
		'is_deleted'=> array('type' => 'tinyint', 'default' => 0),
	);
	
	//Validation
	protected $_validators = array(
		'user_id' => array(
			'notempty' => array('error' => 'Post must be associated with a user.'),
		), 
		'title' => array(
			'notempty' => array('error' => 'The post requires a title.'),
		), 
		'content' => array(
			'notempty' => array('error' => 'The post requires text.'),
		), 
	);
	
	//Relation to other models
	protected $_joins = array(
		'user' => array('type' => 'join', 'model' => 'app\models\basic\Users', 'on' => 'posts.user_id = users.user_id'),
		'image' => array('type' => 'join', 'model' => 'app\models\basic\Images', 'on' => 'posts.post_id = images.entity_id AND entity_id=\'post\' '),
		'image_left' => array('type' => 'left', 'model' => 'app\models\basic\Images', 'on' => 'posts.post_id = images.entity_id AND entity_type=\'post\' ')
		
	);
	
}

//Observer to be executed after CRUD create operation
Posts::addObserver('app\models\basic\Posts::create', 'read_closure', function($model, $result, $id, $data, $options) {
	
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
Posts::addObserver('app\models\basic\Posts::update', 'read_closure', function($model, $result, $data, $conditions, $options) {
	
	//Checks to make sure the update was a success
	if($result){
		//Log successfull update	
		LoggingService::logModelAction($model, ActionLogger::ACTION_UPDATED_SUCCESS);
	} else {
		//Log failure to update
		LoggingService::logModelAction($model, ActionLogger::ACTION_UPDATED_FAILED);
	}
	
}, array('type' => 'closure'));
