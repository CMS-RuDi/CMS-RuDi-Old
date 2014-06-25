<table width="100%" border="0" cellpadding="2" cellspacing="1">
    <?php foreach ($users as $user) { ?>
        <tr>
            <td width="30">
                <a href="<?php echo cmsUser::getProfileURL($user['login']); ?>#upr_awards" title="<?php echo $this->escape($user['nickname']); ?>"><img src="<?php echo $user['avatar']; ?>" border="0" /></a>
            </td>
            <td valign="top">
                <div style="margin-left:15px;">
                    <a style="font-size:16px;font-weight:bold;" href="<?php echo cmsUser::getProfileURL($user['login']); ?>#upr_awards" title="{$user.nickname|escape:'html'}"><?php echo $user['nickname']; ?></a>
                    <?php if ($cfg['show_awards']) { ?>
                        <div style="margin-top:6px">
                            <?php foreach ($user['awards'] as $award) { ?>
                                <img src="/images/icons/award.gif" border="0" title="<?php echo $this->escape($award['title']); ?>" />
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </td>
        </tr>
    <?php } ?>
</table>