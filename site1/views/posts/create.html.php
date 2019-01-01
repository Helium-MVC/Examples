<?php 
//We are injecting our js app into the html
$this->Meta->addJavascript('/js/components/posts.js'); 
?>
<div class="container mt-5">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<h1>Create A Post</h1>
			
			<form  id="postForm" enctype="multipart/form-data" v-on:submit.prevent="createPost" method="post" action="<?= prodigyview\network\Router::getCurrentUrl(); ?>">
				
				<?php include('_form.html.php'); ?>
				
				<input type="hidden" name="user_id" value="<?= $this -> Session -> get('user_id'); ?>" />
				
				<span v-html="validationMessage"></span>
				<div class="form-group text-center">
					<button type="submit" class="btn btn-primary" id="createPost">
						Create Post
					</button>
				</div>
			</form>
		</div>
	</div>
</div>