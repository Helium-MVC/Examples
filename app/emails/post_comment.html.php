<?php include('_header.html.php'); ?>

<p>Hi <?= $poster -> first_name; ?></p>

<p><?= $commenter -> first_name; ?> <?= $commenter -> last_name; ?> has left a comment on your post:</p>

<br />
<p><i><?= $comment -> comment; ?></i></p>

<p>Please visit the site to respond.</p>

<?php include('_footer.html.php'); ?>

