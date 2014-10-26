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

function mod_lastreg($module_id, $cfg) {
    cmsCore::c('db')->orderBy('regdate', 'DESC');
    cmsCore::c('db')->limitPage(1, $cfg['newscount']);

    $users = cmsCore::m('users')->getUsers();

    if ($cfg['view_type'] == 'list') {
        $total_all = cmsUser::getCountAllUsers();
    } else {
        $total_all = 0;
    }

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('users', $users)->
        assign('cfg', $cfg)->
        assign('total_all', $total_all)->
        assign('total', sizeof($users))->
        display();

    return true;
}