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

function fetchFiles($sql) {
    $files  = array();

    $result = cmsCore::c('db')->query($sql);

    if (cmsCore::c('db')->num_rows($result)) {
        while($file = cmsCore::c('db')->fetch_assoc($result)) {
            $file['size'] = round(($file['filesize'] / 1024) / 1024, 2);
            $files[]      = $file;
        }
    }

    return $files;
}

function mod_userfiles($module_id, $cfg) {
    $cfg = array_merge(array(
        'sw_stats'    => 1,
        'sw_latest'   => 1,
        'sw_popular'  => 1,
        'num_latest'  => 5,
        'num_popular' => 5
    ), $cfg);

    $latest     = array();
    $popular    = array();
    $stats      = array();

    //-------------------------- Новые файлы --------------------------------------

    if ($cfg['sw_latest'] && $cfg['num_latest']) {
        $sql = "SELECT f.*,
                       u.nickname as user_nickname, u.login as user_login
                FROM cms_user_files f, cms_users u
                WHERE f.user_id = u.id AND f.allow_who = 'all'
                ORDER BY f.pubdate desc
                LIMIT ". $cfg['num_latest'];

        $latest = fetchFiles($sql);
    }

    //-------------------------- Популярные файлы ---------------------------------

    if ($cfg['sw_popular'] && $cfg['num_popular']) {
        $sql = "SELECT f.*,
                       u.nickname as user_nickname, u.login as user_login
                FROM cms_user_files f, cms_users u
                WHERE f.user_id = u.id AND f.allow_who = 'all'
                ORDER BY f.hits desc
                LIMIT ". $cfg['num_popular'];

        $popular = fetchFiles($sql);
    }

    //----------------------------- Статистика ------------------------------------

    if ($cfg['sw_stats']) {
        $stats['total_files'] = cmsCore::c('db')->rows_count('cms_user_files', "allow_who='all'");
        $stats['total_size']  = 0;

        $sql = "SELECT SUM(f.filesize) as bytes
                FROM cms_user_files f
                WHERE f.allow_who = 'all'";

        $result = cmsCore::c('db')->query($sql);

        if (cmsCore::c('db')->num_rows($result)) {
            $size                = cmsCore::c('db')->fetch_assoc($result);
            $stats['total_size'] = round(($size['bytes'] / 1024) / 1024, 2);
        }
    }

    //-----------------------------------------------------------------------------

    if (!$popular && !$latest) {
        return false;
    }

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('latest', $latest)->
        assign('popular', $popular)->
        assign('stats', $stats)->
        assign('cfg', $cfg)->
        display();

    return true;
}