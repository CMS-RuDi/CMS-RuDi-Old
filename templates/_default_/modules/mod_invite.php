<form action="" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <?php if (!$user_id) { ?>
    <div style="margin-bottom:10px">
        <input type="text" class="text-input" style="width:98%;color:#666" name="username" value="" placeholder="<?php echo $_LANG['YOUR_NAME']; ?>" />
    </div>
    <?php } ?>

    <div>
        <input type="text" class="text-input" style="width:98%;color:#666" name="friend_email" value="" placeholder="<?php echo $_LANG['FRIEND_EMAIL']; ?>" />
    </div>

    <p style="margin-top:10px">
        <input type="submit" name="send_invite_email" value="<?php echo $_LANG['DO_INVITE']; ?>" />
    </p>

</form>
<?php if ($is_redirect) { ?>
<script type="text/javascript">
    $(document).ready(function(){
        location.href='<?php echo $_SERVER['REQUEST_URI']; ?>';
    });
</script>
<?php } ?>