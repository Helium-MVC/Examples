<?php

/**
 * ShowAlert is a class that is callable in the template section because it exist in the template section
 * of the extensions. This means that this class is designed to be used in any of the views.
 */
class ShowAlert extends PVObject {
	
	public function __construct(){
		
	}

	/**
	 * Displays alerts message, if any have added to the session,
	 *
	 * @return void
	 * @access public
	 */
	public function showAlert() {
		
		$he2_alerts = PVSession::readSession('he2_alerts');
		
		if(!empty($he2_alerts)) {
			
			foreach($he2_alerts as $alert)
				echo $alert;	
		}
		
		PVSession::writeSession('he2_alerts', '' );
	}

}
