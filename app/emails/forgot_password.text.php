Hi <?= $account -> account_first_name; ?> <?= $account -> account_last_name; ?>,

A password reset request has been submitted to the site. If you wish to reset your password, follow the link below:

<?= Configuration::getConfiguration('adbience_sites') -> main; ?>accounts/resetpassword/<?= $account -> account_id; ?>?token=<?= $account -> account_reset_token; ?>

If this request was not made by you, please disregard this message.

<?php include('signature.text.php'); ?>