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

function mod_clubs($module_id, $cfg) {
    $cfg = array_merge(array(
        'count'      => 5,
        'type'       => 'id',
        'vip_on_top' => 1
    ), $cfg);

    if ($cfg['vip_on_top']) {
        cmsCore::c('db')->orderBy('is_vip', 'DESC, c.'. $cfg['type'] .' DESC');
    } else {
        cmsCore::c('db')->orderBy('c.'. $cfg['type'], 'DESC');
    }
    
    cmsCore::c('db')->limit($cfg['count']);

    $clubs = cmsCore::m('clubs')->getClubs();
    
    if (!$clubs) { return false; }

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('clubs', $clubs)->
        display();

    return true;
}