<?php
define('PATH', $_SERVER['DOCUMENT_ROOT']);
include(PATH.'/core/ajax/ajax_core.php');

$do = cmsCore::request('do', array('delete', 'insert', 'get_video'));

if ($do == 'get_video') {
    cmsCore::halt(cmsCore::c('db')->get_field('cms_content_videos', "id='". cmsCore::request('video_id', 'int', 0) ."'", 'code'));
}

if (!cmsCore::c('user')->id) { cmsCore::halt(); }

$target = cmsCore::request('target', array('content'));
$target_id = cmsCore::request('target_id', 'int', 0);

if (empty($target) || empty($do)) { cmsCore::halt(); }

// Если пользователь не администратор проверяем имеет ли он право на добавление
// материала а соответственно и прикреплять видео материалы
if (!cmsCore::c('user')->is_admin) {
    if ($target == 'content') {
        if ($target_id == 0) {
            if (!cmsUser::isUserCan('content/add')) { cmsCore::halt(); }
        } else {
            $item = cmsCore::m($target)->getArticle($target_id);
            
            if (empty($item)) { cmsCore::halt(); }
            
            if(!cmsCore::c('user')->is_admin &&
                ($item['user_id'] != cmsCore::c('user')->id) &&
                !($item['modgrp_id'] == cmsCore::c('user')->group_id &&
                cmsUser::isUserCan('content/autoadd')))
            {
                cmsCore::error404();
            }
        }
    }
}

if ($do == 'delete') {
    cmsCore::c('db')->delete('cms_content_videos', "`id` = '". cmsCore::request('video_id', 'int', 0) ."'", 1);
    cmsCore::halt('OK');
}

if ($do == 'insert') {
    cmsCore::loadLanguage('plugins/p_inser_video');
    $cfg = $inCore->loadPluginConfig('p_insert_video');
    $domains = explode(',', $cfg['PIV_DOMENS']);
    foreach ($domains as $k=>$v) {
        $v = trim($v);
        if (empty($v)) {
            unset($domains[$k]);
        } else {
            $domains[$k] = $v;
        }
    }
    $domains[] = cmsCore::getHost();
    
    $code = cmsCore::request('code', 'html', '');
    $code = str_replace('&amp;', '&', $code);
    
    if (!empty($code)) {
        // Для очистки кода используем класс Jevix
        cmsCore::c('jevix')->cfgAllowTags(array('iframe','object','param','embed'));
        cmsCore::c('jevix')->cfgSetTagCutWithContent(array('script','style'));

        cmsCore::c('jevix')->cfgAllowTagParams('object', array('width' => '#int', 'height' => '#int', 'data' => array('#domain' => $domains)));
        cmsCore::c('jevix')->cfgAllowTagParams('param', array('name' => '#text', 'value' => '#text'));
        cmsCore::c('jevix')->cfgAllowTagParams('embed', array('src' => array('#domain' => $domains), 'type' => '#text', 'allowscriptaccess' => '#text', 'allowfullscreen' => '#text', 'width' => '#int', 'height' => '#int', 'flashvars' => '#text', 'wmode'=> '#text', 'quality' => '#text'));
        cmsCore::c('jevix')->cfgAllowTagParams('iframe', array('width' => '#int', 'frameborder' => '#int', 'allowfullscreen' => '#int', 'height' => '#int', 'src' => array('#domain' => $domains)));

        cmsCore::c('jevix')->cfgSetTagParamsRequired('iframe', 'src');
        cmsCore::c('jevix')->cfgSetTagParamsRequired('object', 'data');
        cmsCore::c('jevix')->cfgSetTagParamsRequired('embed', 'src');

        cmsCore::c('jevix')->cfgSetTagChilds('object','param',false,true);
        cmsCore::c('jevix')->cfgSetTagChilds('object','embed',false,false);

        cmsCore::c('jevix')->cfgSetTagIsEmpty(array('param','embed','iframe'));

        cmsCore::c('jevix')->cfgSetTagParamDefault('embed','wmode','opaque',true);

        cmsCore::c('jevix')->cfgSetAutoBrMode(false);

        cmsCore::c('jevix')->cfgSetAutoLinkMode(false);

        cmsCore::c('jevix')->cfgSetTagNoTypography('object','iframe');

        $code = cmsCore::c('jevix')->parse($code, $errors);
        // =====================
        
        // Удаляем текст между тегами если таковой был
        preg_match_all('#<(.+?)>#is', $code, $matches);
        $code = '';
        foreach ($matches[1] as $val) {
            $code .= '<'. $val .'>';
        }

        if (!empty($code)) {
            $code = str_replace('&amp;amp;', '&amp;', $code);
            
            $video_id = cmsCore::c('db')->insert(
                'cms_content_videos',
                array(
                    'target' => $target,
                    'target_id' => $target_id,
                    'code' => cmsCore::c('db')->escape_string($code),
                    'pubdate' => date('Y-m-d'),
                    'user_id' => cmsCore::c('user')->id
                )
            );

            cmsCore::jsonOutput(array('error' => false, 'id' => $video_id, 'code' => $code), false);
        }
    }
}