<div class="container mt-5">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<h1>Delete Post</h1>
			
			<form  id="postForm" enctype="multipart/form-data" method="post" action="<?= PVTools::getCurrentUrl(); ?>">
				
				<p class="lead"> Are you sure you want to delete the post <strong>'<?= $post -> title; ?>'</strong></p>
								
				<div class="form-group text-center">
					<button type="submit" class="btn btn-primary" name="yes">
						Yes
					</button>
					<button type="submit" class="btn btn-danger" name="no">
						No
					</button>
				</div>
				
				<!-- CSFR Token For Security -->
				<input type="hidden" name="delete_token" value="<?= $this -> CSRF -> getCSRFTokenInput('post_token'); ?>" />
			</form>
		</div>
	</div>
</div>