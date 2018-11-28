Hi <?= $account -> first_name; ?> <?= $account -> last_name; ?>,

Thank you for registering. Before you are able to engage, you must first activate your account using the link below.

<?= $site_url ?>users/activate/<?=$account -> user_id; ?>?token=<?= $account -> activation_token; ?>

Looking forward to having you onboard.

<?php include('_signature.text.php'); ?>