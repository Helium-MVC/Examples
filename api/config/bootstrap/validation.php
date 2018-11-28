<?php
/**
 * The validator is used for validation of input data. Initialize, configure and add custom rules
 * to the validator below. Validation is primarly used in conjunction with a model but can also
 * be used in standalone validation.
 * 
 */

//Initialize validation class
PVValidator::init(array());

/**
 * Compares what should be passed is an of a user and compares it the id of the user
 * that currently has a session in the cookie
 */
$differentUser = function($value) {
	return !($value == PVSession::readCookie('user_id'));
};

PVValidator::addRule('differentUser', array('function' => $differentUser));


/**
 * Cast the passed value to an array if it is not already one, and the checks to make
 * sure that the array is not empty.
 */
PVValidator::addRule('notEmptyArray', array('function' => function(array $value) {
	return !empty($value);
}));


/**
 * Validation rule that checks to make sure the uploaded image is an image. Retrieves the mime type
 * of the image using PVFileManager::getMimeType, and uses PVValidator::isImageFile that checks
 * mime types.
 */
PVValidator::addRule('checkImageUpload', array('function' => function($file){
	
	$validation = false;
	
	if($file['size'] > 0 && PVValidator::isImageFile(PVFileManager::getFileMimeType($file['tmp_name'])))
		$validation = true;
	
	return $validation;
}));

/**
 * In the lengthCheck example, the validation is defined directly in the 'addRule' method.
 * This validation takes in options that would be sent from the model if specified.
 */
PVValidator::addRule('lenghtCheck', array('function' => function($string, $options) {
	$valid = true;
	
	if(isset($options['min']) && strlen($string) < $options['min'])
		$valid = false;
	
	if(isset($options['max']) && strlen($string) > $options['max'])
		$valid = false;
		
	return $valid;
}));


/**
 * Add a rule to ensure that the passed value does not equal zero. Used to different the
 * difference between empty and zero
 */
PVValidator::addRule('notzero', array('function' => function($value) {
	if($value != 0)
		return true;
	
	return false;
}));


/**
 * Checks to see if the file has an image extension.
 */
PVValidator::addRule('is_image_file', array('function' => function($file) {
	
	if(!file_exists($file))
		return false;
	
	$mime_type = PVFileManager::getFileMimeType($file);
	
	return PVValidator::isImageFile($mime_type);
}));







