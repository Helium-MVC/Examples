{% for post in posts %}
				<div class="post-preview">
					<a href="/posts/view/{{ post.post_id }}"> <h2 class="post-title"> {{ post.title }}</h2> <h3 class="post-subtitle">  {{ post.content }} </h3> </a>
					<p class="post-meta">
						Posted by <a href="/profile/{{ post.user_id }}">{{ post.user.first_name }} {{ post.user.last_name }}</a>
						on {{ post.date_created }}
					</p>
					{% if session.get('is_loggedin') and session.get('user_id') == post.user_id  %}
						<p>
							<a class="btn btn-info" href="/posts/view/{{ post.post_id }}"><i class="fas fa-eye"></i></a>
							<a class="btn btn-success" href="/posts/update/{{ post.post_id }}"><i class="fas fa-pencil-alt"></i></a>
							<a class="btn btn-danger" href="/posts/delete/{{ post.post_id }}"><i class="fas fa-trash-alt"></i></a>
							
						</p>
					{%  endif %}
				</div>
				<hr>
{% endfor %}