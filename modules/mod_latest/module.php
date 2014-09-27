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

function mod_latest($module_id, $cfg){
    
    $cfg = array_merge(
        array(
            'showrss' => 1,
            'subs' => 1,
            'cat_id' => 1,
            'newscount' => 5,
            'is_pag' => 0,
            'page' => 1
        ),
        $cfg
    );

    if($cfg['cat_id']){
        if (!$cfg['subs']){
            //выбираем из категории
            cmsCore::m('content')->whereCatIs($cfg['cat_id']);
        } else {
            //выбираем из категории и подкатегорий
            $rootcat = cmsCore::c('db')->getNsCategory('cms_category', $cfg['cat_id']);
            if(!$rootcat) { return false; }
            cmsCore::m('content')->whereThisAndNestedCats($rootcat['NSLeft'], $rootcat['NSRight']);
        }
    }

    cmsCore::c('db')->where("con.showlatest = 1");

    if ($cfg['is_pag']){
        $total = cmsCore::m('content')->getArticlesCount();
    }

    cmsCore::c('db')->orderBy('con.pubdate', 'DESC');
    cmsCore::c('db')->limitPage($cfg['page'], $cfg['newscount']);

    $content_list = cmsCore::m('content')->getArticlesList();
    if(!$content_list) { return false; }

    $pagebar = $cfg['is_pag'] ?
                cmsPage::getPagebar($total, $cfg['page'], $cfg['newscount'], 'javascript:conPage(%page%, '.$module_id.')') : '';

    cmsPage::initTemplate('modules', 'mod_latest')->
        assign('articles', $content_list)->
        assign('pagebar_module', $pagebar)->
        assign('module_id', $module_id)->
        assign('cfg', $cfg)->
        display();

    return true;
}
?>