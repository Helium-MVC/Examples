<?php

use app\models\basic\Posts;
use app\models\basic\ContactSubmissions;
use app\services\session\SessionService;

include('baseController.php');

class indexController extends baseController {
	

	public function index() : array  {
		
		$reference  = $this -> _firebase -> getReference('posts');
		$snapshot = $reference->getSnapshot();

		$value = $snapshot->getValue();
		$posts = new PVCollection($value);
		
		return array('posts' => $posts);
	}
	
	public function contact() : array {
		
		$contact = new PVCollection($this -> registry -> post);
		
		if($this -> registry -> post && $this -> validate('contact', 'create', $this -> registry -> post)) {
			$contact = $this -> _models -> createContact($this -> registry -> post);
			
			PVTemplate::successMessage('Contact form has been succesfully submited');
		}
		
		return array('contact' => $contact);
	}
	
	
	
}
