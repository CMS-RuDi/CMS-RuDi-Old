<div class="cm_addentry">
<?php if ($user_can_add) { ?>
    <?php if ($can_by_karma || !$cfg['min_karma']) { ?>
	<form action="/comments/<?php echo $do; ?>" id="msgform" method="POST">
        <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
        <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>" />
        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
        <input type="hidden" name="target" value="<?php echo $target; ?>"/>
        <input type="hidden" name="target_id" value="<?php echo $target_id; ?>"/>
        <?php if (!$is_user) { ?>
            <div class="cm_guest_name"><label><?php echo $_LANG['YOUR_NAME']; ?>: <input type="text" maxchars="20" size="30" name="guestname" class="text-input" value="" id="guestname" /></label></div>
            <script type="text/javascript">$(document).ready(function(){ $('#guestname').focus(); });</script>
        <?php } ?>
        <?php if ($is_can_bbcode) { ?>
            <div class="usr_msg_bbcodebox"><?php echo $bb_toolbar; ?></div>
            <div class="cm_smiles"><?php echo $smilies; ?></div>
        <?php } ?>
        <div class="cm_editor">
            <textarea id="content" name="content" class="ajax_autogrowarea" style="height:150px;min-height: 150px;"><?php echo $this->escape($comment['content_bbcode']); ?></textarea>
        </div>
        <?php if ($do == 'add') { ?>
            <?php if ($need_captcha) { ?>
                <div class="cm_codebar">{captcha}</div>
            <?php } ?>
            <div class="submit_cmm">
                <input id="submit_cmm" type="button" value="<?php echo $_LANG['SEND']; ?>"/>
                <input id="cancel_cmm"type="button" onclick="$('.cm_addentry').remove();$('.cm_add_link').show();" value="<?php echo $_LANG['CANCEL']; ?>"/>
            </div>
        <?php } ?>
        <?php if ($is_user && $do == 'add') { ?>
            <?php if (!$user_subscribed) { ?>
                <div style="margin:9px 0; float:right; font-size:12px; vertical-align:middle">
                    <label style="padding:5px"><input name="subscribe" type="checkbox" value="1" style="margin:0; vertical-align:middle" /> <?php echo $_LANG['NOTIFY_NEW_COMM']; ?></label>
                </div>
            <?php } ?>
        <?php } ?>
	</form>
    <div class="sess_messages" <?php if (!$notice) { ?>style="display:none"<?php } ?>>
        <div class="message_info" id="error_mess"><?php echo $notice; ?></div>
    </div>
    <?php } else { ?>
        <?php if ($is_user) { ?>
            <p><?php echo $_LANG['YOU_NEED']; ?> <a href="/users/<?php echo $is_user; ?>/karma.html"><?php echo $_LANG['KARMS']; ?></a> <?php echo $_LANG['TO_ADD_COMM']; ?>.<br> <?php echo $_LANG['NEED']; ?> &mdash; <?php echo $karma_need; ?>, <?php echo $_LANG['HAS']; ?> &mdash; <?php echo $karma_has; ?>.</p>
        <?php } else { ?>
            <p><?php echo $_LANG['COMMENTS_CAN_ADD_ONLY']; ?> <a href="/registration" /><?php echo $_LANG['REGISTERED']; ?></a> <?php echo $_LANG['USERS']; ?>.</p>
        <?php } ?>
    <?php } ?>
<?php } else { ?>
    <p><?php echo $_LANG['YOU_HAVENT_ACCESS_TEXT']; ?></p>
<?php } ?>
</div>