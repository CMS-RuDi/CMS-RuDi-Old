<div id="usertitle">
    <div class="con_heading" id="nickname" style="float:left;">
        <?php echo $usr['nickname']; ?>
    </div>
</div>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center" valign="middle" style="padding:10px; border:solid 1px gray; background-color:#FFFFFF">
                        <img border="0" class="usr_img" src="<?php echo $usr['avatar']; ?>" />
                    </td>
                </tr>
            </table>
        </td>
    	<td valign="top" style="padding-left:10px">
            <div class="usr_deleted"><?php echo $_LANG['USER_PROFILE_DELETED']; ?></div>
            <?php if ($is_admin) { ?>
                <?php if (!$others_active) { ?>
                    <div class="usr_restore"><?php echo $_LANG['YOU_CAN']; ?> <a href="/users/restoreprofile<?php echo $usr['id']; ?>.html"><?php echo $_LANG['RESTORE_PROFILE']; ?></a></div>
                <?php } else { ?>
                    <div class="usr_restore"><?php echo $_LANG['CANT_RESTORE_PROFILE_TEXT']; ?> (<?php echo $usr['login']; ?>).</div>
                <?php } ?>
            <?php } ?>
        </td>
  </tr>
</table>