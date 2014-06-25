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

function mod_usersearch($module_id, $cfg){

    cmsCore::loadLanguage('components/users');

    cmsPage::initTemplate('modules', 'mod_usersearch')->
            assign('cfg', $cfg)->
            display('mod_usersearch.tpl');

    return true;

}
?>