<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.5                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

function mod_bestcontent($mod, $cfg) {
    $cfg = array_merge(
        array(
            'shownum' => 5,
            'subs' => 1,
            'cat_id' => 1
        ),
        $cfg
    );
    
    cmsCore::c('db')->where("con.canrate = 1");
    
    
    $period = cmsCore::getArrVal($cfg, 'period', 'all');
    switch ($period) {
        case 'today':
            $date_period = date('Y-m-d') ." 00:00:00";
            break;
        case 'today_24':
            $date_period = date('Y-m-d H:i:s', time()-3600*24);
            break;
        case 'week':
            $date_period = date('Y-m-d H:i:s', time()-3600*24*7);
            break;
        case 'month':
            $date_period = date('Y-m-d H:i:s', time()-3600*24*30);
            break;
        case 'year':
            $date_period = date('Y-m-d H:i:s', time()-3600*24*365);
            break;
        default:
            break;
    }
    if (!empty($date_period)) {
        cmsCore::c('db')->where("con.pubdate >= '". $date_period ."'");
    }
    

    if ($cfg['cat_id']) {
        if (!$cfg['subs']) {
            //выбираем из категории
            cmsCore::m('content')->whereCatIs($cfg['cat_id']);
        } else {
            //выбираем из категории и подкатегорий
            $rootcat = cmsCore::c('db')->getNsCategory('cms_category', $cfg['cat_id']);
            if (!$rootcat) { return false; }
            cmsCore::m('content')->whereThisAndNestedCats($rootcat['NSLeft'], $rootcat['NSRight']);
        }
    }

    cmsCore::c('db')->orderBy('con.'. cmsCore::getArrVal($cfg, 'ordering', 'rating'), 'DESC');
    cmsCore::c('db')->limitPage(1, $cfg['shownum']);

    $content_list = cmsCore::m('content')->getArticlesList();

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('articles', $content_list)->
        assign('cfg', $cfg)->
        display();

    return true;
}