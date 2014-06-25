<h1 class="con_heading"><?php echo $_LANG['RECOVER_PASS']; ?></h1>

<?php cmsCore::c('page')->addHeadJS('components/registration/js/check.js'); ?>
<form action="" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <table cellpadding="5" cellspacing="2" border="0" style="margin-bottom: 15px">
        <tr>
            <td width="130"><strong><?php echo $_LANG['LOGIN']; ?>:</strong></td>
            <td width="" height="24"><input type="text" name="pass" value="<?php echo $user['login']; ?>" disabled="disabled" style="width:200px" class="text-input"/></td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['PASS']; ?>:</strong></td>
            <td><input type="password" name="pass" id="pass1input" value="" style="width:200px" class="text-input" onchange="$('#passcheck').html('');" /></td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['REPEAT_PASS']; ?>:</strong></td>
            <td><input type="password" name="pass2" id="pass2input" value="" style="width:200px" class="text-input" onchange="checkPasswords()" /><div id="passcheck"></div></td>
        </tr>
    </table>

    <input type="submit" id="submit" name="submit" value="<?php echo $_LANG['CHANGE_PASS']; ?>" />

</form>
<script type="text/javascript">
    $('input[name=pass]').focus();
</script>