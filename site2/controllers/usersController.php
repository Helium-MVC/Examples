<?php

use app\models\uuid\Posts;
use app\models\uuid\Images;
use app\models\uuid\Users;
use app\models\uuid\UserPasswords;
use app\services\AuthenticationService;
use app\services\session\SessionService;

use prodigyview\template\Template;
use prodigyview\network\Router;
use prodigyview\network\Request;
use prodigyview\network\Response;
use prodigyview\util\Validator;
use prodigyview\util\FileManager;

include('baseController.php');

class usersController extends baseController {
		
	public function index() : array  {
		
		$users = Users::findOne(array(
			'conditions' => array('is_active' => 1),
			'order_by' => 'first_name, last_name'
		));
		
		return array('users' => $users);
		
	}
	
	public function login()  {
		
		$user = new Users();
		
		$failed_login_attempts = false;
		
		if($this -> registry -> post && $this->Token->check($this -> registry -> post) && AuthenticationService::authenticate($this -> registry -> post['email'], $this -> registry -> post['password'])) {
			Template::successMessage('Login Successful!');
			return $this -> redirect('/profile/'. SessionService::read('user_id'));
		} else if($this -> registry -> post) {
			Template::errorMessage('Invalid Username/Password');
			
			$failed_login_attempts = SessionService::read('failed_login_attempts');
		}
		
		return array('user' => $user, 'failed_login_attempts' => $failed_login_attempts);
	}
	
	public function register()  {
		
		$user = new Users();
		
		if($this -> registry -> post && $this->Token->check($this -> registry -> post) && $user -> create($this -> registry -> post)) {
			Template::successMessage('Account successfully created!');
			AuthenticationService::forceLogin($user -> email);
			return $this -> redirect('/profile/'. SessionService::read('user_id'));
		}
		
		return array('user' => $user);
		
	}
	
	public function profile() : array  {
		
		$user = Users::findOne(array(
			'conditions' => array('user_id' => $this -> registry -> route['id']),
			'join' => array('image_left'),
		));
		
		if(!$user) {
			return $this -> error404(array('post_id' => $this -> registry -> route['id']),  'User Not Found');
		}
		
		$posts = Posts::findAll(array(
			'conditions' => array('user_id' => $user -> user_id),
			'order_by' => 'date_created'
		));
		
		return array('user' => $user, 'posts' => $posts);
	}
	
	public function account() : array  {
		
		$user = Users::findOne(array(
			'conditions' => array('user_id' => SessionService::read('user_id')),
			'join' => array('image_left'),
		));
		
		if(!$user) {
			return $this -> error404(array('post_id' => $this -> registry -> route['id']),  'User Not Found');
		}
		
		if($this -> registry -> post && $this->Token->check($this -> registry -> post)) {
			
			if(isset($this -> registry -> post['update_profile']) && $user -> update($this -> registry -> post)) {
				
				if(isset($this -> registry -> files['profile_image'] ) && $this -> registry -> files['profile_image']['error'] == 0 && Validator::isImageFile(FileManager::getFileMimeType($this -> registry -> files['profile_image']['tmp_name'])) ) {	
					$image = Images::uploadImage($this -> registry -> files['profile_image']['tmp_name']);
					
					if($image) {
						$image -> update(array('entity_type' => 'user', 'entity_id' => $user -> user_id));
					}
				}
					
				Template::successMessage('Profile successfully updated.');
			
			} else if(isset($this -> registry -> post['update_email'])) {
				$email = (isset($this -> registry -> post['email'])) ? $this -> registry -> post['email'] : '';
				$tmp_user = Users::findOne(array(
					'conditions' => array('email' => $email )
				));
				
				if(!$tmp_user || $tmp_user -> user_id = $user -> user_id) {
					if($user -> update(array('email' => $email))){
						Template::successMessage('Email successfully updated.');
					}
				} else{
					Template::errorMessage('Another user has that email');
				}
			} else if(isset($this -> registry -> post['update_password'])) {
				$password = UserPasswords::findOne(array(
					'conditions' => array('user_id' => $user -> user_id)
				));
				
				if($password -> update($this -> registry -> post)) {
					Template::successMessage('Password successfully updated.');
				}
			}
		}
		
		return array('user' => $user);
	}

	public function myposts() : array {
		$posts = Posts::findAll(array(
			'conditions' => array('user_id' =>SessionService::read('user_id')),
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
		SessionService::endSession();
		
		return $this -> redirect('/');
	}
		
		
}
	