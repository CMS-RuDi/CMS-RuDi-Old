<?php
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.4                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

$opt = cmsCore::request('opt', 'str', 'list');

echo '<h3>'. $_LANG['AD_SETTINGS'] .': '. $com['title'] .'</h3>';

$cfg = $inCore->loadComponentConfig('registration');

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg['reg_type']    = cmsCore::request('reg_type', 'str', '');
    $cfg['inv_count']   = cmsCore::request('inv_count', 'int', 0);
    $cfg['inv_karma']   = cmsCore::request('inv_karma', 'int', 0);
    $cfg['inv_period']  = cmsCore::request('inv_period', 'str', '');

    $cfg['default_gid'] = cmsCore::request('default_gid', 'int', 0);

    $cfg['is_on']       = cmsCore::request('is_on', 'int', 0);
    $cfg['act']         = cmsCore::request('act', 'int', 0);
    $cfg['send']        = cmsCore::request('send', 'int', 0);
    $cfg['offmsg']      = cmsCore::request('offmsg', 'html', '');

    $cfg['first_auth_redirect'] = cmsCore::request('first_auth_redirect', 'str', '');
    $cfg['auth_redirect']       = cmsCore::request('auth_redirect', 'str', '');

    $cfg['name_mode']       = cmsCore::request('name_mode', 'str', '');
    $cfg['badnickname']     = mb_strtolower(cmsCore::request('badnickname', 'html', ''));
    $cfg['ask_icq']         = cmsCore::request('ask_icq', 'int', 0);
    $cfg['ask_birthdate']   = cmsCore::request('ask_birthdate', 'int', 0);
    $cfg['ask_city']        = cmsCore::request('ask_city', 'int', 0);

    $cfg['send_greetmsg']   = cmsCore::request('send_greetmsg', 'int');
    $cfg['greetmsg']        = cmsCore::request('greetmsg', 'html', '');

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');

    $inCore->saveComponentConfig('registration', $cfg);

    if (cmsCore::request('inv_now', 'int', 0)) {
        $inv_count = $cfg['inv_count'];
        $inv_karma = $cfg['inv_karma'];

        if ($inv_count) {
            $invites_given = cmsCore::m('users')->giveInvites($inv_count, $inv_karma);

            if ($invites_given) {
                cmsCore::addSessionMessage($_LANG['AD_ISSUED_INVITES'].': '.$invites_given, 'success');
            } else {
                cmsCore::addSessionMessage($_LANG['AD_INVITES_NOT_ISSUED'], 'success');
            }
        }

    }

    if (cmsCore::request('inv_delete', 'int', 0)) {
        cmsCore::m('users')->deleteInvites();

        cmsCore::addSessionMessage($_LANG['AD_INVITES_DELETE'], 'success');
    }

    cmsCore::redirectBack();
}

