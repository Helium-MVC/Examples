<!-- Page Header -->
<header class="masthead" style="background-image: url('/img/backgrounds/markus-spiske-771011-unsplash.jpg')">
	<div class="overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-md-10 mx-auto">
				<div class="site-heading">
					<h1><?= prodigyview\template\Template::getSiteTitle(); ?></h1>
					<span class="subheading">Rapid Development Minimalist Framework with VueJS + MySQL</span>
				</div>
			</div>
		</div>
	</div>
</header>

<!-- Main Content -->
<div class="container">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<?php foreach($posts as $post): ?>
				<div class="post-preview">
					<a href="/posts/view/<?= $post -> post_id; ?>"> <h2 class="post-title"> <?= $post -> title; ?></h2> <h3 class="post-subtitle"> <?= prodigyview\util\Tools::truncateText($post -> content); ?> </h3> </a>
					<p class="post-meta">
						Posted by <a href="/profile/<?= $post -> user_id; ?>"><?= $post -> first_name; ?> <?= $post -> last_name; ?></a>
						on <?= $this -> Format -> dateTime($post -> date_created); ?>
					</p>
				</div>
				<hr>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<hr>
