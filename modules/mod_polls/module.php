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

function mod_polls($module_id, $cfg) {
    if ($cfg['poll_id'] > 0) {
        $poll = cmsCore::m('polls')->getPoll($cfg['poll_id']);
    } else {
        $poll = cmsCore::m('polls')->getPoll(0, 'RAND()');
    }

    if (!$poll) { return false; }

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('poll', $poll)->
        assign('is_voted', cmsCore::m('polls')->isUserVoted($poll['id']))->
        assign('module_id', $module_id)->
        assign('cfg', $cfg)->
        display();

    return true;
}