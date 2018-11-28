<?php include('header.html.php'); ?>

Hi <?= $account -> account_first_name; ?> <?= $account -> account_last_name; ?>,

<p>A password reset request has been submitted to the site. If you wish to reset your password, follow the link below:</p>

<div>
<!--[if mso]>
  <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="<?= PVConfiguration::getConfiguration('adbience_sites') -> main; ?>accounts/resetpassword/<?= $account -> account_id; ?>?token=<?= $account -> account_reset_token; ?>" style="height:40px;v-text-anchor:middle;width:300px;" arcsize="10%" stroke="f" fillcolor="#d62828">
    <w:anchorlock/>
    <center style="color:#ffffff;font-family:sans-serif;font-size:16px;font-weight:bold;">
      Reset Password
    </center>
  </v:roundrect>
  <![endif]-->
  <![if !mso]>
  <table cellspacing="0" cellpadding="0"> <tr> 
  <td align="center" width="300" height="40" bgcolor="#000091" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; color: #ffffff; display: block;">
    <a href="<?= PVConfiguration::getConfiguration('adbience_sites') -> main; ?>accounts/resetpassword/<?= $account -> account_id; ?>?token=<?= $account -> account_reset_token; ?>" style="font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; width:100%; display:inline-block">
    <span style="color: #ffffff;">
      Reset Password
    </span>
    </a>
  </td> 
  </tr> </table> 
  <![endif]>
</div>


<p>If this request was not made by you, please disregard this message.</p>

<?php include('footer.html.php'); ?>