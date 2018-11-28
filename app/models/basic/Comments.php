<?php

namespace app\models\basic;

use app\models\HModel;
use app\services\EmailService;
use app\services\LoggingService;

class Comments extends HModel {
	
	protected $_schema = array(
		'comment_id' => array('type' => 'int', 'primary_key' => true, 'auto_increment' => true),
		'user_id' => array('type' => 'int', 'default' => 0),
		'post_id' => array('type' => 'int', 'default' => 0),
		'comment' => array('type' => 'text', 'cast' => 'sanitize_wysiwyg_ahref'),
		'date_added' => array('type' => 'datetime'),
		'is_removed' => array('type' => 'tinyint', 'default' => 0)
	);
	
	//Validators against the virtual schema
	protected $_validators = array(
		'user_id' => array(
			'notempty' => array('error' => 'A user must be associated with the comment.'),
		), 
		'post_id' => array(
			'notempty' => array('error' => 'The comment must be associated with a post.'),
		), 
		'comment' => array(
			'notempty' => array('error' => 'The comment must have some text.'),
		),
	);
	
	protected $_joins = array(
		'user' => array('type' => 'join', 'model' => 'app\models\basic\Users', 'on' => 'comments.user_id = users.user_id'),
		'post' => array('type' => 'join', 'model' => 'app\models\basic\Posts', 'on' => 'comments.post_id = posts.post_id'),
	);
	
	/**
	 * Notify the post creator that a comment has been left on their post.
	 * 
	 * @return void
	 */
	public function notifyPostCreator() : void {
		
		$post = Posts::findOne(array(
			'condtions' => array('post_id' => $this -> post_id)
		));
		
		$original_poster = Users::findOne(array(
			'conditions' => array('user_id' => $post -> user_id)
		));
		
		//Only send email if op has opted in
		if($original_poster -> email_comment_responses) {
			$commenter = Users::findOne(array(
				'conditions' => array('user_id' => $this -> user_id)
			));
			
			$email = new EmailService();
			$email -> sendPostComment($this, $post, $original_poster, $commenter);
			
		}
		
	}
	
}

//Observer to be executed after CRUD create operation
Comments::addObserver('app\models\basic\Comments::create', 'read_closure', function($model, $result, $id, $data, $options) {
	
	//Only execute if successful
	if($result){
		//Log when a new comment has successfully been created
		LoggingService::logModelAction($model, ActionLogger::ACTION_CREATED_SUCCESS, $model -> comment_id);
		
		//After creation, see the creator a comment has been created
		$model -> notifyPostCreator();
		
	} else {
		//Log the user failed to be created
		LoggingService::logModelAction($model, ActionLogger::ACTION_CREATED_FAILED);
	}
	
}, array('type' => 'closure'));


//Observer to execute on CRUD update action
Comments::addObserver('app\models\basic\Comments::update', 'read_closure', function($model, $result, $data, $conditions, $options) {
	
	//Checks to make sure the update was a success
	if($result){
		//Log successfull update	
		LoggingService::logModelAction($model, ActionLogger::ACTION_UPDATED_SUCCESS, $model -> comment_id);
	} else {
		//Log failure to update
		LoggingService::logModelAction($model, ActionLogger::ACTION_UPDATED_FAILED, $model -> comment_id);
	}
	
}, array('type' => 'closure'));
