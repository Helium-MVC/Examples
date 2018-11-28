	<p>Best Regards,</p>
	Helium MVC Team<br />
	
	<?php if(isset($user) && $user && method_exists ( $user , 'getEmailUnsubscribeUrl' )): ?>
		<p style="text-align: center;"><a href="<?= $user -> getEmailUnsubscribeUrl(); ?>">Unsubscribe Or Set Email Options</a></p>
	<?php endif; ?>
	</body>
</html>