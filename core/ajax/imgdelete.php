<?php

define('PATH', $_SERVER['DOCUMENT_ROOT']);
include(PATH.'/core/ajax/ajax_core.php');

if (!cmsCore::c('user')->id){ cmsCore::halt($_LANG['ACCESS_DENIED']); }

$image = cmsCore::c('db')->get_fields('cms_upload_images', "id='". cmsCore::request('file_id', 'int', 0) ."'", '*');

if (empty($image)){
    cmsCore::halt($_LANG['FILE'] .' '. $_LANG['NOT_FOUND']);
}

if (!empty($image['component']) && !cmsCore::c('user')->is_admin){
    if (method_exists(cmsCore::m($image['component']), 'checkAccessAddImage')){
        if (!cmsCore::m($image['component'])->checkAccessAddImage($image['target_id'], $image['target'])){
            cmsCore::halt($_LANG['ACCESS_DENIED']);
        }
    }else if (($image['session_id'] != session_id()) && ($image['user_id'] != cmsCore::c('user')->id)){
        cmsCore::halt($_LANG['ACCESS_DENIED']);
    }
}

cmsCore::deleteUploadImage($image['fileurl'], $image['component']);
cmsCore::c('db')->query("DELETE FROM `cms_upload_images` WHERE `id` = '". $image['id'] ."' LIMIT 1");

if (!empty($image['component'])){
    if (method_exists(cmsCore::m($image['component']), 'updateUploadImages')){
        cmsCore::m($image['component'])->updateUploadImages($image['target_id']);
    }
}

cmsCore::halt('OK');