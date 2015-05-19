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

function mod_category($mod, $cfg) {
    $cfg = array_merge(
        array(
            'category_id'  => 0,
            'show_subcats' => 1,
            'expand_all'   => 1
        ),
        $cfg
    );
    
    $rootcat = cmsCore::c('db')->getNsCategory('cms_category', $cfg['category_id']);
    if (!$rootcat) { return false; }

    $subcats_list = cmsCore::m('content')->getSubCats($rootcat['id'], $cfg['show_subcats'], $rootcat['NSLeft'], $rootcat['NSRight']);
    
    if (!$subcats_list) { return false; }

    $current_seolink = urldecode(cmsCore::request('seolink', 'str', ''));

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('cfg', $cfg)->
        assign('current_seolink', $current_seolink)->
        assign('subcats_list', $subcats_list)->
        display();

    return true;
}