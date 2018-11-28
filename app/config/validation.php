<?php
/**
 * Custom Validation Rules
 * 
 * The models uses PVValidator for check input, but PVValidator is limited. Below are
 * examples of how to add customized validated rules that can be checked against
 * in the models.
 */
use app\models\uuid\Users;


/**
 * Checks to make sure a field is either empty or a complete url.
 */
PVValidator::addRule('url_allow_empty', array('function' => function($url) {
	
	$url = trim($url);
	
	if(!$url) {
		return true;
	}

	return PVValidator::isValidUrl($url);
	
}));

/**
 * Checks the database to see if a user has already
 * registered with the account.
 */
PVValidator::addRule('unique_email', array('function' => function($email) {

	$email = strtolower(trim($email));
	
	$user = new Users();
	$conditions = array('email' => $email);
	$user->first(compact('conditions'));
	
	if(!$user-> user_id)
		return true;
	
	return false;
}));

/**
 * Checks to ensure if an integer  or if the value is empty.
 */
PVValidator::addRule('integer_not_required', array('function' => function($integer) {

	if(!$integer) {
		return true;
	}
	
	return PVValidator::isInteger($integer);
}));

/**
 * Checks to ensure if an double  or if the value is empty.
 */
PVValidator::addRule('double_not_required', array('function' => function($integer) {

	if(!$integer) {
		return true;
	}
	
	return PVValidator::isDouble($integer);
}));

PVValidator::addRule('is_image_file', array('function' => function($file) {
	
	if(!file_exists($file))
		return false;
	
	$mime_type = PVFileManager::getFileMimeType($file);
	
	return PVValidator::isImageFile($mime_type);
}));


PVValidator::addRule('active_user', array('function' => function($email) {

	return Session::read('account_active');
}));

/*Checks to esure an input is of the minimum length*/
PVValidator::addRule('min_length', array('function' => function($value, $options) {
	
	if(isset($options['min'])){
		if(strlen($value) > $options['min']) {
			return true;
		}
	}
	
	return false;
}));

/**
 * Checks if the value is a currency.
 */
PVValidator::addRule('is_currency', array('function' => function($number) {

	return preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $number);
}));





