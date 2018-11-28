<?php
/**
 * Configure the options for the PVSecurity class. Remember to set the options for the encryption,
 * salt, and authorization.
 */
$security_config = array(		
	'mcrypt_key' => '8v9Fp.',									//Set the encryption key
	'mcrypt_iv' => '3n9zAPQ3',									//Set the encryption ov
	'salt' => '$1$ef0110101',									//Set the salt used for password
	'cookie_fields' => array('user_id'),							//Set the fields to be saved to a cookie on successful authentication
	'session_fields' => array('user_id'),						//Set the fields to be saved to a session on successful authentication
	'auth_hashed_fields' => array('user_password'),				//Hash these fields with the salt
	'auth_table' => PVDatabase::formatTableName('users')			//The collection to be used when authentication
);

PVSecurity::init($security_config);
