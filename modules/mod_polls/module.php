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

function mod_polls($module_id, $cfg){

    cmsCore::loadModel('polls');
    $model = new cms_model_polls();

    if ($cfg['poll_id']>0){

        $poll = $model->getPoll($cfg['poll_id']);

    } else {

        $poll = $model->getPoll(0, 'RAND()');

    }

    if (!$poll) { return false; }

	cmsPage::initTemplate('modules', 'mod_polls')->
            assign('poll', $poll)->
            assign('is_voted', $model->isUserVoted($poll['id']))->
            assign('module_id', $module_id)->
            assign('cfg', $cfg)->
            display('mod_polls.tpl');

    return true;

}
?>