<form action="/login" method="post" name="authform" style="margin:0px" target="_self" id="authform">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <table class="authtable" width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
            <td width="60"><?php echo $_LANG['AUTH_LOGIN']; ?>:</td>
            <td width=""><input name="login" type="text" id="login" /></td>
        </tr>
        <tr>
            <td height="30" valign="top"><?php echo $_LANG['AUTH_PASS']; ?>:</td>
            <td valign="top"><input name="pass" type="password" id="pass" /></td>
        </tr>
        <?php if ($cfg['autolog']) { ?>
            <tr>
                <td valign="top">&nbsp;</td>
                <td valign="top" align="right">
                    <table border="0" cellspacing="0" cellpadding="3">
                    <tr>
                        <td width="20">
                            <input name="remember" type="checkbox" id="remember" value="1" checked="checked"  style="margin-right:0px"/>
                        </td>
                        <td>
                            <label for="remember"> <?php echo $_LANG['AUTH_REMEMBER']; ?></label>
                        </td>
                    </tr>
                    </table>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td height="27" colspan="2" align="right" valign="top">
                <table width="100%" border="0" cellspacing="0" cellpadding="3">
                    <tr>
                        <td width="87%">
                            <?php if ($cfg['passrem']) { ?>
                                <a href="/passremind.html"><?php echo $_LANG['AUTH_FORGOT']; ?></a>
                            <?php } ?>
                        </td>
                        <td width="13%" align="right">
                            <input id="login_btn" type="submit" name="Submit" value="<?php echo $_LANG['AUTH_ENTER']; ?>" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>