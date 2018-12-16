<div class="container mt-5">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<h1>Delete Post</h1>
			
			<form  id="postForm" enctype="multipart/form-data" method="post" >
				
				<p class="lead"> Are you sure you want to delete the post <strong>'{{ post.title }}'</strong></p>
								
				<div class="form-group text-center">
					<button type="submit" class="btn btn-primary" name="yes">
						Yes
					</button>
					<button type="submit" class="btn btn-danger" name="no">
						No
					</button>
				</div>
			</form>
		</div>
	</div>
</div>