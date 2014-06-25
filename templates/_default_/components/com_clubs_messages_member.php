<script type="text/javascript" src="/includes/jquery/jquery.form.js"></script>

<p style="margin:6px 0;" id="text_mes"><?php echo $_LANG['SEND_MESSAGE_TEXT']; ?> "<?php echo $club['title']; ?>".</p>

<form action="/clubs/<?php echo $club['id']; ?>/message-members.html" method="POST" name="msgform" id="send_messages">
    <input type="hidden" name="gosend" value="1" />
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div class="usr_msg_bbcodebox"><?php echo $bbcodetoolbar; ?></div>
    
    <?php echo $smilestoolbar; ?>
    
    <div class="cm_editor"><textarea class="ajax_autogrowarea" name="content" id="message"></textarea></div>
    
    <div style="margin:0 0 4px;">
        <label><input id="only_mod" name="only_mod" type="checkbox" value="1" onclick="mod_text()" /> <?php echo $_LANG['MESSAGE_ONLY_MODERS']; ?></label>
    </div>
</form>


<script type="text/javascript">
function mod_text(){
    if ($('#only_mod').prop('checked')){
        $('#text_mes').html('<?php echo $_LANG['SEND_MESSAGE_TEXT_MOD']; ?> "<?php echo $this->escape($club['title']); ?>".');
    } else {
        $('#text_mes').html('<?php echo $_LANG['SEND_MESSAGE_TEXT']; ?> "<?php echo $this->escape($club['title']); ?>".');
    }
}
$(document).ready(function(){
    $('.ajax_autogrowarea').focus();
});
</script>