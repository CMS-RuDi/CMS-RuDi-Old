<?php
define('PATH', $_SERVER['DOCUMENT_ROOT']);
include(PATH.'/core/ajax/ajax_core.php');

if (!$inUser->id){ cmsCore::halt('ERROR'); }

$image_id = cmsCore::request('file_id', 'int', 0);

$image = $inDB->get_fields('cms_content_images', "id='". $image_id ."'", '*');

if (empty($image)){ cmsCore::halt('ERROR'); }

if (empty($image['target_id']) && $image['session_id'] != session_id()){
    cmsCore::halt('ERROR');
}

if (!empty($image['target_id'])){
    $item = $inDB->get_fields('cms_content', "id='". $image['target_id'] ."'", '*');
    if (!$item){ cmsCore::halt('ERROR'); }
    
    if(!$inUser->is_admin &&
        ($item['user_id'] != $inUser->id) &&
        ($item['modgrp_id'] != $inUser->group_id))
    {
        cmsCore::halt('ERROR');
    }
}

$inDB->query("DELETE FROM `cms_content_images` WHERE `id`='". $image['id'] ."' LIMIT 1");

if (file_exists(PATH .'/upload/content/small/'. $image['fileurl'])){
    unlink(PATH .'/upload/content/small/'. $image['fileurl']);
}
if (file_exists(PATH .'/upload/content/medium/'. $image['fileurl'])){
    unlink(PATH .'/upload/content/medium/'. $image['fileurl']);
}
if (file_exists(PATH .'/upload/content/big/'. $image['fileurl'])){
    unlink(PATH .'/upload/content/big/'. $image['fileurl']);
}

cmsCore::halt('OK');
?>