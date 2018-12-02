<?php
use app\factories\ServiceFactory;

/**
 * QueueCli
 * 
 * This class acts as a type of messenging system. It gets information from a queue and
 * sends it off. Services like this can exist on completely different systems.
 */
class QueueCli {
	
	/**
	 * Sends the welcome email to user who just registered
	 */
	public function sendWelcome() {
		
		$queue = ServiceFactory::get('queue');
		
		$data = $queue -> pop('sendWelcomeEmail');
		
		$email = ServiceFactory::get('email');
		
		$email -> sendActivationEmail($data -> user, $data -> site_url);
	}
	
}
