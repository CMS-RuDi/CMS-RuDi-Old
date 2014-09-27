<div class="con_heading"><?php echo $pagetitle; ?></div>

<?php if ($cfg['is_on']) { ?>
    <?php if ($cfg['reg_type'] == 'invite' && !$correct_invite) { ?>
        <p style="margin-bottom:15px; font-size: 14px"><?php echo $_LANG['INVITES_ONLY']; ?></p>

        <form id="regform" name="regform" method="post" action="/registration">
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td><strong><?php echo $_LANG['INVITE_CODE']; ?>:</strong></td>
                <td style="padding-left:15px">
                    <input type="text" name="invite_code" class="text-input" value="" style="width:300px"/>
                </td>
                <td style="padding-left:5px">
                    <input type="submit" name="show_invite" value="<?php echo $_LANG['SHOW_INVITE']; ?>" />
                </td>
            </tr>
        </table>
        </form>

    <?php } else { ?>

        <?php cmsCore::c('page')->addHeadJS('components/registration/js/check.js'); ?>

        <form id="regform" name="regform" method="post" action="/registration/add">
            <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
            <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                <tr>
                    <td width="269" valign="top" class="">
                        <div><strong><?php echo $_LANG['LOGIN']; ?>:</strong></div>
                        <div><small><?php echo $_LANG['USED_FOR_AUTH']; ?><br/><?php echo $_LANG['ONLY_LAT_SYMBOLS']; ?></small></div>
                    </td>
                    <td valign="top" class="">
                        <input name="login" id="logininput" class="text-input" type="text" style="width:300px" value="<?php echo $this->escape($item['login']); ?>" onchange="checkLogin()" autocomplete="off"/>
                        <span class="regstar">*</span>
                        <div id="logincheck"></div>
                    </td>
                </tr>
                <?php if ($cfg['name_mode'] == 'nickname') { ?>
                    <tr>
                        <td valign="top" class="" width="269">
                            <div><strong><?php echo $_LANG['NICKNAME']; ?>:</strong></div>
                            <small><?php echo $_LANG['NICKNAME_TEXT']; ?></small>
                        </td>
                        <td valign="top" class="">
                            <input name="nickname" id="nickinput" class="text-input" type="text" style="width:300px" value="<?php echo $this->escape($item['nickname']); ?>" />
                            <span class="regstar">*</span>
                        </td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td valign="top" class="">
                            <div><strong><?php echo $_LANG['NAME']; ?>:</strong></div>
                        </td>
                        <td valign="top" class="">
                            <input name="realname1" id="realname1" class="text-input" type="text" style="width:300px" value="{$item.realname1|escape:'html'}" />
                            <span class="regstar">*</span>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" class="">
                            <div><strong><?php echo $_LANG['SURNAME']; ?>:</strong></div>
                        </td>
                        <td valign="top" class="">
                            <input name="realname2" id="realname2" class="text-input" type="text" style="width:300px" value="{$item.realname2|escape:'html'}" />
                            <span class="regstar">*</span>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td valign="top" class=""><strong><?php echo $_LANG['PASS']; ?>:</strong></td>
                    <td valign="top" class="">
                        <input name="pass" id="pass1input" class="text-input" type="password" style="width:300px" onchange="$('#passcheck').html('');"/>
                        <span class="regstar">*</span>
                    </td>
                </tr>
                <tr>
                    <td valign="top" class=""><strong><?php echo $_LANG['REPEAT_PASS']; ?>: </strong></td>
                    <td valign="top" class="">
                        <input name="pass2" id="pass2input" class="text-input" type="password" style="width:300px" onchange="checkPasswords()" />
                        <span class="regstar">*</span>
                        <div id="passcheck"></div>
                    </td>
                </tr>
                <tr>
                    <td valign="top" class="">
                        <div><strong><?php echo $_LANG['EMAIL']; ?>:</strong></div>
                        <div><small><?php echo $_LANG['NOPUBLISH_TEXT']; ?></small></div>
                    </td>
                    <td valign="top" class="">
                        <input name="email" type="text" class="text-input" style="width:300px" value="<?php echo $item['email']; ?>"/>
                        <span class="regstar">*</span>
                    </td>
                </tr>
                <?php if ($private_forms) { ?>
                    <?php foreach($private_forms as $field) { ?>
                    <tr>
                        <td valign="top">
                            <strong><?php echo $field['title']; ?>:</strong>
                            <?php if ($field['description']) { ?>
                                <div><small><?php echo $field['description']; ?></small></div>
                            <?php } ?>
                        </td>
                        <td valign="top">
                            <?php echo $field['field']; ?> <span class="regstar">*</span>
                        </td>
                    </tr>
                    <?php } ?>
                <?php } ?>
                <?php if ($cfg['ask_city']) { ?>
                    <tr>
                        <td valign="top" class=""><strong><?php echo $_LANG['CITY']; ?>:</strong></td>
                        <td valign="top" class="">
                            <?php echo cmsCore::city_input(array('value' => $item['city'], 'name' => 'city', 'width' => '300px')); ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ($cfg['ask_icq']) { ?>
                    <tr>
                        <td valign="top" class=""><strong>ICQ:</strong></td>
                        <td valign="top" class="">
                            <input name="icq" type="text" class="text-input" id="icq" value="<?php echo $this->escape($item['icq']); ?>" style="width:300px"/>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ($cfg['ask_birthdate']) { ?>
                    <tr>
                        <td valign="top" class="">
                            <div><strong><?php echo $_LANG['BIRTH']; ?>:</strong></div>
                            <div><small><?php echo $_LANG['NOPUBLISH_TEXT']; ?></small></div>
                        </td>
                        <td valign="top" class="">
                            <?php
                                $sel_day = 1; $sel_month = 1; $sel_year = 1980;
                                if (!empty($item['birthdate'])){
                                    $parts = explode('-', $item['birthdate']);
                                    $sel_day = intval($parts[2]);
                                    $sel_month = intval($parts[1]);
                                    $sel_year = intval($parts[0]);
                                }
                            ?>
                            <select name="birthdate[day]">
                                <?php for ($i=1;$i<=31;$i++){ ?>
                                    <option value="<?php echo ($i < 10 ? '0'. $i : $i); ?>"<?php if ($sel_day == $i) { echo ' selected="selected"'; }?>><?php echo ($i < 10 ? '0'. $i : $i); ?></option>
                                <?php } ?>
                            </select>
                            <select name="birthdate[month]">
                                <?php for ($i=1;$i<=12;$i++){ ?>
                                    <option value="<?php echo ($i < 10 ? '0'. $i : $i); ?>"<?php if ($sel_month == $i) { echo ' selected="selected"'; }?>><?php echo ($i < 10 ? '0'. $i : $i); ?></option>
                                <?php } ?>
                            </select>
                            <select name="birthdate[year]">
                                <?php for ($i=1950;$i<=intval(date('Y'));$i++){ ?>
                                    <option value="<?php echo $i; ?>"<?php if ($sel_year == $i) { echo ' selected="selected"'; }?>><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td valign="top" class="">
                        <div><strong><?php echo $_LANG['SECUR_SPAM']; ?>: </strong></div>
                        <div><small><?php echo $_LANG['SECUR_SPAM_TEXT']; ?></small></div>
                    </td>
                    <td valign="top" class=""><?php echo cmsPage::getCaptcha(); ?></td>
                </tr>
                <tr>
                    <td valign="top" class="">&nbsp;</td>
                    <td valign="top" class="">
                        <input name="do" type="hidden" value="register" />
                        <input name="save" type="submit" id="save" value="<?php echo $_LANG['REGISTRATION']; ?>" />
                    </td>
                </tr>
            </table>
        </form>

    <?php } ?>

<?php } else { ?>

    <div style="margin-top:10px"><?php echo $cfg['offmsg']; ?></div>

<?php } ?>