<div class="container">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<p class="text-center">
				Login
			</p>
			
			<form  id="contactForm"method="post">
				
				
				<div class="form-group">
					<label>Email Address</label>
					<input type="email" class="form-control" maxlength="255" name="email" value="{{ user.email }}" />
				</div>
				
				<div class="form-group">
					<label>Password</label>
					<input type="password" class="form-control" maxlength="255" name="password" value="" />
				</div>
				<?php if($failed_login_attempts): ?>
					<p><strong>Failed Login Attempts:</strong> <?= $failed_login_attempts; ?></p>
				<?php endif; ?>
				<br>
				<div id="success"></div>
				<div class="form-group text-center">
					<button type="submit" class="btn btn-primary" id="sendMessageButton">
						Login
					</button>
				</div>
			</form>
		</div>
	</div>
</div>