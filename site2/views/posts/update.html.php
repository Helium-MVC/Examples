<div class="container mt-5">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<h1>Update Post</h1>
			
			<form  id="postForm" enctype="multipart/form-data" method="post" ng-controller="PostsCtrl" ng-init="initSync('<?= $this -> Angular -> escape($post -> post_id); ?>')" action="<?= PVTools::getCurrentUrl(); ?>">
				
				<?php include('_form.html.php'); ?>
				
				<div ng-bind-html="validationMessage"></div>			
				<div class="form-group text-center">
					<button type="submit" class="btn btn-primary" id="updatePost" ng-click="update($event)">
						Update Post
					</button>
				</div>
			</form>
		</div>
	</div>
</div>