<h1 class="con_heading"><?php echo $_LANG['SITE_LOGIN']; ?></h1>

<?php if ($is_sess_back) { ?>
    <p class="lf_notice"><?php echo $_LANG['PAGE_ACCESS_NOTICE']; ?></p>
<?php } ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="login_form">
    <tr>
        <td valign="top" width="50%">
            <form method="post" action="">
                <div class="lf_title"><?php echo $_LANG['LOGIN']; ?> <?php echo $_LANG['OR']; ?> <?php echo $_LANG['EMAIL']; ?></div>
                <div class="lf_field">
                    <input type="text" name="login" id="login_field" tabindex="1"/> <a href="/registration" class="lf_link"><?php echo $_LANG['REGISTRATION']; ?></a>
                </div>
                <div class="lf_title"><?php echo $_LANG['PASS']; ?></div>
                <div class="lf_field">
                    <input type="password" name="pass" id="pass_field" tabindex="2"/> <a href="/passremind.html" class="lf_link"><?php echo $_LANG['FORGOT_PASS']; ?></a>
                </div>
                <?php if ($anti_brute_force) { ?>
                    <div class="lf_title"><?php echo $_LANG['SECUR_SPAM']; ?></div>
                    <div class="lf_field">
                        <?php echo cmsPage::getCaptcha(); ?>
                    </div>
                <?php } ?>
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td width="20"><input type="checkbox" name="remember" value="1" checked="checked" id="remember" tabindex="3" /></td>
                        <td>
                            <label for="remember"><?php echo $_LANG['REMEMBER_ME']; ?></label>
                        </td>
                    </tr>
                </table>
                <p class="lf_submit">
                    <input type="submit" name="login_btn" value="<?php echo $_LANG['SITE_LOGIN_SUBMIT']; ?>" tabindex="4" />
                </p>
            </form>
        </td>
        <td valign="top">
            <?php cmsCore::callEvent('LOGINZA_BUTTON', array()); ?>
        </td>
    </tr>
</table>

<script type="text/javascript">
    $(document).ready(function(){
        $('.login_form #login_field').focus();
    });
</script>