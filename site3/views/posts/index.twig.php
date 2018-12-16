
<!-- Main Content -->
<div class="container mt-5">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<a href="/posts/create" class="btn btn-success"><i class="fas fa-plus"></i> Write A Post</a>
			{% for post in posts %}
				<div class="post-preview">
					<a href="/posts/view/{{ post.post_id }}"> <h2 class="post-title"> {{ post.title }}</h2> <h3 class="post-subtitle">  {{ post.content }} </h3> </a>
					<p class="post-meta">
						Posted by <a href="/profile/{{ post.user_id }}">{{ post.user.first_name }} {{ post.user.last_name }}</a>
						on {{ post.date_created }} --  <span class="badge badge-secondary">{{ (post.comments) ? post.comments.length : 0 }}</span> comments
					</p>
				</div>
				<hr>
			{% endfor %}
			
		</div>
	</div>
</div>

<hr>
