<?php

use app\factories\ServiceFactory;

use prodigyview\util\Collection;
use prodigyview\template\Template;
use prodigyview\network\Router;
use prodigyview\network\Request;
use prodigyview\network\Response;

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
			Template::errorMessage('The section is restricted to members. Please login.');
			Router::redirect('/login');
		}
		
	}
	

	public function index() : array {
		
		$posts = $this -> _models -> queryPosts(array('is_published' => 1));
		
		return array('posts' => $posts);
	}
	
	public function create()  {
		
		$post = new Collection($this -> registry -> post);
		
		if($this -> registry -> post && $this -> validate('post','create', $this -> registry -> post)) {
			
			$post = $this -> _models -> createPost($this -> registry -> post);
			
			if(isset($this -> registry -> files['header_image'] ) && $this -> registry -> files['header_image']['error'] == 0 && Validator::isImageFile(FileManager::getFileMimeType($this -> registry -> files['header_image']['tmp_name'])) ) {
				//Get the storage factory to utlize the storage service	
				$storage = ServiceFactory::getStorageService();
				$storage -> upload($post -> post_id, $this -> registry -> files['header_image']['tmp_name']);
			}
			
			Template::successMessage('Post successfully created.');
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
			
			if(isset($this -> registry -> files['header_image'] ) && $this -> registry -> files['header_image']['error'] == 0 && Validator::isImageFile(FileManager::getFileMimeType($this -> registry -> files['header_image']['tmp_name'])) ) {	
				$storage = ServiceFactory::getStorageService();
				$storage -> upload($post -> post_id, $this -> registry -> files['header_image']['tmp_name']);
			}
			
			Template::successMessage('Post successfully updated.');
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
			Template::successMessage('Comment successfully created');
		}
		
		
		//Set the meta data here instead of in tempalte
		Template::setSiteTitle($post -> title);
 
		Template::appendSiteMetaTags('<meta name="description" content="'. $this -> Format -> ogTag(prodigyview\util\Tools::truncateText($post -> content, 100)) .'" />');
		
		Template::appendSiteMetaTags('<meta property="og:title" content="'. $this -> Format -> ogTag($post -> title).' "/>');
		Template::appendSiteMetaTags('<meta property="og:description" content="'. $this -> Format -> ogTag(prodigyview\util\Tools::truncateText($post -> content, 100)) .'">');
		Template::appendSiteMetaTags('<meta property="og:url" content="' . Router::getCurrentUrl() .'"/>');
		Template::appendSiteMetaTags('<meta property="og:site_name" content="Helium MVC"/>');
		Template::appendSiteMetaTags('<meta property="og:type" content="website"/>');
		if($post -> image_id):
			Template::appendSiteMetaTags('<meta property="og:image" content="'. $this -> Format -> parseImage($post -> image_large_url) .'" />');
		 endif;
		
		Template::appendSiteMetaTags('<meta name="twitter:card" content="summary">');
		Template::appendSiteMetaTags('<meta name="twitter:site" content="@he2mvc">');
		Template::appendSiteMetaTags('<meta name="twitter:creator" content="@he2mvc">');
		Template::appendSiteMetaTags('<meta name="twitter:url" content="' . Router::getCurrentUrl()  . '">');
		Template::appendSiteMetaTags('<meta name="twitter:title" content="'. $this -> Format -> ogTag($post -> title).'">');
		Template::appendSiteMetaTags('<meta name="twitter:description" content="'. $this -> Format -> ogTag(prodigyview\util\Tools::truncateText($post -> content, 100)) .'">');
		
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
				Template::successMessage('Post successfully deleted.');
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
