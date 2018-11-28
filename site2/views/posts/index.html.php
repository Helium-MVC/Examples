
<!-- Main Content -->
<div class="container mt-5">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<a href="/posts/create" class="btn btn-success"><i class="fas fa-plus"></i> Write A Post</a>
			<?php foreach($posts as $post): ?>
				<div class="post-preview">
					<a href="/posts/view/<?= $post -> post_id; ?>"> <h2 class="post-title"> <?= $post -> title; ?></h2> <h3 class="post-subtitle"> <?= PVTools::truncateText($post -> content, 300); ?> </h3> </a>
					<p class="post-meta">
						Posted by <a href="/profile/<?= $post -> user_id; ?>"><?= $post -> first_name; ?> <?= $post -> last_name; ?></a>
						on <?= $this -> Format -> dateTime($post -> date_created); ?> --  <span class="badge badge-secondary"><?= $post -> comment_count; ?></span> comments
					</p>
				</div>
				<hr>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<hr>
