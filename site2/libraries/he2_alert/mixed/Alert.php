<?php

//This is a filter add is added to the template error message and success message. It negates
//the return thus altering the functions output
PVTemplate::addFilter('PVTemplate','errorMessage', 'Alert', 'addAlert', array('event'=>'return'));
PVTemplate::addFilter('PVTemplate','successMessage', 'Alert', 'addAlert', array('event'=>'return'));

/**
 * Alert is a library that is loaded with the MVC Helium. The purpose of alert is to provide
 * messages that can persist between page changes.
 */
class Alert {
	
	private static $alert_count = 0;
	
	/**
	 * Method takes the data filtered from PVTemplate::errorMessage and PVTemplate::successMessage
	 * and adds the output to a session that is later called in the template.
	 * 
	 * @param string $message The message passed to the addAlert
	 * @param array $options Options passed about the filter
	 * 
	 * @return void
	 * @access public
	 */
	public static function addAlert($message, $options) {
		
		$he2_alerts = PVSession::readSession('he2_alerts');
		
		if(empty($he2_alerts))
			$he2_alerts = array();		
		
		$he2_alerts[] = $message;
		
		PVSession::writeSession('he2_alerts', $he2_alerts );
	}
	
}
