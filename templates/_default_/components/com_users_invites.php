<h1 class="con_heading"><?php echo $_LANG['MY_INVITES']; ?></h1>

<p style="margin-bottom: 4px"><?php echo $_LANG['YOU_CAN_SEND']; ?> <?php echo $this->spellcount($invites_count, $_LANG['INVITE1'], $_LANG['INVITE2'], $_LANG['INVITE10']); ?></p>

<p style="margin-bottom: 10px"><?php echo $_LANG['INVITE_NOTICE']; ?></p>

<p style="margin-bottom: 5px"><strong><?php echo $_LANG['INVITE_EMAIL']; ?>:</strong></p>

<form method="post" action="">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <input type="text" name="invite_email" class="text-input" value="" style="width:200px"/>

    <input type="submit" name="send_invite" value="<?php echo $_LANG['SEND_INVITE']; ?>" />

</form>