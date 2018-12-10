<div class="container mt-5">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<p>
				Want to get in touch? Fill out the form below to send me a message and I will get back to you as soon as possible!
			</p>
			
			<form  id="contactForm" method="post" action="<?= PVTools::getCurrentUrl(); ?>">
				<div class="control-group">
					<div class="form-group floating-label-form-group controls">
						<label>Name</label>
						<input type="text" class="form-control" maxlength="255" placeholder="Name" id="name" name="name" required="" data-validation-required-message="Please enter your name.">
						<p class="help-block text-danger"></p>
					</div>
				</div>
				<div class="control-group">
					<div class="form-group floating-label-form-group controls">
						<label>Email Address</label>
						<input type="email" class="form-control" maxlength="255" placeholder="Email Address" id="email" name="email" required="" data-validation-required-message="Please enter your email address.">
						<p class="help-block text-danger"></p>
					</div>
				</div>
				<div class="control-group">
					<div class="form-group col-xs-12 floating-label-form-group controls">
						<label>Phone Number</label>
						<input type="tel" class="form-control" maxlength="255" placeholder="Phone Number" id="phone" required="" name="phone" data-validation-required-message="Please enter your phone number.">
						<p class="help-block text-danger"></p>
					</div>
				</div>
				<div class="control-group">
					<div class="form-group floating-label-form-group controls">
						<label>Message</label>
						<textarea rows="5" class="form-control" placeholder="Message" id="message" name="message" required="" data-validation-required-message="Please enter a message."></textarea>
						<p class="help-block text-danger"></p>
					</div>
				</div>
				<br>
				<div id="success"></div>
				<!--Create CSRF Token For Security -->
				<?= $this->CSRF->getCSRFTokenInput(); ?>
				<div class="form-group">
					<button type="submit" class="btn btn-primary" id="sendMessageButton">
						Send
					</button>
				</div>
			</form>
		</div>
	</div>
</div>