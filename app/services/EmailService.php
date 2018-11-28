<?php
/**
 * EmailService
 * 
 * The email service is responsible for sending out emails to users. It should wrap the librrary
 * used to send email. A future possible implementation might include dependency injection.
 * 
 * Example Usage:
 * 
 * $email = new EmailService();
 * $email -> sendInviteUser($user);
 * 
 */
namespace app\services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {
	
	protected $_mailer = null;
	
	/**
	 * The Constructor
	 */
    public function __construct(){
		$this -> _mailer = new PHPMailer;
		
		$this -> _setAuthDetails();
		$this -> _setSendDetails();
	}
	
	/**
	 * _setSendDetails
	 * 
	 * Sets up the information for which SMTP service to use and dfault
	 * sender information
	 */
	protected function _setSendDetails(){
		$this -> _mailer->isSMTP();  
		$this -> _mailer->Host = \PVConfiguration::getConfiguration('mail') -> host;
		$this -> _mailer->Port = \PVConfiguration::getConfiguration('mail') -> port;
		$this -> _mailer ->isHTML(true);
		$this -> _mailer->SMTPSecure = 'tls';
		
		//Default Sending Information
		$this -> _mailer->From = \PVConfiguration::getConfiguration('mail') -> from_address;
		$this -> _mailer->FromName = \PVConfiguration::getConfiguration('mail') -> from_name; 
	}

	/**
	 * _setAuthDetails
	 * 
	 * Setups up the information for authenticating against the
	 * sender service
	 */
	protected function _setAuthDetails(){
		$this -> _mailer->SMTPAuth = true; 
		$this -> _mailer->Username = \PVConfiguration::getConfiguration('mail') -> login;
		$this -> _mailer->Password = \PVConfiguration::getConfiguration('mail') -> password;   
	}
	
	/**
	 * _sendEmail
	 * 
	 * Sends the email, record errors and remove receipts
	 * so more emails can be sent.
	 * 
	 */
	protected function _sendEmail($options = array()) {
		
		$status = true;
		
		if(!$this -> _mailer ->send()) {
			LoggingService::logsServiceAction($this, $this -> _mailer ->ErrorInfo, $options);
			
			$status = false;  
		} 
		
		LoggingService::logEmail($this -> _mailer, $status, $options);
		
		$this -> _mailer ->ClearAllRecipients( );
		
		return $status;
	}

	
	/**
	 * Sends a email to reset the email for the user.
	 * 
	 * @param Accounts $user An object of the users password to reset
	 * 
	 * @return boolean Status of the email sent
	 */
	public function sendPasswordReset($user) {
		$data = array('user_reset_token' => \PVTools::generateRandomString(15));
		
		$user -> update($data);
		
		$this -> _mailer -> AddAddress($user -> email, $user -> first_name . ' ' . $user -> last_name);
		
		$this -> _mailer -> Subject = 'Password Reset Request';
		$this -> _mailer ->Body = \MailLoader::loadHtml('forgot_password', array('user' => $user));
		$this -> _mailer ->AltBody = \MailLoader::loadText('forgot_password', array('user' => $user));
		
		return $this -> _sendEmail(array('mail_type' => 'forgot_password'));
	}

	/**
	 * Send an activation email to a new user which will have them complete their registration.
	 * 
	 * @param User $account The account that has signed up
	 * @param string $site_url The site to go back to for the notifcation
	 * 
	 * @return boolean
	 */
	public function sendActivationEmail($account, $site_url) {
			
		$this -> _mailer->AddAddress($account -> email, $account -> first_name . ' ' . $account -> last_name);
			
		$this -> _mailer->Subject = 'Account Activation Required';
		$this -> _mailer->Body = \MailLoader::loadHtml('activation_email', array('account' => $account, 'site_url' => $site_url));
		$this -> _mailer->AltBody = \MailLoader::loadText('activation_email', array('account' => $account, 'site_url' => $site_url));
			
		return $this -> _sendEmail(array('mail_type' => 'activation_email'));	
	}
	
	/**
	 * Send an .email when a user leaves a comment on a post
	 * 
	 * @param Comments $comment
	 * @param Posts $post
	 * @param Users $poster
	 * @param Users $commenter
	 * 
	 * @return boolean
	 */
	public function sendPostComment($comment, $post, $poster, $commenter) {
			
		$this -> _mailer->AddAddress($poster -> email, $poster -> first_name . ' ' . $poster -> last_name);
			
		$this -> _mailer->Subject = $commenter -> first_name . ' has commented on your post ' . $post -> title;
		$this -> _mailer->Body = \MailLoader::loadHtml('post_comment', array('comment' => $comment, 'post' => $post, 'poster' => $poster, 'commenter' => $commenter));
		$this -> _mailer->AltBody = \MailLoader::loadText('post_comment', array('comment' => $comment, 'post' => $post, 'poster' => $poster, 'commenter' => $commenter));
			
		return $this -> _sendEmail(array('mail_type' => 'post_comment'));	
	}
	
}