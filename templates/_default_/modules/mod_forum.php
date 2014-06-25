<table width="100%" cellspacing="0" cellpadding="5" border="0" >
    <?php foreach ($threads as $thread) { ?>
        <tr>
            <td align="left" width="100"><div class="<?php if ($thread['is_new']) { echo 'mod_fweb2_date_new '; } ?>mod_fweb2_date"<?php if ($thread['is_new']) { ?>title="<?php echo $_LANG['HAVE_NEW_MESS']; ?>"<?php } ?>><?php echo $thread['last_msg_array']['fpubdate']; ?></div></td>
            <td width="13">
                <img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/user_comment.png" border="0" />
            </td>
            <td style="padding-left:0px"><?php echo $thread['last_msg_array']['user_link']; if ($thread['last_msg_array']['post_count'] == 1) { echo $_LANG['FORUM_START_THREAD']; } else {  } ?><?php echo $_LANG['FORUM_REPLY_THREAD']; ?> &laquo;<?php echo $thread['last_msg_array']['thread_link']; ?>&raquo;
            <?php if ($cfg['showforum']) { echo $_LANG['FORUM_ON_FORUM']; ?> &laquo;<a href="/forum/{$thread.forum_id}">{$thread.forum_title}</a>&raquo;<?php } ?>
            </td>
        </tr>
        <?php if ($cfg['showtext']) { ?>
        <tr>
            <td colspan="3"><div class="mod_fweb2_shorttext"><?php echo $this->truncate($this->strip_tags($thread['last_msg_array']['content_html']), 200); ?></div></td>
        </tr>
        <?php } ?>
    <?php } ?>
</table>