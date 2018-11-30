<?php

namespace app\factories;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

/**
 * FirebaseFactory
 * 
 * When firebase is being used, this factory will ensure the models
 * are being created to the same standar
 */
class FirebaseFactory {
	
	private $_firebase = null;
	
	public function __construct($database) {
		$this -> _firebase = $database;
	}
	
	public function createUser(array $data , $format = 'array') {
		
		//Create Document ID
		$id = $uuid5 = Uuid::uuid4();
				
		//Assign Values
		$data['user_id'] = $id;
		$data['password'] = \PVSecurity::hash($data['password']);
		$data['activation_token'] = \PVTools::generateRandomString();
		$data['preferences'] = array(
			'email_weekly_updates' =>true,
			'email_comment_responses' => true
		);
		
		if($format === 'array') {
			return $data;
		} else {
			return new \PVCollection($data);
		}
		
		$data['date_registered'] = date('Y-m-d H:i:s');
		
	}
	
	public function createPost(array $data, $user, $format = 'array') {
		$data['user'] = array(
			'user_id' => $user -> user_id,
			'first_name' => $user -> first_name,
			'last_name' => $user -> last_name
		);
		
		$data['date_created'] = date('Y-m-d H:i:s');
		
		if($format === 'array') {
			return $data;
		} else {
			return new \PVCollection($data);
		}
	}
	
	public function retrieveUser($id) {
		
	}
}
