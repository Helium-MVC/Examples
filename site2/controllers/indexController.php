<?php

use app\models\uuid\Posts;
use app\models\uuid\ContactSubmissions;
use app\services\session\SessionService;

include('baseController.php');

class indexController extends baseController {
	

	public function index() : array  {
		
		$posts = Posts::findAll(array(
			'conditions' => array('is_deleted' => 0, 'is_published' => 1),
			'join' => array('user'),
			'order_by' => 'date_created',
			'limit' => 5
		));
		
		return array('posts' => $posts);
	}
	
	public function contact() : array {
		
		$contact = new ContactSubmissions();
		
		if($this -> registry -> post && $this->Token->check($this -> registry -> post) && $contact -> create($this -> registry -> post)) {
			PVTemplate::successMessage('Contact form has been succesfully submited');
		}
		
		return array('contact' => $contact);
	}
	
	
	
}
