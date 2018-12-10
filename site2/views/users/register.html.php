<div class="container">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<p class="text-center">
				Register To Our Blog
			</p>
			
			<form  id="contactForm"method="post" ng-controller="UsersCtrl" action="<?= PVTools::getCurrentUrl(); ?>">
				
				<div class="form-group">
					<label>First Name</label>
					<input type="text" class="form-control" maxlength="255" name="first_name" ng-model="data.first_name" value="<?= $user -> first_name; ?>" />
				</div>
				
				<div class="form-group">
					<label>Last Name</label>
					<input type="text" class="form-control" maxlength="255" name="last_name" ng-model="data.last_name" value="<?= $user -> last_name; ?>" />
				</div>
				
				<div class="form-group">
					<label>Email Address</label>
					<input type="email" class="form-control" maxlength="255" name="email" ng-model="data.email" value="<?= $user -> email; ?>" />
				</div>
				
				<div class="form-group">
					<label>Password</label>
					<input type="password" class="form-control" maxlength="255" name="password" ng-model="data.password" value="" />
				</div>
				
				<div class="form-group">
					<label>Agree To Terms of Service</label>
					<input type="checkbox" name="agree_to_terms" value="1" />
				</div>
				<br>
				<div ng-bind-html="validationMessage"></div>
				<!--Create CSRF Token For Security -->
				<?= $this->CSRF->getCSRFTokenInput(); ?>
				<div class="form-group text-center">
					<button type="submit" class="btn btn-primary" id="sendMessageButton" ng-click="register($event)">
						Create Account
					</button>
				</div>
			</form>
		</div>
	</div>
</div>