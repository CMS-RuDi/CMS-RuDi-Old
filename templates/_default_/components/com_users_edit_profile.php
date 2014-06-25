<?php cmsCore::c('page')->addHeadJS('includes/jquery/tabs/jquery.ui.min.js'); ?>
<?php cmsCore::c('page')->addHeadCSS('includes/jquery/tabs/tabs.css'); ?>

<script type="text/javascript">
    $(function(){$(".uitabs").tabs();});
</script>


<div class="con_heading"><?php echo $_LANG['CONFIG_PROFILE']; ?></div>

<div id="profiletabs" class="uitabs">
    <ul id="tabs">
        <li><a href="#about"><span><?php echo $_LANG['ABOUT_ME']; ?></span></a></li>
        <li><a href="#contacts"><span><?php echo $_LANG['CONTACTS']; ?></span></a></li>
        <li><a href="#notices"><span><?php echo $_LANG['NOTIFIC']; ?></span></a></li>
        <li><a href="#policy"><span><?php echo $_LANG['PRIVACY']; ?></span></a></li>
        <li rel="hid"><a href="#change_password"><span><?php echo $_LANG['CHANGING_PASS']; ?></span></a></li>
    </ul>

    <form id="editform" name="editform" enctype="multipart/form-data" method="post" action="">
        <input type="hidden" name="opt" value="save" />
        <div id="about">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td width="300" valign="top">
                        <strong><?php echo $_LANG['YOUR_NAME']; ?>: </strong><br />
                        <span class="usr_edithint"><?php echo $_LANG['YOUR_NAME_TEXT']; ?></span>
                    </td>
                    <td valign="top"><input name="nickname" type="text" class="text-input" id="nickname" style="width:300px" value="<?php echo $this->escape($usr['nickname']); ?>"/></td>
                </tr>
                <tr>
                    <td valign="top"><strong><?php echo $_LANG['SEX']; ?>:</strong></td>
                    <td valign="top">
                        <select name="gender" id="gender" style="width:307px">
                            <option value="0" <?php if ($usr['gender'] == 0) { ?> selected <?php } ?>><?php echo $_LANG['NOT_SPECIFIED']; ?></option>
                            <option value="m" <?php if ($usr['gender'] == 'm') { ?> selected <?php } ?>><?php echo $_LANG['MALES']; ?></option>
                            <option value="f" <?php if ($usr['gender'] == 'f') { ?> selected <?php } ?>><?php echo $_LANG['FEMALES']; ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <strong><?php echo $_LANG['CITY']; ?>:</strong><br />
                        <span class="usr_edithint"><?php echo $_LANG['CITY_TEXT']; ?></span>
                    </td>
                    <td valign="top">
                        <?php echo cmsCore::city_input(array('value' => $usr['city'], 'name' => 'city', 'width' => '300px')); ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top"><strong><?php echo $_LANG['BIRTH']; ?>:</strong> </td>
                    <td valign="top">
                        <?php
                            $sel_day = 1; $sel_month = 1; $sel_year = 1980;
                            if (!empty($usr['birthdate'])){
                                $parts = explode('-', $usr['birthdate']);
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
                <tr>
                    <td valign="top">
                        <strong><?php echo $_LANG['HOBBY']; ?> (<?php echo $_LANG['TAGSS']; ?>): </strong><br/>
                        <span class="usr_edithint"><?php echo $_LANG['YOUR_KEYWORDS']; ?></span><br />
                        <span class="usr_edithint"><?php echo $_LANG['TAGSS_TEXT']; ?></span>
                    </td>
                    <td valign="top">
                        <textarea name="description" class="text-input" style="width:300px" rows="2" id="description"><?php echo $usr['description']; ?></textarea>
                    </td>
                </tr>
                <?php if ($cfg_forum['component_enabled']) { ?>
                <tr>
                    <td valign="top">
                        <strong><?php echo $_LANG['SIGNED_FORUM']; ?>:</strong><br />
                        <span class="usr_edithint"><?php echo $_LANG['CAN_USE_BBCODE']; ?> </span>
                    </td>
                    <td valign="top">
                        <textarea name="signature" class="text-input" style="width:300px" rows="2" id="signature"><?php echo $this->escape($usr['signature']); ?></textarea>
                    </td>
                </tr>
                <?php } ?>
                <?php if ($private_forms) { ?>
                    <?php foreach($private_forms as $field) { ?>
                    <tr>
                        <td valign="top">
                            <strong><?php echo $field['title']; ?>:</strong>
                            <?php if ($field['description']) { ?>
                                <br /><span class="usr_edithint"><?php echo $field['description']; ?></span>
                            <?php } ?>
                        </td>
                        <td valign="top">
                            <?php echo $field['field']; ?>
                        </td>
                    </tr>
                    <?php } ?>
                <?php } ?>
            </table>
        </div>

        <div id="contacts">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td width="300" valign="top">
                        <strong>E-mail:</strong><br />
                        <span class="usr_edithint"><?php echo $_LANG['REALY_ADRESS_EMAIL']; ?></span>
                    </td>
                    <td valign="top">
                        <input name="email" type="text" class="text-input" id="email" style="width:300px" value="<?php echo $usr['email']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td valign="top"><strong><?php echo $_LANG['NUMBER_ICQ']; ?> :</strong></td>
                    <td valign="top"><input name="icq" class="text-input" type="text" id="icq" style="width:300px" value="<?php echo $this->escape($usr['icq']); ?>"/></td>
                </tr>
            </table>
        </div>

        <div id="notices">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td width="300" valign="top">
                        <strong>
                            <?php echo $_LANG['NOTIFY_NEW_MESS']; ?>:
                        </strong><br/>
                        <span class="usr_edithint">
                            <?php echo $_LANG['NOTIFY_NEW_MESS_TEXT']; ?>
                        </span>
                    </td>
                    <td valign="top">
                        <label><input name="email_newmsg" type="radio" value="1" <?php if ($usr['email_newmsg']) { ?>checked<?php } ?>/> <?php echo $_LANG['YES']; ?> </label>
                        <label><input name="email_newmsg" type="radio" value="0" <?php if (!$usr['email_newmsg']) { ?>checked<?php } ?>/> <?php echo $_LANG['NO']; ?></label>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <strong><?php echo $_LANG['HOW_NOTIFY_NEW_MESS']; ?> </strong><br />
                        <span class="usr_edithint"><?php echo $_LANG['WHERE_TO_SEND']; ?></span>
                    </td>
                    <td valign="top">
                        <select name="cm_subscribe" id="cm_subscribe" style="width:307px">
                            <option value="mail" <?php if ($usr['cm_subscribe'] == 'mail') { ?>selected="selected"<?php } ?>><?php echo $_LANG['TO_EMAIL']; ?></option>
                            <option value="priv" <?php if ($usr['cm_subscribe'] == 'priv') { ?>selected="selected"<?php } ?>><?php echo $_LANG['TO_PRIVATE_MESS']; ?></option>
                            <option value="both" <?php if ($usr['cm_subscribe'] == 'both') { ?>selected="selected"<?php } ?>><?php echo $_LANG['TO_EMAIL_PRIVATE_MESS']; ?></option>
                            <option value="none" <?php if ($usr['cm_subscribe'] == 'none') { ?>selected="selected"<?php } ?>><?php echo $_LANG['NOT_SEND']; ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>

        <div id="policy">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td width="300" valign="top">
                        <strong><?php echo $_LANG['SHOW_EMAIL']; ?>:</strong><br/>
                        <span class="usr_edithint"><?php echo $_LANG['SHOW_EMAIL_TEXT']; ?></span>
                    </td>
                    <td valign="top">
                        <label><input name="showmail" type="radio" value="1" <?php if ($usr['showmail']) { ?>checked<?php } ?>/> <?php echo $_LANG['YES']; ?> </label>
                        <label><input name="showmail" type="radio" value="0" <?php if (!$usr['showmail']) { ?>checked<?php } ?>/> <?php echo $_LANG['NO']; ?> </label>
                    </td>
                </tr>
                <tr>
                    <td valign="top"><strong><?php echo $_LANG['SHOW_ICQ']; ?>:</strong></td>
                    <td valign="top">
                        <label><input name="showicq" type="radio" value="1" <?php if ($usr['showicq']) { ?>checked<?php } ?>/> <?php echo $_LANG['YES']; ?> </label>
                        <label><input name="showicq" type="radio" value="0" <?php if (!$usr['showicq']) { ?>checked<?php } ?>/> <?php echo $_LANG['NO']; ?> </label>
                    </td>
                </tr>
                <tr>
                    <td valign="top"><strong><?php echo $_LANG['SHOW_BIRTH']; ?>:</strong> </td>
                    <td valign="top">
                        <label><input name="showbirth" type="radio" value="1" <?php if ($usr['showbirth']) { ?>checked<?php } ?>/> <?php echo $_LANG['YES']; ?> </label>
                        <label><input name="showbirth" type="radio" value="0" <?php if (!$usr['showbirth']) { ?>checked<?php } ?>/> <?php echo $_LANG['NO']; ?> </label>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <strong><?php echo $_LANG['SHOW_PROFILE']; ?>:</strong><br/>
                        <span class="usr_edithint"><?php echo $_LANG['WHOM_SHOW_PROFILE']; ?> </span>
                    </td>
                    <td valign="top">
                        <select name="allow_who" id="allow_who" style="width:307px">
                            <option value="all" <?php if ($usr['allow_who'] == 'all') { ?>selected="selected"<?php } ?>><?php echo $_LANG['EVERYBODY']; ?></option>
                            <option value="registered" <?php if ($usr['allow_who'] == 'registered') { ?>selected="selected"<?php } ?>><?php echo $_LANG['REGISTERED']; ?></option>
                            <option value="friends" <?php if ($usr['allow_who'] == 'friends') { ?>selected="selected"<?php } ?>><?php echo $_LANG['MY_FRIENDS']; ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 12px;" id="submitform">
            <input style="font-size:16px" name="save" type="submit" id="save" value="<?php echo $_LANG['SAVE']; ?>" />
            <input style="font-size:16px" name="delbtn2" type="button" id="delbtn2" value="<?php echo $_LANG['DEL_PROFILE']; ?>" onclick="location.href='/users/<?php echo $usr['id']; ?>/delprofile.html';" />
        </div>
    </form>
    <div id="change_password">
        <form id="editform" name="editform" method="post" action="">
            <input type="hidden" name="opt" value="changepass" />
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td width="300" valign="top">
                        <strong><?php echo $_LANG['OLD_PASS']; ?></strong>
                    </td>
                    <td valign="top">
                        <input name="oldpass" type="password" id="oldpass" class="text-input" size="30" />
                    </td>
                </tr>
                <tr>
                    <td valign="top"><strong><?php echo $_LANG['NEW_PASS']; ?></strong></td>
                    <td valign="top"><input name="newpass" type="password" id="newpass" class="text-input" size="30" /></td>
                </tr>
                <tr>
                    <td valign="top"><strong><?php echo $_LANG['NEW_PASS_REPEAT']; ?></strong></td>
                    <td valign="top"><input name="newpass2" type="password" class="text-input" id="newpass2" size="30" /></td>
                </tr>
            </table>
            <div style="margin-top: 12px;">
                <input style="font-size:16px" name="save2" type="submit" id="save2" value="<?php echo $_LANG['CHANGE_PASSWORD']; ?>" />
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $( '#tabs li' ).click( function(){
            rel = $( this ).attr( "rel" );
            if(!rel){
                $('#submitform').show();
            } else {
                $('#submitform').hide();
            }
        });
    });
</script>