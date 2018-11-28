<?php 
//We are injecting our js app into the html
PVLibraries::enqueueJavascript('components/accounts.js'); 
?>
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
  	
					<form  id="updateInfoForm" enctype="multipart/form-data" method="post" v-on:submit.prevent="updateInfo" action="<?= PVTools::getCurrentUrl(); ?>">
						
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
							<input type="text" class="form-control" maxlength="255" name="first_name" v-model="first_name" value="<?= $user -> first_name; ?>" />
						</div>
						
						<div class="form-group">
							<label>Last Name</label>
							<input type="text" class="form-control" maxlength="255" name="last_name" v-model="last_name" value="<?= $user -> last_name; ?>" />
						</div>
						
						<div class="form-group">
							<label>Github Account</label>
							<input type="text" class="form-control" maxlength="255" name="github_profile" v-model="github_profile" value="<?= $user -> github_profile; ?>" />
						</div>
						
						<div class="form-group">
							<label>Bio</label>
							<vue-mce v-model="bio" :config="tinymceConfig" />
							<!-- If Vue is enabled, does not display -->
							<textarea type="text" class="form-control" name="bio" v-if="false" ><?= $user -> bio; ?></textarea>
						</div>
						
						
						<br>
						<span v-html="updateInfoMessage"></span>
						<div class="form-group text-center">
							<button type="submit" name="update_profile" class="btn btn-primary" >
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
					<form  id="updateEmailForm"method="post" v-on:submit.prevent="updateEmail" action="<?= PVTools::getCurrentUrl(); ?>">
						
						<p><strong>Current Email:</strong> <?= $user -> email; ?></p>
						<div class="form-group">
							<label>New Email</label>
							<input type="email" class="form-control" maxlength="255" v-model="email" name="email" value="" />
						</div>
						
						<br>
						<span v-html="updateEmailMessage"></span>
						<div class="form-group text-center">
							<button type="submit" name="update_email" class="btn btn-primary" >
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
					<form  id="updatePasswordForm" method="post" v-on:submit.prevent="updatePassword" action="<?= PVTools::getCurrentUrl(); ?>">
						
						<div class="form-group">
							<label>New Password</label>
							<input type="password" class="form-control" maxlength="255" v-model="password" name="user_password" value="" />
						</div>
						
						<br>
						<span v-html="updatePasswordMessage"></span>
						<div class="form-group text-center">
							<button type="submit" name="update_password" class="btn btn-primary" >
								Update Password
							</button>
						</div>
					</form>
				</div>
			</div>
			<input type="hidden" name="user_id" id="user_id" value="<?= $user -> user_id; ?>" />
		</div>
	</div>
</div>