<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                          LICENSED BY GNU/GPL v2                            //
//                                                                            //
/******************************************************************************/
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function applet_robots() {
    global $_LANG;
    global $adminAccess;
    
    if (!cmsUser::isAdminCan('admin/robots', $adminAccess)) { cpAccessDenied(); }
    
    cmsCore::c('page')->setTitle($_LANG['ROBOTS_TITLE']);

    cpAddPathway($_LANG['ROBOTS_TITLE']);

    $do = cmsCore::request('do', array('edit', 'save'), 'edit');

    if (!file_exists(PATH .'/robots.txt')) {
        $fp = fopen(PATH .'/robots.txt', 'w');
        fwrite($fp, str_replace(array('%domen%', '%host%'), array(str_replace(array('https://', 'http://'), '', cmsCore::c('config')->host), cmsCore::c('config')->host), file_get_contents(PATH .'/includes/default_robots.txt')));
        fclose ($fp);
        chmod(PATH .'/robots.txt', 0777);
    }

    if ($do == 'save') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $data = cmsCore::request('robots', 'str');
        
        $fp = fopen(PATH .'/robots.txt', 'w');
        fwrite($fp, stripcslashes($data) ."\n");
        fclose ($fp);
    }

    $robots = file_get_contents(PATH .'/robots.txt');
    
    cmsCore::c('page')->initTemplate('applets', 'robots')->
        assign('robots', $robots)->
        display();
}