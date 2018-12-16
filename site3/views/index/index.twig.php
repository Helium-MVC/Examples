<!-- Page Header -->
<header class="masthead" style="background-image: url('/img/backgrounds/nesa-by-makers-708224-unsplash.jpg')">
	<div class="overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-md-10 mx-auto">
				<div class="site-heading">
					<h1>{{ SITE_TITLE }}</h1>
					<span class="subheading">Rapid Development Minimalist Framework with Firebase + Microservices</span>
				</div>
			</div>
		</div>
	</div>
</header>

<!-- Main Content -->
<div class="container">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			{% for post in posts %}
				<div class="post-preview">
					<a href="/posts/view/{{ post.post_id }}"> <h2 class="post-title"> {{ post.title }}</h2> <h3 class="post-subtitle">  {{ post.content }} </h3> </a>
					<p class="post-meta">
						Posted by <a href="/profile/{{ post.user_id }}">{{ post.user.first_name }} {{ post.user.last_name }}</a>
						on {{ post.date_created }}
					</p>
				</div>
				<hr>
			{% endfor %}
		</div>
	</div>
</div>

<hr>
