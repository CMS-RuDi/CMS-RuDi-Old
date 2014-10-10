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

function mod_latest_faq($module_id, $cfg) {
    $cfg = array_merge(array(
        'newscount' => 2,
        'cat_id'    => 0,
        'maxlen'    => 120
    ), $cfg);

    if ($cfg['cat_id']) {
        $catsql = 'AND category_id = '. $cfg['cat_id'];
    } else {
        $catsql = '';
    }

    $sql = "SELECT *
            FROM cms_faq_quests
            WHERE published = 1 ". $catsql ."
            ORDER BY pubdate DESC
            LIMIT ". $cfg['newscount'];

    $result = cmsCore::c('db')->query($sql) ;

    $faq = array();

    if (cmsCore::c('db')->num_rows($result)) {
        while($con = cmsCore::c('db')->fetch_assoc($result)) {
            $con['date'] = cmsCore::dateFormat($con['pubdate']);
            $con['href'] = '/faq/quest'.$con['id'].'.html';
            $faq[] = $con;
        }
    }

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('faq', $faq)->
        assign('cfg', $cfg)->
        display();

    return true;
}