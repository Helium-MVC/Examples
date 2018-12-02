<?php
namespace app\facades;

/**
 * ValidationFacade
 * 
 * The ValidationFacade is responsible for validating data to ensure the correct fields are present. This
 * Facade is used in conjunction withe FireModelFacade beacuse the built-in validated system is not
 * being utilized.
 */
class ValidationFacade {
	
	private $_errors = array();
	
	private $_isValid = true;
	
	/**
	 * Validate a user object before attempting to create or update it.
	 * 
	 * @param string $action. Normally either create or update, but custom validation rules can be added
	 * @param array $data And array of values to validate against
	 * @param boolean $display If set to true, will write the errors out to the template
	 * 
	 * @return boolean Etierh returns true or false
	 */
	public function checkUser($action, array $data, bool $display = true) : bool {
		$valid = true;
		
		//Reset the errors array
		$this -> _errors = array();
		
		if($action == 'create') {
			if(!\PVValidator::check('notempty', $data['first_name'])) {
				$this -> _errors[] = 'First name is required to register';
			}
			if(!\PVValidator::check('notempty', $data['last_name'])) {
				$valid = false;
				$this -> _errors[] = 'Last name is required to register';
			}
			
			if(!\PVValidator::check('notempty', $data['email'])) {
				$valid = false;
				$this -> _errors[] = 'Email is required to register';
			} 
			
			if(!\PVValidator::check('notempty', $data['password'])) {
				$valid = false;
				$this -> _errors[] = 'Password is required to register';
			}
		} else if($action == 'email') {
			if(!\PVValidator::check('notempty', $data['email'])) {
				$this -> _errors[] = 'Email address cannot be empty';
			}
		} else if($action == 'password') {
			if(!\PVValidator::check('notempty', $data['password'])) {
				$this -> _errors[] = 'Password cannot be empty';
			}
		}
		
		
		$this -> _isValid = $valid;
		
		if($display) {
			$this -> _displayErrors();
		}
		
		return $valid;
	}
	
	/**
	 * Validate a post object before attempting to create or update it.
	 * 
	 * @param string $action. Normally either create or update, but custom validation rules can be added
	 * @param array $data And array of values to validate against
	 * @param boolean $display If set to true, will write the errors out to the template
	 * 
	 * @return boolean Etierh returns true or false
	 */
	public function checkPost($action, array $data, bool $display = true) : bool {
		$valid = true;
		
		//Reset the errors array
		$this -> _errors = array();
		
		if($action == 'create') {
			if(!\PVValidator::check('notempty', $data['user_id'])) {
				$valid = false;
				$this -> _errors[] = 'The post must belong to a user';
			}
		} else if($action == 'comment') {
			
		}
		
		if($action == 'create' || $action == 'update') {
			if(!\PVValidator::check('notempty', $data['title'])) {
				$this -> _errors[] = 'The post must have a title';
			}
			if(!\PVValidator::check('notempty', $data['content'])) {
				$valid = false;
				$this -> _errors[] = 'The post requires content';
			}
		}
		
		
		$this -> _isValid = $valid;
		
		if($display) {
			$this -> _displayErrors();
		}
		
		return $valid;
	}
	
	/**
	 * Return the errors that have been found
	 * 
	 * @return array
	 */
	public function getErrors() : array {
		return $this -> _errors;
	}
	
	/**
	 * Returns if the current validator is valid
	 * 
	 * @return boolean
	 */
	public function isValid() : bool {
		return $this -> _isValid;
	}
	
	/**
	 * Display the error in the view using PVTemplate
	 */
	private function _displayErrors() {
		
		foreach($this -> _errors as $error) {
			\PVTemplate::errorMessage($error);
		}//end foreach
		
	}
	
}
