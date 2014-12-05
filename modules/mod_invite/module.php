<?php
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

function mod_invite($module_id, $cfg) {
    global $_LANG;

    $errors      = false;
    $is_redirect = false; // в модуле нельзя использовать cmsCore::redirectBack(), используем костыли ;)

    if (cmsCore::inRequest('send_invite_email')) {
        $is_redirect = true;

        $username = cmsCore::request('username', 'str', '');
        $email    = cmsCore::request('friend_email', 'email', '');

        if (!$username && !cmsCore::c('user')->id) {
            cmsCore::addSessionMessage($_LANG['ERR_NEED_NAME'], 'error'); $errors = true;
        }
        
        if (cmsCore::c('user')->id) {
            $username = cmsCore::c('user')->nickname;
        }

        if (!$email) {
            cmsCore::addSessionMessage($_LANG['ERR_NEED_MAIL'], 'error'); $errors = true;
        }

        if (!$errors) {
            if (!cmsUser::checkCsrfToken()) {
                cmsCore::error404();
            }

            $letter = cmsCore::getLanguageTextFile('mail_invite');
            $letter = str_replace('{sitename}', cmsConfig::getConfig('sitename'), $letter);
            $letter = str_replace('{site_url}', HOST, $letter);
            $letter = str_replace('{username}', $username, $letter);

            cmsCore::mailText($email, sprintf($_LANG['INVITE_SUBJECT'], $username), $letter);
            cmsCore::addSessionMessage($_LANG['INVITE_SENDED'], 'success');
        }
    }

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('user_id', cmsCore::c('user')->id)->
        assign('is_redirect', $is_redirect)->
        display();

    return true;
}