?>
<form action="index.php?view=components&amp;do=config&amp;id=<?php echo $id; ?>" method="post" name="optform" target="_self" id="optform">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div id="config_tabs" style="width: 650px;" class="uitabs">
        <ul id="tabs">
            <li><a href="#basic"><span><?php echo $_LANG['AD_GENERAL']; ?></span></a></li>
            <li><a href="#form"><span><?php echo $_LANG['AD_FORM']; ?></span></a></li>
            <li><a href="#greets"><span><?php echo $_LANG['AD_WELCOME']; ?></span></a></li>
        </ul>
        
        <div id="basic">
            <div class="form-group">
                <label><?php echo $_LANG['AD_REGISTRATION_TYPE']; ?>:</label>
                <select id="name_mode" class="form-control" name="reg_type" onchange="if($(this).val()=='invite'){ $('#inv').show(); } else { $('#inv').hide(); }">
                    <option value="open" <?php if ($cfg['reg_type']=='open') {echo 'selected';} ?>><?php echo $_LANG['AD_REGISTRATION_OPEN']; ?></option>
                    <option value="invite" <?php if ($cfg['reg_type']=='invite') {echo 'selected';} ?>><?php echo $_LANG['AD_REGISTRATION_INVITES']; ?></option>
                </select>
            </div>
            <div id="inv" class="form-group" <?php if ($cfg['reg_type'] == 'open') { ?>style="display:none;"<?php } ?>>
                <table width="100%">
                    <tr>
                        <td><?php echo $_LANG['AD_ISSUE_ON']; ?>:</td>
                        <td><input type="number" class="form-control" name="inv_count" value="<?php echo $cfg['inv_count']; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $_LANG['AD_INVITES_KARMA']; ?> &ge;</td>
                        <td><input type="number" class="form-control" name="inv_karma" value="<?php echo $cfg['inv_karma']; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $_LANG['AD_ONCE']; ?></td>
                        <td>
                            <select class="form-control" name="inv_period">
                                <option value="DAY" <?php if ($cfg['inv_period']=='DAY') {echo 'selected';} ?>><?php echo $_LANG['AD_DAY']; ?></option>
                                <option value="WEEK" <?php if ($cfg['inv_period']=='WEEK') {echo 'selected';} ?>><?php echo $_LANG['AD_WEEKLY']; ?></option>
                                <option value="MONTH" <?php if ($cfg['inv_period']=='MONTH') {echo 'selected';} ?>><?php echo $_LANG['AD_MONTH']; ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                <div style="margin-top: 10px;">
                    <input type="hidden" id="inv_now" name="inv_now" value="0" />
                    <input type="hidden" id="inv_delete" name="inv_delete" value="0" />
                    <input type="button" class="btn btn-default" value="<?php echo $_LANG['AD_ISSUE_INVITES']; ?>" onclick="if(confirm('<?php echo $_LANG['AD_GIVE_INVITES']; ?>?')){ $('#inv_now').val('1'); $('#optform').submit(); }" />
                    <input type="button" class="btn btn-default" value="<?php echo $_LANG['AD_DELETE_INVITES']; ?>" onclick="if(confirm('<?php echo $_LANG['AD_DELETE_INVITES_QUEST']; ?>?')){ $('#inv_delete').val('1'); $('#optform').submit(); }" />
                </div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_REGISTRATION_ON']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                    <label class="btn btn-default <?php if ($cfg['is_on']) { echo 'active'; } ?>">
                        <input type="radio" name="is_on" <?php if ($cfg['is_on']) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!$cfg['is_on']) { echo 'active'; } ?>">
                        <input type="radio" name="is_on" <?php if (!$cfg['is_on']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_REGISTRATION_OFF_POST']; ?>:</label>
                <textarea id="offmsg" class="form-control" name="offmsg" rows="2"><?php echo $cfg['offmsg'];?></textarea>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_ACTIVATION_MAIL']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                    <label class="btn btn-default <?php if ($cfg['act']) { echo 'active'; } ?>">
                        <input type="radio" name="act" <?php if ($cfg['act']) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!$cfg['act']) { echo 'active'; } ?>">
                        <input type="radio" name="act" <?php if (!$cfg['act']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_ACTIVATION_POST']; ?>:</label>
                <p class="form-control"><?php echo '/languages/'. cmsConfig::getConfig('lang') .'/letters/activation.txt'; ?></p>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_GROUP_DEFAULT']; ?>:</label>
                <?php $groups = cmsUser::getGroups(true); ?>
                <select id="default_gid" class="form-control" name="default_gid">
                    <?php foreach($groups as $group){ ?>
                    <option value="<?php echo $group['id']; ?>" <?php if ($cfg['default_gid']==$group['id']){ ?>selected="selected"<?php } ?>><?php echo $group['title']; ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_FIRST_LOGIN']; ?>:</label>
                <select id="first_auth_redirect" class="form-control" name="first_auth_redirect">
                    <option value="none" <?php if ($cfg['first_auth_redirect']=='none') {echo 'selected';} ?>><?php echo $_LANG['AD_DO_NOTHING']; ?></option>
                    <option value="index" <?php if ($cfg['first_auth_redirect']=='index') {echo 'selected';} ?>><?php echo $_LANG['AD_OPEN_HOME']; ?></option>
                    <option value="profile" <?php if ($cfg['first_auth_redirect']=='profile') {echo 'selected';} ?>><?php echo $_LANG['AD_OPEN_PROFILE']; ?></option>
                    <option value="editprofile" <?php if ($cfg['first_auth_redirect']=='editprofile') {echo 'selected';} ?>><?php echo $_LANG['AD_OPEN_PROFILE_SETTIGS']; ?></option>
                </select>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_NEXT_LOGIN']; ?>:</label>
                <select id="auth_redirect" class="form-control" name="auth_redirect">
                    <option value="none" <?php if ($cfg['auth_redirect']=='none') {echo 'selected';} ?>><?php echo $_LANG['AD_DO_NOTHING']; ?></option>
                    <option value="index" <?php if ($cfg['auth_redirect']=='index') {echo 'selected';} ?>><?php echo $_LANG['AD_OPEN_HOME']; ?></option>
                    <option value="profile" <?php if ($cfg['auth_redirect']=='profile') {echo 'selected';} ?>><?php echo $_LANG['AD_OPEN_PROFILE']; ?></option>
                    <option value="editprofile" <?php if ($cfg['auth_redirect']=='editprofile') {echo 'selected';} ?>><?php echo $_LANG['AD_OPEN_PROFILE_SETTIGS']; ?></option>
                </select>
            </div>
        </div>

        <div id="form">
            <div class="form-group">
                <label><?php echo $_LANG['AD_NAME_FORMAT']; ?>:</label>
                <select id="name_mode" class="form-control" name="name_mode">
                    <option value="nickname" <?php if ($cfg['name_mode']=='nickname') {echo 'selected';} ?>><?php echo $_LANG['AD_NICKNAME']; ?></option>
                    <option value="realname" <?php if ($cfg['name_mode']=='realname') {echo 'selected';} ?>><?php echo $_LANG['AD_NAME_SURNAME']; ?></option>
                </select>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_NAME_PROCHIBITED']; ?>:</label>
                <textarea id="badnickname" class="form-control" name="badnickname" rows="5"><?php echo $cfg['badnickname'];?></textarea>
                <div class="help-block"><?php echo $_LANG['AD_ENTER_BANNED_NAME']; ?>.</div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_REQUIRE_ICQ']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                    <label class="btn btn-default <?php if ($cfg['ask_icq']) { echo 'active'; } ?>">
                        <input type="radio" name="ask_icq" <?php if ($cfg['ask_icq']) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!$cfg['ask_icq']) { echo 'active'; } ?>">
                        <input type="radio" name="ask_icq" <?php if (!$cfg['ask_icq']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_REQUIRE_BIRD']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                    <label class="btn btn-default <?php if ($cfg['ask_birthdate']) { echo 'active'; } ?>">
                        <input type="radio" name="ask_birthdate" <?php if ($cfg['ask_birthdate']) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!$cfg['ask_birthdate']) { echo 'active'; } ?>">
                        <input type="radio" name="ask_birthdate" <?php if (!$cfg['ask_birthdate']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_REQUIRE_CITY']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                    <label class="btn btn-default <?php if ($cfg['ask_city']) { echo 'active'; } ?>">
                        <input type="radio" name="ask_city" <?php if ($cfg['ask_city']) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!$cfg['ask_city']) { echo 'active'; } ?>">
                        <input type="radio" name="ask_city" <?php if (!$cfg['ask_city']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
        </div>

        <div id="greets">
            <div class="form-group">
                <label><?php echo $_LANG['AD_SEND_MASSAGE']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                    <label class="btn btn-default <?php if ($cfg['send_greetmsg']) { echo 'active'; } ?>">
                        <input type="radio" name="send_greetmsg" <?php if ($cfg['send_greetmsg']) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!$cfg['send_greetmsg']) { echo 'active'; } ?>">
                        <input type="radio" name="send_greetmsg" <?php if (!$cfg['send_greetmsg']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <?php $inCore->insertEditor('greetmsg', $cfg['greetmsg'], '300', '600'); ?>
            </div>
        </div>
    </div>

    <div>
        <input type="hidden" name="opt" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';" />
    </div>
</form>