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
		
		$contact = new ContactSubmissions();
		
		if($this -> registry -> post && $contact -> create($this -> registry -> post)) {
			PVTemplate::successMessage('Contact form has been succesfully submited');
		}
		
		return array('contact' => $contact);
	}
	
	
	
}
