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

function mod_random_image($module_id, $cfg) {
    $catsql = '';

    if ($cfg['album_id'] != 0) {
        if ($cfg['subs']) {
            $rootcat = cmsCore::c('db')->get_fields('cms_photo_albums', 'id='. $cfg['album_id'], 'NSLeft, NSRight');
            $catsql = " AND a.NSLeft >= ". $rootcat['NSLeft'] ." AND a.NSRight <= ". $rootcat['NSRight'];
        } else {
            $catsql = "AND f.album_id = ". $cfg['album_id'];
        }
    }

    $sql = "SELECT f.*, a.title album_title
            FROM cms_photo_files f
            LEFT JOIN cms_photo_albums a ON a.id = f.album_id
            WHERE f.published = 1 ". $catsql ."
            ORDER BY RAND()
            LIMIT 1
            ";

    $result = cmsCore::c('db')->query($sql) ;

    $is_img = false;

    if (cmsCore::c('db')->num_rows($result)) {
        $is_img = true;
        $item=cmsCore::c('db')->fetch_assoc($result);
    }

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('item', $item)->
        assign('is_img', $is_img)->
        assign('cfg', $cfg)->
        display();

    return true;
}