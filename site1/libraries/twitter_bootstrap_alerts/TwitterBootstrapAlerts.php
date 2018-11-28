<?php

//This is a filter add is added to the template error message and success message. It negates
//the return thus altering the functions output
PVTemplate::addFilter('PVTemplate','errorMessage', 'TwitterBootstrapAlerts', 'errorAlert', array('event'=>'return'));
PVTemplate::addFilter('PVTemplate','successMessage', 'TwitterBootstrapAlerts', 'successAlert', array('event'=>'return'));

/**
 * Alert is a library that is loaded with the MVC Helium. The purpose of alert is to provide
 * messages that can persist between page changes.
 */
class TwitterBootstrapAlerts {
	
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
	public static function successAlert($message, $options) {
		
		$he2_alerts = PVSession::readSession('he2_alerts');
		
		if(empty($he2_alerts))
			$he2_alerts = array();		
		
		$he2_alerts[] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $message . '</div>';
		
		PVSession::writeSession('he2_alerts', $he2_alerts );
	}
	
	public static function errorAlert($message, $options) {
		
		$he2_alerts = PVSession::readSession('he2_alerts');
		
		if(empty($he2_alerts))
			$he2_alerts = array();		
		
		$he2_alerts[] = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $message . '</div>';
		
		PVSession::writeSession('he2_alerts', $he2_alerts );
	}
	
}
