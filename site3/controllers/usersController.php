<?php

use app\factories\ServiceFactory;


include('baseController.php');

class usersController extends baseController {
	
	protected $_errorMessage = '';
	
	protected $_session = null;
	/**
	 * In the construct, we are restricting access to certian routes of this controller.
	 */
	public function __construct($registry, $configurtion = array()) {
		parent::__construct($registry, $configurtion );
		
		$restricted_routes = array(
			'/users/account',
			'/users/myposts',
			'/users/logout'
		);
		
		$session  = ServiceFactory::getSessionService();
		
		$this -> _session = $session;
		
		if(!$session::read('is_loggedin') && in_array($this->registry -> route[0], $restricted_routes)) {
			PVTemplate::errorMessage('The section is restricted to members. Please login.');
			PVRouter::redirect('/login');
		}
		
	}
		
	public function index() : array  {
		
		$users = $this -> _models -> queryUsers();
		
		return array('users' => $users);
		
	}
	
	public function login()  {
		
		$user = new PVCollection($this -> registry -> post);
		
		$failed_login_attempts = false;
		
		if($this -> registry -> post && AuthenticationService::authenticate($this -> registry -> post['email'], $this -> registry -> post['password'])) {
			PVTemplate::successMessage('Login Successful!');
			return $this -> redirect('/profile/'. $this -> _session::read('user_id'));
		} else if($this -> registry -> post) {
			PVTemplate::errorMessage('Invalid Username/Password');
			
			$failed_login_attempts = $this -> _session::read('failed_login_attempts');
		}
		
		return array('user' => $user, 'failed_login_attempts' => $failed_login_attempts, 'disable_cache' => true);
	}
	
	public function register()  {
		
		$user = new PVCollection($this -> registry -> post);
		
		if($this -> registry -> post && $this -> validate('user','create', $this -> registry -> post)) {
			
			//Get The Data
			$user = $this -> _models -> createUser($this -> registry -> post);
			
			//Create User
			if($user) {
				$this -> _session::write('user_id', $user -> user_id);
				$this -> _session::write('is_loggedin', 1);
				return $this -> redirect('/profile/' . $user -> user_id);
			}	
		}
		
		return array('user' => $user, 'disable_cache' => true);
	}
	
	public function profile() : array  {
		
		$user = $this -> _models -> retrieveUser($this -> registry -> route['id']);
		
		if(!$user) {
			return $this -> error404(array('post_id' => $this -> registry -> route['id']),  'User Not Found');
		}
		
		$posts = new PVCollection();
		
		//Set The Meta here instead of the view
		PVTemplate::setSiteTitle($user -> first_name . ' ' . $user -> last_name);
		PVTemplate::appendSiteMetaTags('<meta name="description" content="'. $this -> Format -> ogTag(PVTools::truncateText($user -> bio, 100)) .'" />');
		
		PVTemplate::appendSiteMetaTags('<meta property="og:title" content="'. $this -> Format -> ogTag($user -> first_name . ' ' . $user -> last_name).' "/>');
		PVTemplate::appendSiteMetaTags('<meta property="og:description" content="'. $this -> Format -> ogTag(PVTools::truncateText($user -> bio, 100)) .'">');
		PVTemplate::appendSiteMetaTags('<meta property="og:url" content="' . PVTools::getCurrentUrl() .'"/>');
		PVTemplate::appendSiteMetaTags('<meta property="og:site_name" content="Helium MVC"/>');
		PVTemplate::appendSiteMetaTags('<meta property="og:type" content="website"/>');
		if($user -> image_id):
			PVTemplate::appendSiteMetaTags('<meta property="og:image" content="'. $this -> Format -> parseImage($user -> image_large_url) .'" />');
		 endif;
		
		PVTemplate::appendSiteMetaTags('<meta name="twitter:card" content="summary">');
		PVTemplate::appendSiteMetaTags('<meta name="twitter:site" content="@he2mvc">');
		PVTemplate::appendSiteMetaTags('<meta name="twitter:creator" content="@he2mvc">');
		PVTemplate::appendSiteMetaTags('<meta name="twitter:url" content="' . PVTools::getCurrentUrl()  . '">');
		PVTemplate::appendSiteMetaTags('<meta name="twitter:title" content="'. $this -> Format -> ogTag($user -> first_name . ' ' . $user -> last_name).'">');
		PVTemplate::appendSiteMetaTags('<meta name="twitter:description" content="'. $this -> Format -> ogTag(PVTools::truncateText($user -> bio, 100)) .'">');
		
		return array('user' => $user, 'posts' => $posts, 'disable_cache' => true);
	}
	
	public function account() : array  {
		
		$user = $this -> _models -> retrieveUser($this -> _session -> read('user_id'));
		
		if(!$user) {
			return $this -> error404(array('post_id' => $this -> registry -> route['id']),  'User Not Found');
		}
		
		if($this -> registry -> post) {
			
			if(isset($this -> registry -> post['update_profile']) && $this -> validate('user','update', $this -> registry -> post)) {
				
				$this -> _models -> updateUser($user -> user_id, $this -> registry -> post);
				
				if(isset($this -> registry -> files['profile_image'] ) && $this -> registry -> files['profile_image']['error'] == 0 && PVValidator::isImageFile(PVFileManager::getFileMimeType($this -> registry -> files['profile_image']['tmp_name'])) ) {	
					$image = Images::uploadImage($this -> registry -> files['profile_image']['tmp_name']);
					
					if($image) {
						$image -> update(array('entity_type' => 'user', 'entity_id' => $user -> user_id));
					}
				}
					
				PVTemplate::successMessage('Profile successfully updated.');
			
			} else if(isset($this -> registry -> post['update_email']) && $this -> validate('user','email', $this -> registry -> post)) {
				$this -> _models -> updateUser($user -> user_id, $this -> registry -> post);
				
				PVTemplate::successMessage('Email successfully updated.');
			} else if(isset($this -> registry -> post['update_password']) && $this -> validate('user','password', $this -> registry -> post)) {
				$this -> _models -> updateUser($user -> user_id, $this -> registry -> post);
				
				PVTemplate::successMessage('Password successfully updated.');
			}
			
			//Reload
			$user = $this -> _models -> retrieveUser($this -> registry -> route['id']);
		}
		
		return array('user' => $user, 'disable_cache' => true);
	}

	public function myposts() : array {
		$posts = Posts::findAll(array(
			'conditions' => array('user_id' =>$this -> _session::read('user_id')),
			'order_by' => 'date_created'
		));
		
		return array('posts' => $posts);
		
	}

	public function activate() {
		
		$user = Users::findOne(array(
			'conditions' => array('user_id' => $this -> registry -> route['id'], 'activation_token' => $this -> registry -> get['token'])
		));
		
		if(!$user) {
			return $this -> error404(array('post_id' => $this -> registry -> route['id']),  'User Not Found');
		}
		
		if(!$user -> is_active) {
			$user -> update(array('is_active' => 1));
		}
		
		return array('user' => $user);
		
	}
	
	public function logout() {
		$this -> _session::endSession();
		
		return $this -> redirect('/');
	}
		
		
}
	