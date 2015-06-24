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

if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function applet_config() {
    // получаем оригинальный конфиг
    $config = cmsConfig::getDefaultConfig();
    
    global $_LANG;
    
    global $adminAccess;
    
    if (!cmsUser::isAdminCan('admin/config', $adminAccess)) { cpAccessDenied(); }
    
    cmsCore::c('page')->setTitle($_LANG['AD_SITE_SETTING']);

    cpAddPathway($_LANG['AD_SITE_SETTING'], 'index.php?view=config');

    $do = cmsCore::request('do', 'str', 'list');

    if ($do == 'save') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $newCFG = cmsCore::getArrayFromRequest(array(
            'scheme'                  => array('scheme', array('http', 'https'), ''),
            'sitename'                => array('sitename', 'str', ''),
            'title_and_sitename'      => array('title_and_sitename', 'int', 0),
            'title_and_page'          => array('title_and_page', 'int', 0),
            'hometitle'               => array('hometitle', 'str', ''),
            'homecom'                 => array('homecom', 'str', ''),
            'com_without_name_in_url' => array('com_without_name_in_url', 'str', ''),
            'siteoff'                 => array('siteoff', 'int', 0),
            'only_authorized'         => array('only_authorized', 'int', 0),
            'debug'                   => array('debug', 'int', 0),
            'offtext'                 => array('offtext', 'str', ''),
            'keywords'                => array('keywords', 'str', ''),
            'metadesc'                => array('metadesc', 'str', ''),
            'seourl'                  => array('seourl', 'int', 0),
            'lang'                    => array('lang', 'str', 'ru'),
            'is_change_lang'          => array('is_change_lang', 'int', 0),
            'sitemail'                => array('sitemail', 'str', ''),
            'sitemail_name'           => array('sitemail_name', 'str', ''),
            'wmark'                   => array('wmark', 'str', ''),
            'template'                => array('template', 'str', ''),
            'cache'                   => array('cache', 'int', 0),
            'cache_type'              => array('cache_type', array('file', 'memcached'), ''),
            'memcached_host'          => array('memcached_host', 'str', ''),
            'memcached_port'          => array('memcached_port', 'int', 0),
            'combine_css_enable'      => array('combine_css_enable', 'int', 0),
            'combine_css'             => array('combine_css', 'html', ''),
            'combine_js_enable'       => array('combine_js_enable', 'int', 0),
            'combine_js'              => array('combine_js', 'html', ''),
            'splash'                  => array('splash', 'int', 0),
            'slight'                  => array('slight', 'int', 0),
            'show_pw'                 => array('show_pw', 'int', 0),
            'last_item_pw'            => array('last_item_pw', 'int', 0),
            'index_pw'                => array('index_pw', 'int', 0),
            'fastcfg'                 => array('fastcfg', 'int', 0),
            'mailer'                  => array('mailer', 'str', ''),
            'smtpsecure'              => array('smtpsecure', 'str', ''),
            'smtpauth'                => array('smtpauth', 'int', 0),
            'smtpuser'                => array('smtpuser', 'str', $config['smtpuser']),
            'smtppass'                => array('smtppass', 'str', $config['smtppass']),
            'smtphost'                => array('smtphost', 'str', ''),
            'smtpport'                => array('smtpport', 'int', '25'),
            'timezone'                => array('timezone', 'str', $config['timezone']),
            'user_stats'              => array('user_stats', 'int', 0),
            'seo_url_count'           => array('seo_url_count', 'int', 0),
            'max_pagebar_links'       => array('max_pagebar_links', 'int', 0),
            'allow_ip'                => array('allow_ip', 'str', ''),
            'iframe_enable'           => array('iframe_enable', 'int', 0),
            'vk_enable'               => array('vk_enable', 'int', 0),
            'vk_id'                   => array('vk_id', 'str', ''),
            'vk_private_key'          => array('vk_private_key', 'str', ''),
        ));
        
        $newCFG['sitename']  = stripslashes($newCFG['sitename']);
        $newCFG['hometitle'] = stripslashes($newCFG['hometitle']);
        $newCFG['offtext']   = htmlspecialchars($newCFG['offtext'], ENT_QUOTES);
        $newCFG['db_host']   = $config['db_host'];
        $newCFG['db_base']   = $config['db_base'];
        $newCFG['db_user']   = $config['db_user'];
        $newCFG['db_pass']   = $config['db_pass'];
        $newCFG['db_prefix'] = $config['db_prefix'];
        
        if (cmsConfig::saveToFile($newCFG)) {
            cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'] , 'success');
        } else {
            cmsCore::addSessionMessage($_LANG['AD_CONFIG_SITE_ERROR'], 'error');
        }

        cmsCore::clearCache();
        cmsCore::redirect('index.php?view=config');
    }

    cpCheckWritable('/includes/config/config.inc.json');
    
    $result = cmsCore::c('db')->query("SELECT (sum(data_length)+sum(index_length))/1024/1024 as size FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = '". $config['db_base'] ."'", true);
    if (!cmsCore::c('db')->error()) {
        $s = cmsCore::c('db')->fetch_assoc($result);
    } else {
        $s['size'] = 0;
    }
    
    cmsCore::c('page')->initTemplate('applets', 'config')->
        assign('config', $config)->
        assign('timezone_opt', cmsCore::getTimeZonesOptions($config['timezone']))->
        assign('templates', cmsCore::getDirsList('/templates'))->
        assign('tpl_info', cmsCore::c('page')->getTplInfo(cmsCore::c('page')->template))->
        assign('components_opt', cmsCore::getListItems('cms_components', $config['com_without_name_in_url'], 'title', 'ASC', 'internal=0', 'link'))->
        assign('homecom_opt', cmsCore::getListItems('cms_components', $config['homecom'], 'title', 'ASC', 'internal=0', 'link'))->
        assign('langs', cmsCore::getDirsList('/languages'))->
        assign('db_size', round($s['size'], 2))->
        display();
}