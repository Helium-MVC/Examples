Best Regards,
Helium MVC Team
	
<?php if($user && method_exists ( $user , 'getEmailUnsubscribeUrl' )): ?>
Unsubscribe Or Set Email Options: <?= $user -> getEmailUnsubscribeUrl(); ?>
<?php endif; ?>