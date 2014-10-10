<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.4                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

function mod_user_rating($module_id, $cfg) {
    $cfg = array_merge(array(
        'count' => 20,
        'view_type' => 'rating'
    ), $cfg);
 
    if (!in_array($cfg['view_type'], array('karma', 'rating'))) {
        $cfg['view_type'] = 'rating';
    }

    cmsCore::c('db')->orderBy($cfg['view_type'], 'DESC');
    cmsCore::c('db')->limitPage(1, $cfg['count']);

    $users = cmsCore::m('users')->getUsers();
    
    cmsCore::c('db')->resetConditions();

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('users', $users)->
        assign('cfg', $cfg)->
        display();

    return true;
}