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

$opt = cmsCore::request('opt', 'str', 'config');

$toolmenu = array(
    array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.optform.submit();' ),
    array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => '?view=components' )
);

cpToolMenu($toolmenu);

$cfg = $inCore->loadComponentConfig('rssfeed');

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
    
    $cfg = array();
    $cfg['addsite']  = cmsCore::request('addsite', 'int');
    $cfg['maxitems'] = cmsCore::request('maxitems', 'int');
    $cfg['icon_on']  = cmsCore::request('icon_on', 'int');
    $cfg['icon_url'] = cmsCore::request('icon_url', 'str', '');
    
    $inCore->saveComponentConfig('rssfeed', $cfg);
    
    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

cmsCore::c('page')->initTemplate('components', 'rssfeed_config')->
    assign('cfg', $cfg)->
    display();