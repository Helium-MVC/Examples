<?php
/**
 * Configuration the session in this file. Sessions refer to both to sessions and cookies. The session variables
 * are assigned in the environments.php and are then assigned here.
 */

//Get the configuration set for the session. Configuration is set in the environments.php file
$session = PVConfiguration::getConfiguration('session');

$session_configuration = array(
	'cookie_lifetime' => $session -> cookie_lifetime,
	'session_lifetime' => $session -> cookie_lifetime,
	'session_name' => $session -> session_name.'_3',
	'hash_cookie' => $session -> hash_cookie,
	'session_domain' => 'site3'.$session -> session_domain,
	'cookie_domain' => 'site3'.$session -> cookie_domain
);


PVSession::init($session_configuration);

