<?php include('_header.html.php'); ?>

<p style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px" >Hi <?= $account -> first_name; ?> <?= $account -> last_name; ?>,</p>

<p style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px" >Thank you for registering. Before you are able to engage, you must first activate your account using the link below.</p>
<p style="display: block; margin: 10px 0; line-height:10px; content: ' ';" ></p>

<div>
<!--[if mso]>
  <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="<?= $site_url ?>users/activate/<?=$user -> user_id; ?>?token=<?= $user->activation_token; ?>" style="height:40px;v-text-anchor:middle;width:300px;" arcsize="10%" stroke="f" fillcolor="#d62828">
    <w:anchorlock/>
    <center style="color:#ffffff;font-family:sans-serif;font-size:16px;font-weight:bold;">
      Activate Account
    </center>
  </v:roundrect>
  <![endif]-->
  <![if !mso]>
  <table cellspacing="0" cellpadding="0"> <tr> 
  <td align="center" width="300" height="40" bgcolor="#000091" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; color: #ffffff; display: block;">
    <a href="<?= $site_url ?>users/activate/<?=$account -> user_id; ?>?token=<?= $account ->activation_token; ?>" style="font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; width:100%; display:inline-block">
    <span style="color: #ffffff;">
      Activate Account
    </span>
    </a>
  </td> 
  </tr> </table> 
  <![endif]>
</div>

<p style="display: block; margin: 10px 0; line-height:10px; content: ' ';" ></p>
<p style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px" >Looking forward to having you onboard.</p>

<?php include('_footer.html.php'); ?>