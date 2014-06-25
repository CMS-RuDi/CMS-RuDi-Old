<div id="usertitle">
    <div class="con_heading" id="nickname">
        <?php echo $usr['nickname']; ?>
    </div>
</div>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:14px">
    <tr>
        <td width="200" valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center" valign="middle">
                        <div class="usr_avatar">
                            <img alt="<?php echo $this->escape($usr['nickname']); ?>" class="usr_img" src="<?php echo $usr['avatar']; ?>" />
                        </div>
                        <?php if ($is_auth) { ?>
                        <div id="usermenu">
                            <div class="usr_profile_menu">
                                <table cellpadding="0" cellspacing="6" ><tr>
                                    <tr>
                                        <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/profile/friends.png" border="0"/></td>
                                        <td><a class="ajaxlink" href="javascript:void(0)" title="<?php echo $this->escape($usr['nickname']); ?>" onclick="users.addFriend('<?php echo $usr['id']; ?>', this);return false;"><?php echo $_LANG['ADD_TO_FRIEND']; ?></a></td>

                                    </tr>
                                </table>
                            </div>
                        </div>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </td>
    	<td valign="top" style="padding-left:10px">
            <h3><?php echo $_LANG['ACCESS_SECURITY']; ?></h3>
            <div><?php echo $_LANG['LAST_VISIT']; ?>: <?php echo $usr['flogdate']; ?></div>
	</td>
  </tr>
</table>