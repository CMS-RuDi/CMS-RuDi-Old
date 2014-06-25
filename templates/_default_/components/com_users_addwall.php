<form action="/core/ajax/wall.php" method="POST" id="add_wall_form">
    <input type="hidden" name="target_id" value="<?php echo $target_id; ?>" />
    <input type="hidden" name="component" value="<?php echo $component; ?>" />
    <input type="hidden" name="do_wall" value="add" />
    <input type="hidden" name="submit" value="1" />
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <div class="usr_msg_bbcodebox"><?php echo $bb_toolbar; ?></div>
    <div class="cm_smiles"><?php echo $smilies; ?></div>
    <div class="cm_editor">
        <textarea name="message" id="message" class="ajax_autogrowarea"></textarea>
    </div>
</form>

<script type="text/javascript" src="/includes/jquery/jquery.form.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#message').focus();
    });
</script>
