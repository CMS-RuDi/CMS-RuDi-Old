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

function mod_arhive($module_id, $cfg){

    cmsCore::loadModel('arhive');
    $model = new cms_model_arhive();

    $model->whereThisAndNestedCats(@$cfg['cat_id']);

    if($model->year != 'all'){
        $model->whereYearIs();
    }

    $items = $model->getArhiveContent();
    if(!$items){ return false; }

    cmsPage::initTemplate('modules', 'mod_arhive')->
            assign('arhives', $items)->
            assign('date', array('year'=>$model->year,'month'=>$model->month,'day'=>$model->day))->
            display('mod_arhive.tpl');

    return true;

}
?>