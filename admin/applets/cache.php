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

function applet_cache() {
    $component = cmsCore::request('component', 'str', '');
    $target    = cmsCore::request('target', 'str', '');
    $target_id = cmsCore::request('target_id', 'str', '');

    if (empty($component) || empty($target_id)) { cmsCore::error404(); }
    
    cmsCore::c('cache')->remove($component, $target_id, $target);

    cmsCore::redirectBack();
}