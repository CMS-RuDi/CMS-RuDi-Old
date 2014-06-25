<script type="text/javascript" src="/includes/jquery/jquery.form.js"></script>

<?php if ($is_reply_user) { ?>
    <div class="usr_msgreply_source">
        <div class="usr_msgreply_sourcetext"><?php echo $msg['message']; ?></div>
        <div class="usr_msgreply_author"><?php echo $_LANG['ORIGINAL_MESS']; ?>: <a href="<?php echo cmsUser::getProfileURL($msg['login']); ?>"><?php echo $msg['nickname']; ?></a>, <?php echo $msg['senddate']; ?></div>
    </div>
<?php } ?>

<form action="" method="POST" name="msgform" id="send_msgform">
    <input type="hidden" name="gosend" value="1" />
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <div class="usr_msg_bbcodebox"><?php echo $bbcodetoolbar; ?></div>
    <?php echo $smilestoolbar; ?>
    <div class="cm_editor">
        <textarea class="ajax_autogrowarea" name="message" id="message"></textarea>
    </div>
    <div style="margin-top:6px; display:block">
    <?php if (!$id && $friends) { ?>
        <strong><?php echo $_LANG['SEND_TO']; ?>: </strong>
        <select name="user_id" id="user_id" class="s_usr" style="width:160px;" onchange="changeFriendTo();">
            <option value="0"></option>
            <?php foreach($friends as $friend) { ?>
                <option value="<?php echo $friend['id']; ?>" <?php if ($id == $friend['id']) {?> selected="selected"<?php } ?>><?php echo $friend['nickname']; ?></option>
            <?php } ?>
        </select>
    <?php } else { ?>
        <select name="user_id" id="user_id" style="display: none;">
            <option value="<?php echo $id; ?>" selected="selected"></option>
        </select>
    <?php } ?>
    <?php if ($id_admin && !$is_reply_user) { ?>
        <?php if (!$id) { ?>
        <select name="group_id" class="s_grp" id="group_id" style="width:160px; display:none">
            <?php foreach($groups as $group) { ?>
                <option value="<?php echo $group['id']; ?>"><?php echo $group['title']; ?></option>
            <?php } ?>
        </select>
        <input type="hidden" name="send_to_group" id="send_to_group" value="0" />
        <a href="javascript:" class="s_usr ajaxlink" onclick="$('.s_grp').fadeIn();$('.s_usr').hide();$('#send_to_group').val(1);">
            <?php echo $_LANG['SEND_TO_GROUP']; ?>
        </a>
        <a href="javascript:" class="s_grp ajaxlink" onclick="$('.s_grp').hide();$('.s_usr').fadeIn();$('#send_to_group').val(0);" style="display:none">
            <?php echo $_LANG['SEND_TO_FRIEND']; ?>
        </a>
        <?php } ?>
        <label>
            <input name="massmail" type="checkbox" value="1" />
            <?php echo $_LANG['SEND_TO_ALL']; ?>
        </label>
    <?php } ?>
    </div>
</form>

<script type="text/javascript">
$(document).ready(function(){
    $('.ajax_autogrowarea').focus();
});
function changeFriendTo(){
    fr_to = $("#user_id option:selected").html();
    $('#popup_title').html('<?php echo $_LANG['WRITE_MESS']; ?>: '+fr_to);
}
</script>