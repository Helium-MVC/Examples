<div class="container mt-5">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto" ng-controller="UsersCtrl" ng-init="initSync('<?= $this -> Angular -> escape($user -> user_id); ?>')">
			<h1 >
				Update Your Account
			</h1>
			<div class="card">
  				<div class="card-header">
    					Update Your Info
  				</div>
  				<div class="card-body">
  	
					<form  id="updateInfoForm" enctype="multipart/form-data" method="post" action="<?= PVTools::getCurrentUrl(); ?>">
						
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
							<input type="text" class="form-control" maxlength="255" name="first_name" ng-model="data.first_name" value="<?= $user -> first_name; ?>" />
						</div>
						
						<div class="form-group">
							<label>Last Name</label>
							<input type="text" class="form-control" maxlength="255" name="last_name" ng-model="data.last_name"  value="<?= $user -> last_name; ?>" />
						</div>
						
						<div class="form-group">
							<label>Github Account</label>
							<input type="text" class="form-control" maxlength="255" name="github_profile" ng-model="data.github_profile"  value="<?= $user -> github_profile; ?>" />
						</div>
						
						<div class="form-group">
							<label>Bio</label>
							<wysiwyg textarea-id="content_" ng-model="data.bio" textarea-class="form-control"  textarea-height="150px" action-tracker="form_media_description" textarea-name="textareaQuestion" textarea-required enable-bootstrap-title="true" textarea-menu="wysiwig_options"  ></wysiwyg>
							<!-- Hides when angular is activated -->
							<textarea type="text" class="form-control" name="bio" ng-show="false" ><?= $user -> bio; ?></textarea>
						</div>
						
						
						<br>
						<div ng-bind-html="updateInfoMessage"></div>
						<?= $this->CSRF->getCSRFTokenInput(); ?>
						<div class="form-group text-center">
							<button type="submit" name="update_profile" class="btn btn-primary" ng-click="updateInfo($event)" >
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
					<form  id="updateEmailForm"method="post" action="<?= PVTools::getCurrentUrl(); ?>">
						
						<p><strong>Current Email:</strong> <?= $user -> email; ?></p>
						<div class="form-group">
							<label>New Email</label>
							<input type="email" class="form-control" maxlength="255" name="email" ng-model="data.email" value="" />
						</div>
						
						<br>
						<div ng-bind-html="updateEmailMessage"></div>
						<div class="form-group text-center">
							<button type="submit" name="update_email" class="btn btn-primary" ng-click="updateEmail($event)" >
								Update Email
							</button>
						</div>
						<?= $this->CSRF->getCSRFTokenInput(); ?>
					</form>
				</div>
			</div>
			<hr />
			
			<div class="card">
  				<div class="card-header">
    					Update Your Password
  				</div>
  				<div class="card-body">
					<form  id="updatePasswordForm"method="post" action="<?= PVTools::getCurrentUrl(); ?>">
						
						<div class="form-group">
							<label>New Password</label>
							<input type="password" class="form-control" maxlength="255" name="user_password" ng-model="data.user_password" value="" />
						</div>
						
						<br>
						<div ng-bind-html="updatePasswordMessage"></div>
						<?= $this->CSRF->getCSRFTokenInput(); ?>
						<div class="form-group text-center">
							<button type="submit" name="update_password" class="btn btn-primary" ng-click="updatePassword($event)" >
								Update Password
							</button>
						</div>
					</form>
				</div>
			</div>
			<!--Create CSRF Token For Security -->
			<?= $this->CSRF->getCSRFTokenInput(); ?>
		</div>
	</div>
</div>