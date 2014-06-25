<h1 class="con_heading"><?php echo $_LANG['REMINDER_PASS']; ?></h1>
<form name="prform" action="" method="POST">
<input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
<table style="background-color:#EBEBEB" border="0" cellspacing="0" cellpadding="9"><tr>
<td><?php echo $_LANG['WRITE_REGISTRATION_EMAIL']; ?>: </td>
<td><input name="email" type="text" size="25" class="text-input" /></td>
<td><input name="goremind" type="submit" value="<?php echo $_LANG['SEND']; ?>"/></td>
</tr></table>
</form>