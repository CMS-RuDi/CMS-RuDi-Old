<?php if ($friends || $is_admin) { ?>
    <div class="float_bar">
        <a href="javascript:void(0)" class="new_link" onclick="users.sendMess(0, 0, this);return false;" title="<?php echo $_LANG['NEW_MESS']; ?>:"><span class="ajaxlink"><?php echo $_LANG['WRITE']; ?></span></a>
    </div>
<?php } ?>
<div class="con_heading"><?php echo $_LANG['MY_MESS']; ?></div>

<div class="usr_msgmenu_tabs">
    <?php if ($opt == 'in') { ?>
        <span class="usr_msgmenu_active in_span"><?php echo $page_title; ?> <?php if ($new_messages['messages']) { ?>(<?php echo $new_messages['messages']; ?>)<?php } ?></span>
        <a class="usr_msgmenu_link out_link" href="/users/<?php echo $id; ?>/messages-sent.html"><?php echo $_LANG['SENT']; ?></a>
        <a class="usr_msgmenu_link notices_link" href="/users/<?php echo $id; ?>/messages-notices.html"><?php echo $_LANG['NOTICES']; ?> <?php if ($new_messages['notices']) { ?>(<?php echo $new_messages['notices']; ?>)<?php } ?></a>
        <a class="usr_msgmenu_link history_link" href="/users/<?php echo $id; ?>/messages-history.html"><?php echo $_LANG['DIALOGS']; ?></a>
    <?php } else if ($opt == 'out') { ?>
        <a class="usr_msgmenu_link in_link" href="/users/<?php echo $id; ?>/messages.html"><?php echo $_LANG['INBOX']; ?> <?php if ($new_messages['messages']) { ?>(<?php echo $new_messages['messages']; ?>)<?php } ?></a>
        <span class="usr_msgmenu_active out_span"><?php echo $page_title; ?></span>
        <a class="usr_msgmenu_link notices_link" href="/users/<?php echo $id; ?>/messages-notices.html"><?php echo $_LANG['NOTICES']; ?> <?php if ($new_messages['notices']) { ?>(<?php echo $new_messages['notices']; ?>)<?php } ?></a>
        <a class="usr_msgmenu_link history_link" href="/users/<?php echo $id; ?>/messages-history.html"><?php echo $_LANG['DIALOGS']; ?></a>
    <?php } else if ($opt == 'notices') { ?>
        <a class="usr_msgmenu_link in_link" href="/users/<?php echo $id; ?>/messages.html"><?php echo $_LANG['INBOX']; ?> <?php if ($new_messages['messages']) { ?>(<?php echo $new_messages['messages']; ?>)<?php } ?></a>
        <a class="usr_msgmenu_link out_link" href="/users/<?php echo $id; ?>/messages-sent.html"><?php echo $_LANG['SENT']; ?></a>
        <span class="usr_msgmenu_active notices_span"><?php echo $page_title; ?> <?php if ($new_messages['notices']) { ?>(<?php echo $new_messages['notices']; ?>)<?php } ?></span>
        <a class="usr_msgmenu_link history_link" href="/users/<?php echo $id; ?>/messages-history.html"><?php echo $_LANG['DIALOGS']; ?></a>
    <?php } else if ($opt == 'history') { ?>
        <a class="usr_msgmenu_link in_link" href="/users/<?php echo $id; ?>/messages.html"><?php echo $_LANG['INBOX']; ?> <?php if ($new_messages['messages']) { ?>(<?php echo $new_messages['messages']; ?>)<?php } ?></a>
        <a class="usr_msgmenu_link out_link" href="/users/<?php echo $id; ?>/messages-sent.html"><?php echo $_LANG['SENT']; ?></a>
        <a class="usr_msgmenu_link notices_link" href="/users/<?php echo $id; ?>/messages-notices.html"><?php echo $_LANG['NOTICES']; ?> <?php if ($new_messages['notices']) { ?>(<?php echo $new_messages['notices']; ?>)<?php } ?></a>
        <span class="usr_msgmenu_active history_span"><?php echo $page_title; ?></span>
    <?php } ?>
</div>
<div class="usr_msgmenu_bar">
    <strong><?php echo $_LANG['MESS_INBOX']; ?>:</strong> <span id="msg_count"><?php echo $msg_count; ?></span>
