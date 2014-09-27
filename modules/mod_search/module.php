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

function mod_search(){

    cmsCore::loadModel('search');
    cmsCore::loadLanguage('components/search');
    $model = cms_model_search::initModel();

    cmsPage::initTemplate('modules', 'mod_search')->
            assign('enable_components', $model->getEnableComponentsWithSupportSearch())->
            display();

    return true;

}
?>