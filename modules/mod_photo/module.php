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

function mod_photo($module_id, $cfg) {
    $cfg = array_merge(array(
        'user_photos' => 0,
        'is_full'     => 1,
        'showmore'    => 1,
        'album_id'    => 0,
        'whatphoto'   => 'all',
        'shownum'     => 5,
        'maxcols'     => 2,
        'sort'        => 'pubdate',
        'showclubs'   => 0,
        'is_subs'     => 1,
        'is_lightbox' => 1
    ), $cfg);

    // Задаем период
    cmsCore::c('photo')->wherePeriodIs($cfg['whatphoto']);
    
    //устанавливаем номер текущей страницы и кол-во фото на странице
    cmsCore::c('db')->limit($cfg['shownum']);
    
    if (!$cfg['user_photos']) {
        // выбираем категории фото
        cmsCore::c('db')->addJoin('INNER JOIN cms_photo_albums a ON a.id = f.album_id AND a.published = 1');
        cmsCore::c('db')->addSelect('a.title as cat_title, a.NSDiffer');

        // если категория задана, выбираем из нее
        if ($cfg['album_id']) {
            // Если выбирать нужно включая вложенные
            if ($cfg['is_subs']) {
                // получаем категорию
                $album = cmsCore::c('db')->getNsCategory('cms_photo_albums', $cfg['album_id']);
                if (!$album) { return false; }

                cmsCore::c('photo')->whereThisAndNestedCats($album['NSLeft'], $album['NSRight']);
            } else {
                cmsCore::c('photo')->whereAlbumIs($cfg['album_id']);
            }
        }

        // если фото клубов не нужны
        if (!$cfg['showclubs']) {
            cmsCore::c('db')->where("f.owner = 'photos'");
        }

        //устанавливаем сортировку
        cmsCore::c('db')->orderBy('f.'.$cfg['sort'], 'DESC');

        // получаем фото
        $photos = cmsCore::c('photo')->getPhotos(false, $cfg['is_full']);
        
        if (empty($photos)) { return false; }
        
        $tpl = $cfg['tpl'];
    } else {
        if ($cfg['sort'] == 'rating') { $cfg['sort'] = 'hits'; }
        
        $sql = "SELECT u.id uid, u.nickname author, u.login as login, f.title, f.id, f.album_id, pr.gender gender, f.imageurl as file, f.pubdate, a.title as album_title FROM cms_user_photos f
		INNER JOIN cms_user_albums a ON a.id = f.album_id
                LEFT JOIN cms_users u ON u.id = f.user_id
                LEFT JOIN cms_user_profiles pr ON pr.user_id = u.id
                WHERE f.allow_who='all' AND u.is_deleted = 0 AND u.is_locked = 0
                      AND f.album_id > 0 AND a.allow_who = 'all'
                      ". cmsCore::c('db')->where ."
                ORDER BY f.". $cfg['sort'] ." DESC \n";

        if (cmsCore::c('db')->limit) {
            $sql .= "LIMIT ". cmsCore::c('db')->limit;
        }

        $result = cmsCore::c('db')->query($sql);

        cmsCore::c('db')->resetConditions();

        if (!cmsCore::c('db')->num_rows($result)) { return false; }

        $photos = array();

        while ($photo = cmsCore::c('db')->fetch_assoc($result)) {
            if ($cfg['is_full']) {
                $photo['comments'] = cmsCore::getCommentsCount('userphoto', $photo['id']);
            }

            $photo['pubdate'] = cmsCore::dateFormat($photo['pubdate'], false, false, false);
            $photos[] = $photo;
        }
        
        cmsCore::callEvent('GET_PHOTOS', $photos);
        
        $tpl = 'mod_user_photo';
    }
    
    cmsPage::initTemplate('modules', $tpl)->
        assign('photos', $photos)->
        assign('cfg', $cfg)->
        display();

    return true;
}