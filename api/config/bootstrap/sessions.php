<?php
/**
 * Configuration the session in this file. Sessions refer to both to sessions and cookies. The session variables
 * are assigned in the environments.php and are then assigned here.
 */

//Get the configuration set for the session. Configuration is set in the environments.php file
$session = PVConfiguration::getConfiguration('session');

if(@array_shift((explode('.',$_SERVER['HTTP_HOST']))) != parse_url ( PVConfiguration::getConfiguration('sites') -> main , PHP_URL_HOST)) {
	$session_configuration = array(
		'cookie_lifetime' => $session -> cookie_lifetime,
		'session_lifetime' => $session -> cookie_lifetime,
		'session_name' => $session -> session_name,
		'hash_cookie' => $session -> hash_cookie,
		'session_domain' => substr($_SERVER['HTTP_HOST'], strpos($_SERVER['HTTP_HOST'], '.')),
		'cookie_domain' => substr($_SERVER['HTTP_HOST'], strpos($_SERVER['HTTP_HOST'], '.'))
	);
} else {
	$session_configuration = array(
		'cookie_lifetime' => $session -> cookie_lifetime,
		'session_lifetime' => $session -> cookie_lifetime,
		'session_name' => $session -> session_name,
		'hash_cookie' => $session -> hash_cookie,
		'session_domain' => $session -> session_domain,
		'cookie_domain' => $session -> cookie_domain
	);
}

PVSession::init($session_configuration);

