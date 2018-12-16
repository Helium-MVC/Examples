
<!-- Main Content -->
<div class="container mt-5">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<a href="/posts/create" class="btn btn-success"><i class="fas fa-plus"></i> New Post</a>
			<br /><br />
			<h3>My Posts</h3>
			
			{% include 'users/_posts.html.php'  %}
			
			{% if !$posts  %}
				<br />
				<div class="lead text-center">
					You have not written any posts. Are you ready to write your first one?
				</div>
				<br /><br />
			{% endif  %}
			
			
		</div>
	</div>
</div>

<hr>