<?php if (($opt != 'history') && $msg_count > 0) { ?>
    <div style="float: right;"><a href="javascript:void(0)" onclick="users.cleanCat('/users/<?php echo $id; ?>/delmessages-<?php echo $opt; ?>.html');return false;"><?php echo $_LANG['CLEAN_CAT']; ?></a></div>
<?php } ?>
<?php if ($opt == 'history') { ?>
    <div style="float: right;">
        <form action="" id="history" method="post">
            <select name="with_id" id="with_id" style="width:360px;" onchange="changeFriend();">
                <option value="0"><?php echo $_LANG['FRIEND_FOR_DIALOGS']; ?></option>
                <?php if ($interlocutors) { ?>
                    <?php echo $interlocutors; ?>
                <?php } ?>
            </select>
        </form>
    </div>
<?php } ?>
</div>

<?php if ($records) { ?>
    <?php foreach($records as $record) { ?>
    <div class="usr_msg_entry" id="usr_msg_entry_id_<?php echo $record['id']; ?>">
        <table style="width:100%" cellspacing="0">
        <tr>
            <td class="usr_msg_title" width=""><strong><?php echo $record['authorlink']; ?></strong>, <span class="usr_msg_date"><?php echo $record['fpubdate']; ?></span></td>
            <?php if ($record['is_new']) { ?>
                <?php if ($opt == 'in' || $opt == 'notices') { ?>
                    <td class="usr_msg_title" width="90" align="right"><span class="msg_new"><?php echo $_LANG['NEW']; ?>!</span></td>
                <?php } else { ?>
                    <td class="usr_msg_title" width="90" align="right"><a class="msg_delete" href="javascript:void(0)" onclick="users.deleteMessage('<?php echo $record['id']; ?>')"><span class="ajaxlink"><?php echo $_LANG['CANCEL_MESS']; ?></span></a></td>
                <?php } ?>
            <?php } else { ?>
                <td class="usr_msg_title" width="14" align="right">&nbsp;</td>
                <td class="usr_msg_title" width="20" align="right">&nbsp;</td>
            <?php } ?>
            <?php if ($opt == 'in') { ?>
                <?php if ($record['sender_id'] > 0) { ?>
                    <td class="usr_msg_title" width="80" align="right"><a href="javascript:void(0)" class="msg_reply" onclick="users.sendMess('<?php echo $record['from_id']; ?>', '<?php echo $record['id']; ?>', this);return false;" title="<?php echo $_LANG['NEW_MESS']; ?>: <?php echo $this->escape($record['author']); ?>"><span class="ajaxlink"><?php echo $_LANG['REPLY']; ?></span></a></td>
                    <td class="usr_msg_title" width="80" align="right"><a class="msg_history" href="/users/<?php echo $id; ?>/messages-history<?php echo $record['from_id']; ?>.html"><?php echo $_LANG['HISTORY']; ?></a></td>
                <?php } ?>
            <?php } ?>
            <?php if ($opt == 'in' || (in_array($opt, array('out','history','notices')) && !$record['is_new'])) { ?>
                <td class="usr_msg_title" width="70" align="right"><a class="msg_delete" href="javascript:void(0)" onclick="users.deleteMessage('<?php echo $record['id']; ?>')"><span class="ajaxlink"><?php echo $_LANG['DELETE']; ?></span></a></td>
            <?php } ?>
        </tr>
        </table>
        <table cellspacing="4">
        <tr>
            <td width="70" height="70" valign="middle" align="center" style="border:solid 1px #C3D6DF; padding: 4px">
                <?php if ($record['sender_id'] > 0) { ?>
                    <a href="<?php echo cmsUser::getProfileURL($record['author_login']); ?>"><img border="0" class="usr_img_small" src="<?php echo $record['user_img']; ?>" /></a>
                <?php } else { ?>
                    <img border="0" class="usr_img_small" src="<?php echo $record['user_img']; ?>" />
                <?php } ?>
                <div style="margin: 4px 0 0 0;"><?php echo $record['online_status']; ?></div>
            </td>
            <td width="" valign="top"><div style="padding:6px"><?php echo $record['message']; ?></div></td>
        </tr>
        </table>
    </div>
    <?php } ?>
    <?php echo $pagebar; ?>
<?php } else { ?>
    <p style="padding:20px 10px"><?php echo $_LANG['NOT_MESS_IN_CAT']; ?></p>
<?php } ?>

<script type="text/javascript">
    function changeFriend(){
        fr_id = $("#with_id option:selected").val();
        if(fr_id != 0) {
            $("#history").attr("action", '/users/<?php echo $id; ?>/messages-history'+fr_id+'.html');
            $('#history').submit();
        }
    }
</script>