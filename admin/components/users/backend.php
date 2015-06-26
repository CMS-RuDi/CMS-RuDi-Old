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

cmsCore::loadModel('users');
$model = new cms_model_users();

$opt = cmsCore::request('opt', 'str', 'list');

$toolmenu = array(
    array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.optform.submit();' ),
    array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => '?view=components' )
);

cpToolMenu($toolmenu);

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg = array();
    $cfg['sw_comm']   = cmsCore::request('sw_comm', 'int', 0);
    $cfg['sw_search'] = cmsCore::request('sw_search', 'int', 0);
    $cfg['sw_forum']  = cmsCore::request('sw_forum', 'int', 0);
    $cfg['sw_photo']  = cmsCore::request('sw_photo', 'int', 0);
    $cfg['sw_wall']   = cmsCore::request('sw_wall', 'int', 0);
    $cfg['sw_blogs']  = cmsCore::request('sw_blogs', 'int', 0);
    $cfg['sw_clubs']  = cmsCore::request('sw_clubs', 'int', 0);
    $cfg['sw_feed']   = cmsCore::request('sw_feed', 'int', 0);
    $cfg['sw_awards'] = cmsCore::request('sw_awards', 'int', 0);
    $cfg['sw_board']  = cmsCore::request('sw_board', 'int', 0);
    $cfg['sw_msg']    = cmsCore::request('sw_msg', 'int', 0);
    $cfg['sw_guest']  = cmsCore::request('sw_guest', 'int', 0);
    $cfg['sw_files']  = cmsCore::request('sw_files', 'int', 0);

    $cfg['karmatime'] = cmsCore::request('karmatime', 'int', 0);
    $cfg['karmaint']  = cmsCore::request('karmaint', 'str', 'DAY');

    $cfg['photosize'] = cmsCore::request('photosize', 'int', 0);
    $cfg['watermark'] = cmsCore::request('watermark', 'int', 0);
    $cfg['smallw']    = cmsCore::request('smallw', 'int', 64);
    $cfg['medw']      = cmsCore::request('medw', 'int', 200);
    $cfg['medh']      = cmsCore::request('medh', 'int', 200);

    $cfg['filessize'] = cmsCore::request('filessize', 'int', 0);
    $cfg['filestype'] = mb_strtolower(cmsCore::request('filestype', 'str', 'jpeg,gif,png,jpg,bmp,zip,rar,tar'));
    while (mb_strpos($cfg['fa_ext'], 'htm') ||
           mb_strpos($cfg['fa_ext'], 'php') ||
           mb_strpos($cfg['fa_ext'], 'ht')) {
        $cfg['filestype'] = str_replace(array('htm','php','ht'), '', $cfg['filestype']);
    }

    $cfg['privforms'] = cmsCore::request('privforms', 'array_int');

    $cfg['deltime']   = cmsCore::request('deltime', 'int', 0);
    $cfg['users_perpage'] = cmsCore::request('users_perpage', 'int', 10);
    $cfg['wall_perpage']  = cmsCore::request('wall_perpage', 'int', 10);

    $inCore->saveComponentConfig('users', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=config');
}

cpCheckWritable('/images/users/avatars', 'folder');
cpCheckWritable('/images/users/avatars/small', 'folder');
cpCheckWritable('/images/users/photos', 'folder');
cpCheckWritable('/images/users/photos/small', 'folder');
cpCheckWritable('/images/users/photos/medium', 'folder');

$sql = "SELECT * FROM cms_forms";
$rs = cmsCore::c('db')->query($sql);

$forms = array();
if (cmsCore::c('db')->num_rows($rs)) {
    while ($f = cmsCore::c('db')->fetch_assoc($rs)) {
        $forms[] = $f;
    }
}

cmsCore::c('page')->initTemplate('components', 'users_config')->
    assign('forms', $forms)->
    assign('cfg', $model->config)->
    display();