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

function mod_latestboard($module_id, $cfg){

    $inDB = cmsDatabase::getInstance();

    cmsCore::loadModel('board');
    $model = new cms_model_board();

    if (!isset($cfg['shownum'])){ $cfg['shownum'] = 5; }
    if (!isset($cfg['onlyvip'])){ $cfg['onlyvip'] = 0; }
    if (!isset($cfg['butvip'])){ $cfg['butvip'] = 0; }

    if (@$cfg['cat_id']) {
        if (!@$cfg['subs']){
            $model->whereCatIs($cfg['cat_id']);
        } else {
            $cat = $inDB->get_fields('cms_board_cats', "id='{$cfg['cat_id']}'", 'NSLeft, NSRight');
            if(!$cat) { return false; }
            $model->whereThisAndNestedCats($cat['NSLeft'], $cat['NSRight']);
        }
    }
    // только ВИП
    if($cfg['onlyvip'] && !$cfg['butvip']){
        $model->whereVip(1);
    }
    // кроме ВИП
    if($cfg['butvip'] && !$cfg['onlyvip']){
        $model->whereVip(0);
    }
    $inDB->orderBy('i.is_vip', 'DESC, i.pubdate DESC');
    $inDB->limitPage(1, $cfg['shownum']);

    $items = $model->getAdverts(false, true, false, true);

    cmsPage::initTemplate('modules', 'mod_latestboard')->
            assign('items', $items)->
            assign('cfg', $cfg)->
            display('mod_latestboard.tpl');

    return true;

}
?>