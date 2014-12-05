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

function mod_arhive($module_id, $cfg) {
    cmsCore::m('arhive')->whereThisAndNestedCats(cmsCore::getArrVal($cfg, 'cat_id', 0));

    if (cmsCore::m('arhive')->year != 'all') {
        cmsCore::m('arhive')->whereYearIs();
    }

    $items = cmsCore::m('arhive')->getArhiveContent();
    if (!$items) { return false; }

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('arhives', $items)->
        assign('date', array('year'=>cmsCore::m('arhive')->year,'month'=>cmsCore::m('arhive')->month,'day'=>cmsCore::m('arhive')->day))->
        display();

    return true;
}