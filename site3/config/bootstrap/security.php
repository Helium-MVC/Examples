<?php
/**
 * Configure the options for the Security class. Remember to set the options for the encryption,
 * salt, and authorization.
 */
use prodigyview\database\Database;
use prodigyview\system\Security;

$security_config = array(		
	'mcrypt_key' => '8v9Fp.',									//Set the encryption key
	'mcrypt_iv' => '3n9zAPQ3',									//Set the encryption ov
	'salt' => '$1$ef0110101',									//Set the salt used for password
);

Security::init($security_config);
