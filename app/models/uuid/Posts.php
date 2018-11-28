<?php
namespace app\models\uuid;

use app\services\LoggingService;

class Posts extends PGModel {
	
	//Virtual Schema
	protected $_schema = array(
		'post_id' => array('type' => 'bigint', 'primary_key' => true, 'default' => 'shard_1.id_generator()' , 'execute_default' => true, 'auto_increment' => true),
		//Optional UUID using built-in Postgres OSSP features
		//'post_id' => array('type' => 'uuid', 'primary_key' => true, 'default' => 'uuid_generate_v4()' , 'execute_default' => true, 'auto_increment' => true),
		'user_id' => array('type' => 'bigint', 'default' => 0),
		'title' => array('type' => 'string', 'precision' => 255, 'default' => 0, 'cast' => 'sanitize'),
		'content' => array('type' => 'text', 'default' => '', 'cast' => 'sanitize_wysiwyg_ahref'),
		'date_created' => array('type' => 'datetime', 'default' => 'now()'),
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
		'user' => array('type' => 'join', 'model' => 'app\models\uuid\Users', 'on' => 'posts.user_id = users.user_id'),
		'image' => array('type' => 'join', 'model' => 'app\models\uuid\Images', 'on' => 'posts.post_id = images.entity_id AND entity_id=\'post\' '),
		'image_left' => array('type' => 'left', 'model' => 'app\models\uuid\Images', 'on' => 'posts.post_id = images.entity_id AND entity_type=\'post\' ')
		
	);
	
}

//Observer to be executed after CRUD create operation
Posts::addObserver('app\models\uuid\Posts::create', 'read_closure', function($model, $result, $id, $data, $options) {
	
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
Posts::addObserver('app\models\uuid\Posts::update', 'read_closure', function($model, $result, $data, $conditions, $options) {
	
	//Checks to make sure the update was a success
	if($result){
		//Log successfull update	
		LoggingService::logModelAction($model, ActionLogger::ACTION_UPDATED_SUCCESS);
	} else {
		//Log failure to update
		LoggingService::logModelAction($model, ActionLogger::ACTION_UPDATED_FAILED);
	}
	
}, array('type' => 'closure'));
