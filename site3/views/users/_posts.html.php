<?php foreach($posts as $post): ?>
				<div class="post-preview">
					<a href="/posts/view/<?= $post -> post_id; ?>"> <h2 class="post-title"> <?= $post -> title; ?></h2> <h3 class="post-subtitle"> <?= prodigyview\util\Tools::truncateText($post -> content, 200); ?> </h3> </a>
					<p class="post-meta">
						Posted on <?= $this -> Format -> dateTime($post -> date_created); ?>
					</p>
					<?php if($this->Session ->get('is_loggedin') && $this->Session ->get('user_id') == $post -> user_id ): ?>
						<p>
							<a class="btn btn-info" href="/posts/view/<?= $post -> post_id; ?>"><i class="fas fa-eye"></i></a>
							<a class="btn btn-success" href="/posts/update/<?= $post -> post_id; ?>"><i class="fas fa-pencil-alt"></i></a>
							<a class="btn btn-danger" href="/posts/delete/<?= $post -> post_id; ?>"><i class="fas fa-trash-alt"></i></a>
							
						</p>
					<?php endif; ?>
				</div>
				<hr>
<?php endforeach; ?>