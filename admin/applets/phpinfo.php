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

if (!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function applet_phpinfo() {
    global $_LANG;
    global $adminAccess;
    if (!cmsUser::isAdminCan('admin/config', $adminAccess)) { cpAccessDenied(); }

    cmsCore::c('page')->setTitle($_LANG['AD_PHP_INFO']);

    cpAddPathway($_LANG['AD_SITE_SETTING'], 'index.php?view=config');
    cpAddPathway($_LANG['AD_PHP_INFO'], 'index.php?view=phpinfo');

    ob_start(); phpinfo();

    cmsCore::c('page')->initTemplate()->
        assign('phpinfo',ob_get_clean() )->
        display();
}