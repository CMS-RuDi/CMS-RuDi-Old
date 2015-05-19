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

function mod_latestboard($mod, $cfg) {
    $cfg = array_merge(array(
        'shownum' => 5,
        'onlyvip' => 0,
        'butvip'  => 0,
        'cat_id'  => 0,
        'subs'    => 0
    ), $cfg);

    $inDB = cmsDatabase::getInstance();

    if ($cfg['cat_id']) {
        if (!$cfg['subs']) {
            cmsCore::m('board')->whereCatIs($cfg['cat_id']);
        } else {
            $cat = cmsCore::c('db')->get_fields('cms_board_cats', "id='". $cfg['cat_id'] ."'", 'NSLeft, NSRight');
            if (!$cat) { return false; }
            cmsCore::m('board')->whereThisAndNestedCats($cat['NSLeft'], $cat['NSRight']);
        }
    }
    
    // только ВИП
    if($cfg['onlyvip'] && !$cfg['butvip']) {
        cmsCore::m('board')->whereVip(1);
    }
    
    // кроме ВИП
    if($cfg['butvip'] && !$cfg['onlyvip']) {
        cmsCore::m('board')->whereVip(0);
    }
    
    cmsCore::c('db')->orderBy('i.is_vip', 'DESC, i.pubdate DESC');
    cmsCore::c('db')->limitPage(1, $cfg['shownum']);

    $items = cmsCore::m('board')->getAdverts(false, true, false, true);

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('items', $items)->
        assign('cfg', $cfg)->
        display();

    return true;
}