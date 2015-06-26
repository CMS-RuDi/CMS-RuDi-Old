<?php
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.5                               //
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

cmsCore::c('page')->initTemplate('components', 'registration_config')->
    assign('groups', cmsUser::getGroups(true))->
    assign('cfg', $cfg)->
    display();