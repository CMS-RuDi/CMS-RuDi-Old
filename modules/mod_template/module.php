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

function mod_template(){

    cmsPage::initTemplate('modules', 'mod_template')->
            assign('current_template', (isset($_SESSION['template']) ? $_SESSION['template'] : ''))->
            assign('templates', cmsCore::getDirsList('/templates'))->
            display('mod_template.tpl');

    return true;

}
?>