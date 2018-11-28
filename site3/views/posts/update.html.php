<div class="container mt-5">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<h1>Update Post</h1>
			
			<form  id="postForm" enctype="multipart/form-data" method="post" action="<?= PVTools::getCurrentUrl(); ?>">
				
				<?php include('_form.html.php'); ?>
								
				<div class="form-group text-center">
					<button type="submit" class="btn btn-primary" id="updatePost">
						Update Post
					</button>
				</div>
			</form>
		</div>
	</div>
</div>