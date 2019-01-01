<div class="container mt-5">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<h1 >
				Update Your Account
			</h1>
			<div class="card">
  				<div class="card-header">
    					Update Your Info
  				</div>
  				<div class="card-body">
  	
					<form  id="updateInfoForm" enctype="multipart/form-data" method="post" action="<?= prodigyview\network\Router::getCurrentUrl(); ?>">
						
						<?php if($user -> image_id): ?>
							<div class="text-center">
								<img src="<?= $this -> Format -> parseImage($user -> image_large_url); ?>" />
							</div>
						<?php endif; ?>
						<div class="form-group">
							<label>Profile Image</label>
							<input type="file" name="profile_image" accept="img/*" />
						</div>

						<div class="form-group">
							<label>First Name</label>
							<input type="text" class="form-control" maxlength="255" name="first_name" value="<?= $user -> first_name; ?>" />
						</div>
						
						<div class="form-group">
							<label>Last Name</label>
							<input type="text" class="form-control" maxlength="255" name="last_name" value="<?= $user -> last_name; ?>" />
						</div>
						
						<div class="form-group">
							<label>Github Account</label>
							<input type="text" class="form-control" maxlength="255" name="github_profile" value="<?= $user -> github_profile; ?>" />
						</div>
						
						<div class="form-group">
							<label>Bio</label>
							<textarea type="text" class="form-control" name="bio"><?= $user -> bio; ?></textarea>
						</div>
						
						
						<br>
						<div class="form-group text-center">
							<button type="submit" name="update_profile" class="btn btn-primary" id="sendMessageButton">
								Update Info
							</button>
						</div>
					</form>
				</div>
			</div>
			<hr />
			<div class="card">
  				<div class="card-header">
    					Update Your Email
  				</div>
  				<div class="card-body">
					<form  id="updateEmailForm"method="post" action="<?= prodigyview\network\Router::getCurrentUrl(); ?>">
						
						<p><strong>Current Email:</strong> <?= $user -> email; ?></p>
						<div class="form-group">
							<label>New Email</label>
							<input type="email" class="form-control" maxlength="255" name="email" value="" />
						</div>
						
						<br>
						<div class="form-group text-center">
							<button type="submit" name="update_email" class="btn btn-primary" id="sendMessageButton">
								Update Email
							</button>
						</div>
					</form>
				</div>
			</div>
			<hr />
			
			<div class="card">
  				<div class="card-header">
    					Update Your Password
  				</div>
  				<div class="card-body">
					<form  id="updatePasswordForm"method="post" action="<?= prodigyview\network\Router::getCurrentUrl(); ?>">
						
						<div class="form-group">
							<label>New Password</label>
							<input type="password" class="form-control" maxlength="255" name="user_password" value="" />
						</div>
						
						<br>
						<div class="form-group text-center">
							<button type="submit" name="update_password" class="btn btn-primary" id="sendMessageButton">
								Update Password
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>