
<div class="container mt-5">
	{% if session.get('user_id') == user.user_id %}
		<a class="btn btn-success" href="/users/account/<?= $user -> user_id; ?>"><i class="fas fa-pencil-alt"></i> Update</a>
	{% endif %}
	<div class="row ">
		<div class="col-sm-3">
			{% if user.image_id %}
				<div class="text-center" >
					<img class="img-fluid" src="{{ format.parseImage(user.image_medium_url) }}" />
				</div>
			{% endif %}
		</div>
		<div class="col-sm-9">
			<h1>{{ user.first_name }} {{ user.last_name }}</h1>
			{{ user.bio }} 
			
			<hr />
			{% include 'users/_posts.twig.php' %}
		</div>
	</div>
</div>