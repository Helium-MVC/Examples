<?php

use app\models\basic\Posts;
use app\models\basic\ContactSubmissions;
use app\services\session\SessionService;

use prodigyview\util\Collection;
use prodigyview\template\Template;
use prodigyview\network\Router;
use prodigyview\network\Request;
use prodigyview\network\Response;

include('baseController.php');

class indexController extends baseController {
	

	public function index() : array  {
		
		$reference  = $this -> _firebase -> getReference('posts');
		$snapshot = $reference->getSnapshot();

		$value = $snapshot->getValue();
		$posts = new Collection($value);
		
		return array('posts' => $posts);
	}
	
	public function contact() : array {
		
		$contact = new Collection($this -> registry -> post);
		
		if($this -> registry -> post && $this -> validate('contact', 'create', $this -> registry -> post)) {
			$contact = $this -> _models -> createContact($this -> registry -> post);
			
			Template::successMessage('Contact form has been succesfully submited');
		}
		
		return array('contact' => $contact);
	}
	
	
	
}
