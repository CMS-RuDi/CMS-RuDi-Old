<?php
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/
ignore_user_abort(true);
set_time_limit(0);

function genSystemImage($dir) {
    $dir = rtrim($dir, '/');
    $dir_context = opendir($dir);
    
    while ($next = readdir($dir_context)) {
        if (strpos($next, '.') === 0) {
            continue;
        }
        
        if ($dir === PATH) {
            if (in_array($next, array('cache', 'images', 'upload'))) {
                continue;
            }
        }
        
        if (is_dir($dir .'/'. $next)) {
            genSystemImage($dir .'/'. $next);
        } else {
            $GLOBALS['SYSTEM_IMAGE'][str_replace(PATH, '', $dir .'/'. $next)] = md5_file($dir .'/'. $next);
        }
    }
    
    closedir($dir_context);
}

function getSystemImageFiles() {
    $dir = PATH .'/cache/system';
    $dir_context = opendir($dir);
    
    $list = array();
    
    while ($next = readdir($dir_context)) {
        if (strpos($next, '.') === 0 || is_dir($dir .'/'. $next) || !preg_match('#systemImage_.+?\.serialize#is', $next)) {
            continue;
        }
        
        $list[] = $next;
    }
    
    closedir($dir_context);
    return $list;
}

function applet_checksystem() {
    global $_LANG;
    
    global $adminAccess;
    
    if (!cmsUser::isAdminCan('admin/checksystem', $adminAccess)) { cpAccessDenied(); }
    
    cmsCore::c('page')->setTitle($_LANG['AD_CHECK_SYSTEM']);

    cpAddPathway($_LANG['AD_CHECK_SYSTEM'], 'index.php?view=checksystem');

    $do = cmsCore::request('do', array('last_check', 'save', 'start', 'start_scan'), 'last_check');
    
    $toolmenu = array(
        array( 'icon' => 'liststuff.gif', 'title' => $_LANG['AD_LAST_CHECK_RESULT'], 'link' => 'index.php?view=checksystem&do=last_check' ),
        array( 'icon' => 'start.png', 'title' => $_LANG['AD_START_NEW_CHECK'], 'link' => 'index.php?view=checksystem&do=start' ),
        array( 'icon' => 'save.png', 'title' => $_LANG['AD_CREATE_NEW_IMG'], 'link' => 'index.php?view=checksystem&do=save' )
    );

    cpToolMenu($toolmenu, 'last_check', 'do');
    
    if ($do == 'last_check') {
        cpAddPathway($_LANG['AD_LAST_CHECK_RESULT'], 'index.php?view=checksystem&do=last_check');
        
        $data = false;
        if (file_exists(PATH .'/cache/last_check_result.serialize')) {
            $data = unserialize(file_get_contents(PATH .'/cache/last_check_result.serialize'));
        }
        
        cmsCore::c('page')->initTemplate('applets', 'checksystem_last_check')->
            assign('data', $data)->
            display();
    }
    
    if ($do == 'start') {
        cpAddPathway($_LANG['AD_START_NEW_CHECK']);
        
        $imageFiles = getSystemImageFiles();
        
        cmsCore::c('page')->initTemplate('applets', 'checksystem')->
            assign('imageFiles', $imageFiles)->
            display();
    }
    
    if ($do == 'start_scan') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
        
        $image = cmsCore::request('image', 'str', '');
        
        if (empty($image) || !file_exists(PATH .'/cache/system/'. $image)) {
            cmsCore::error404();
        }
        
        genSystemImage(PATH);
        
        $old_files = unserialize(file_get_contents(PATH .'/cache/system/'. $image));
        
        $data = array(
            'modified_files' => array(),
            'new_files'      => array(),
            'old_files'      => array()
        );
        
        foreach ($GLOBALS['SYSTEM_IMAGE'] as $k=>$v) {
            if (isset($old_files[$k])) {
                if ($old_files[$k] != $v) {
                    $data['modified_files'][] = $k;
                }
                unset($old_files[$k]);
            } else {
                $data['new_files'][] = $k;
            }
            unset($GLOBALS['SYSTEM_IMAGE'][$k]);
        }
        
        foreach ($old_files as $k => $v) {
            $data['old_files'][] = $k;
        }
        
        $data['date'] = date('Y-m-d H:i:s');
        $data['img']  = $image;
        
        file_put_contents(PATH .'/cache/last_check_result.serialize', serialize($data));
        
        cmsCore::addSessionMessage($_LANG['AD_CHECK_SYSTEM_SUCCES'], 'success');
        cmsCore::redirect('index.php?view=checksystem');
    }
    
    if ($do == 'save') {
        $GLOBALS['SYSTEM_IMAGE'] = array();
        
        genSystemImage(PATH);
        
        $d = date('Y-m-d_H-i-s');
        
        file_put_contents(PATH . '/cache/system/systemImage_'. $d .'.serialize', serialize($GLOBALS['SYSTEM_IMAGE']));
        
        unset($GLOBALS['SYSTEM_IMAGE']);
        
        cmsCore::addSessionMessage($_LANG['AD_NEW_IMG_GENERATED'] .': /cache/system/systemImage_'. $d .'.serialize', 'success');
        cmsCore::redirectBack();
    }
}