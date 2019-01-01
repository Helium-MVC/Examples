<?php

$this->Meta->setTitle($post -> title);
 
$this->Meta->appendTags('<meta name="description" content="'. $this -> Format -> ogTag(prodigyview\util\Tools::truncateText($post -> content, 100)) .'" />');

$this->Meta->appendTags('<meta property="og:title" content="'. $this -> Format -> ogTag($post -> title).' "/>');
$this->Meta->appendTags('<meta property="og:description" content="'. $this -> Format -> ogTag(prodigyview\util\Tools::truncateText($post -> content, 100)) .'">');
$this->Meta->appendTags('<meta property="og:url" content="' . $this->Navigation->getUrl() .'"/>');
$this->Meta->appendTags('<meta property="og:site_name" content="Helium MVC"/>');
$this->Meta->appendTags('<meta property="og:type" content="website"/>');
if($post -> image_id):
	$this->Meta->appendTags('<meta property="og:image" content="'. $this -> Format -> parseImage($post -> image_large_url) .'" />');
 endif;

$this->Meta->appendTags('<meta name="twitter:card" content="summary">');
$this->Meta->appendTags('<meta name="twitter:site" content="@he2mvc">');
$this->Meta->appendTags('<meta name="twitter:creator" content="@he2mvc">');
$this->Meta->appendTags('<meta name="twitter:url" content="' . $this->Navigation->getUrl()  . '">');
$this->Meta->appendTags('<meta name="twitter:title" content="'. $this -> Format -> ogTag($post -> title).'">');
$this->Meta->appendTags('<meta name="twitter:description" content="'. $this -> Format -> ogTag(prodigyview\util\Tools::truncateText($post -> content, 100)) .'">');

?>

<?php if($post -> image_id): ?>
	<header class="masthead" style="background-image: url('<?= $this -> Format -> parseImage($post -> image_large_url); ?>')">
		<div class="overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-md-10 mx-auto">
					<div class="post-heading">
						<h1><?= $post -> title; ?></h1>
						<h2 class="subheading"><?= $post -> subheading; ?></h2>
						<span class="meta">Posted by <a href="/profile/<?= $post -> user_id; ?>"><?= $post -> first_name; ?> <?= $post -> user_last; ?></a> on <?= $this -> Format ->dateTime($post -> date_created); ?></span>
					</div>
				</div>
			</div>
		</div>
	</header>
<?php endif; ?>

<article class="<?= (!$post -> image_id) ? 'mt-5' : ''; ?>">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-md-10 mx-auto">
				<?php if($this -> Session -> get('user_id') == $post -> user_id): ?>
					<a class="btn btn-success" href="/posts/update/<?= $post-> post_id; ?>"><i class="fas fa-pencil-alt"></i> Update</a>
				<?php endif; ?>
				
				<?php if(!$post -> image_id): ?>
					<h1><?= $post -> title; ?></h1>
					<small><span class="meta">Posted by <a href="/profile/<?= $post -> user_id; ?>"><?= $post -> first_name; ?> <?= $post -> user_last; ?></a> on <?= $this -> Format ->dateTime($post -> date_created); ?></span></small>
				<?php endif; ?>
				<?= $post -> content; ?>
				
				<?php if($this -> Session -> get('is_loggedin')): ?>
					<div class="card">
		  				<div class="card-header">
		    					Leave A Comment
		  				</div>
		  				<div class="card-body">
							<form  id="leaveComment"method="post" action="<?= $this->Navigation->getUrl(); ?>">
								
								<div class="form-group">
									<textarea name="comment" class="form-control" rows="7"></textarea>
								</div>
								
								<br>
								<input type="hidden" name="post_id" value="<?= $post -> post_id; ?>" />
								<input type="hidden" name="user_id" value="<?= $this -> Session -> get('user_id'); ?>" />
								<!--Create CSRF Token For Security -->
								<?= $this->CSRF->getCSRFTokenInput(); ?>
								<div class="form-group text-center">
									<button type="submit" name="update_email" class="btn btn-primary" id="sendMessageButton">
										Submit
									</button>
								</div>
							</form>
						</div>
					</div>
					<br />
				<?php endif; ?>
				
				<?php if($comments): ?>
					<h4>Comments</h4>
					<hr />	
				<?php endif; ?>
				
				<?php foreach($comments as $comment): ?>
					<div class="media">
					  
					  <div class="media-body">
					    <h5 class="mt-0"><?= $comment -> first_name; ?> <?= $comment -> last_name; ?> said...</h5>
					    <?= $comment -> comment; ?>
					  </div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</article>