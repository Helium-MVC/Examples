<?php
/**
 * Seed
 * 
 * This class is used to seed information into the database. This class is used by the cli
 * and should be accessed such as:
 * 
 * php main/helium.php Seed [function_name]
 * 
 * Example:
 * 
 * php main/helium.php Seed seedSkills
 */
class Seed {
	
	/**
	 * Seed The database with sample user data
	 */
	public function seedUsers() {
		
		$users = array(
			array(
				'email' => 'user1@he2mvc.com',
				'first_name' => 'John',
				'last_name' => 'Doe',
				'user_password' => 'abc123',
				'is_active' => 1,
			),
			
		);
		
		foreach($users as $data) {
			
			$user = new Users();
			
			if($user -> create($data)) {
				
				
			} else {
				print_r($user -> getValidationErrors());
			}
			
		}//end for users
		
	}
	
}
