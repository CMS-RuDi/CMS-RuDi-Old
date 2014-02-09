<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.3                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2013                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

function mod_lastreg($module_id, $cfg){

    $inDB = cmsDatabase::getInstance();

    cmsCore::loadModel('users');
    $model = new cms_model_users();

    $inDB->orderBy('regdate', 'DESC');

    $inDB->limitPage(1, $cfg['newscount']);

    $users = $model->getUsers();

    if ($cfg['view_type']=='list'){
        $total_all = cmsUser::getCountAllUsers();
    } else { $total_all = 0; }

    cmsPage::initTemplate('modules', 'mod_lastreg')->
            assign('usrs', $users)->
            assign('cfg', $cfg)->
            assign('total_all', $total_all)->
            assign('total', sizeof($users))->
            display('mod_lastreg.tpl');

    return true;

}
?>