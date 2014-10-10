<?php
define('PATH', $_SERVER['DOCUMENT_ROOT']);
include(PATH .'/core/ajax/ajax_core.php');

if (!$inUser->id) { cmsCore::error404(); }

$item = $inCore->m('content')->getArticle(cmsCore::request('item_id', 'int', 0));
$config = $inCore->loadPluginConfig('p_ckeditor');

if ($config['PCK_INLINE'] != 1) { cmsCore::error404(); }

if (!empty($item) && ($inUser->is_admin || ($item['user_id'] == $inUser->id) || ($item['modgrp_id'] == $inUser->group_id && cmsUser::isUserCan('content/autoadd'))))
{
    $content = cmsCore::badTagClear(cmsCore::request('content', 'html', ''));
    
    if (mb_strlen(strip_tags($content)) < 10) {
        cmsCore::halt($_LANG['REQ_CONTENT']);
    } else {
        $inCore->c('db')->query("UPDATE cms_content SET content='". $inCore->c('db')->escape_string($content) ."' WHERE id=". $item['id']);
    }
    
    cmsCore::halt($_LANG['ARTICLE_SAVE']);
} else {
    cmsCore::halt($_LANG['404']);
}