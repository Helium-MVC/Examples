
{% if post.image_id %}
	<header class="masthead" style="background-image: url('{{ this.Format.parseImage(post.image_large_url) }}')">
		<div class="overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-md-10 mx-auto">
					<div class="post-heading">
						<h1>{{ post.title }}</h1>
						<h2 class="subheading">{{ post.subheading }}</h2>
						<span class="meta">Posted by <a href="/profile/{{ post.user_id }}">{{ post.first_name }} {{ post.user_last }}</a> on {{ format.dateTime(post.date_created) }}</span>
					</div>
				</div>
			</div>
		</div>
	</header>
{% endif %}

<article class="{{ (not post.image_id) ? 'mt-5' : '' }}">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-md-10 mx-auto">
				{% if this.Session.get('user_id') == post.user_id %}
					<a class="btn btn-success" href="/posts/update/{{ post.post_id }}"><i class="fas fa-pencil-alt"></i> Update</a>
				{% endif %}
				
				{% if not post.image_id %}
					<h1>{{ post.title }}</h1>
					<small><span class="meta">Posted by <a href="/profile/{{ post.user_id }}">{{ post.first_name }} {{ post.user_last }}</a> on {{ format.dateTime(post.date_created) }}</span></small>
				{% endif %}
				<div class="content">
					{{ post.content }}
				</div>
				
				{% if session.get('is_loggedin')==1 %}
					<div class="card">
		  				<div class="card-header">
		    					Leave A Comment
		  				</div>
		  				<div class="card-body">
							<form  id="leaveComment"method="post" >
								
								<div class="form-group">
									<textarea name="comment" class="form-control" rows="7"></textarea>
								</div>
								
								<br>
								<input type="hidden" name="post_id" value="{{ post.post_id }}" />
								<input type="hidden" name="user_id" value="{{ this.Session.get('user_id') }}" />
								<div class="form-group text-center">
									<button type="submit" name="update_email" class="btn btn-primary" id="sendMessageButton">
										Submit
									</button>
								</div>
							</form>
						</div>
					</div>
					<br />
				{% endif %}
				
				{% if post.comments %}
					<h4>Comments</h4>
					<hr />	
					
					{% for comment in post.comments %}
						<div class="media">
						  
						  <div class="media-body">
						    <h5 class="mt-0">{{ comment.user.first_name }} {{ comment.user.last_name }} said...</h5>
						    {{ comment.comment }}
						  </div>
						</div>
					{% endfor %}
				{% endif %}
				
			</div>
		</div>
	</div>
</article>