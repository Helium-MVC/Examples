<?php

use app\factories\ServiceFactory;

include('baseController.php');

class postsController extends baseController {
	
	/**
	 * In the construct, we are restricting access to certian routes of this controller.
	 */
	public function __construct($registry, $configurtion = array()) {
		parent::__construct($registry, $configurtion );
		
		$restricted_routes = array(
			'/posts/create',
			'/posts/update',
			'/posts/delete'
		);
		
		$session  = ServiceFactory::getSessionService();
		
		if(!$session::read('is_loggedin') && in_array($this->registry -> route[0], $restricted_routes)) {
			PVTemplate::errorMessage('The section is restricted to members. Please login.');
			PVRouter::redirect('/login');
		}
		
	}
	

	public function index() : array {
		
		$posts = $this -> _models -> queryPosts(array('is_published' => 1));
		
		return array('posts' => $posts);
	}
	
	public function create()  {
		
		$post = new PVCollection($this -> registry -> post);
		
		if($this -> registry -> post && $this -> validate('post','create', $this -> registry -> post)) {
			
			$post = $this -> _models -> createPost($this -> registry -> post);
			
			if(isset($this -> registry -> files['header_image'] ) && $this -> registry -> files['header_image']['error'] == 0 && PVValidator::isImageFile(PVFileManager::getFileMimeType($this -> registry -> files['header_image']['tmp_name'])) ) {
				//Get the storage factory to utlize the storage service	
				$storage = ServiceFactory::getStorageService();
				$storage -> upload($post -> post_id, $this -> registry -> files['header_image']['tmp_name']);
			}
			
			PVTemplate::successMessage('Post successfully created.');
			return $this -> redirect('/posts/view/' . $post -> post_id);
		}
		
		
		return array('post' => $post, 'disable_cache' => true);
	}
	
	public function update() {
		
		$post = $this -> _models -> retrievePost($this -> registry -> route['id']);
		
		$session = ServiceFactory::getSessionService();
		
		if(!$post) {
			return $this -> error404(array('post_id' => $this -> registry -> route['id']),  'Post Not Found');
		}
		
		if($post -> user_id != $session::read('user_id')) {
			return$this-> accessdenied(array('post_id' => $this -> registry -> route['id'], 'user_id' => $session::read('user_id') ));
		}
		
		if($this -> registry -> post && $this -> validate('post','update', $this -> registry -> post)) {
			
			$this -> _models -> updatePost($post -> post_id, $this -> registry -> post);
			
			if(isset($this -> registry -> files['header_image'] ) && $this -> registry -> files['header_image']['error'] == 0 && PVValidator::isImageFile(PVFileManager::getFileMimeType($this -> registry -> files['header_image']['tmp_name'])) ) {	
				$storage = ServiceFactory::getStorageService();
				$storage -> upload($post -> post_id, $this -> registry -> files['header_image']['tmp_name']);
			}
			
			PVTemplate::successMessage('Post successfully updated.');
			return $this -> redirect('/posts/view/' . $post -> post_id);
		}
		
		return array('post' => $post, 'disable_cache' => true);
	}

	public function view() : array  {
		
		$post = $this -> _models -> retrievePost($this -> registry -> route['id']);
		
		if(!$post) {
			return $this -> error404(array('post_id' => $this -> registry -> route['id']),  'Post Not Found');
		}
		
		if($this -> registry -> post && $comment -> create($this -> registry -> post)) {
			PVTemplate::successMessage('Comment successfully created');
		}
		
		return array('post' => $post);
	}

	public function delete()  {
		
		$session = ServiceFactory::getSessionService();
		
		$post = Posts::findOne(array(
			'conditions' => array('post_id' => $this -> registry -> route['id'])
		));
		
		if(!$post) {
			return $this -> error404(array('post_id' => $this -> registry -> route['id']),  'Post Not Found');
		}
		
		if($post -> user_id != $session::read('user_id')) {
			return$this-> accessdenied(array('post_id' => $this -> registry -> route['id'], 'user_id' => $this -> $session::read('user_id') ));
		}
		
		if($this -> registry -> post) {
			if(isset($this -> registry -> post['yes'])) {
				$this -> _models -> deleteUser($this -> registry -> route['id']);
				PVTemplate::successMessage('Post successfully deleted.');
			}
			
			return $this -> redirect('/posts');
		}
		
		return array('post' => $post, 'disable_cache' => true);
	}

	public function rss() : array {
		
		$posts = $this -> _models -> queryPosts(array('is_published' => 1));
		
		//Changes the template to blank.html.php
		$this -> _renderTemplate(array('prefix' => 'blank'));
		
		return array('posts' => $posts);
	}
	
	
}
