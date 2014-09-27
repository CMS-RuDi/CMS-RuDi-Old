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

if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }
	
function search_photos($query, $look) {
    $sql = "SELECT f.*, a.title as cat, a.id as cat_id
                    FROM cms_photo_files f
                    INNER JOIN cms_photo_albums a ON a.id = f.album_id AND a.published = 1 AND a.NSDiffer = ''
                    WHERE MATCH(f.title, f.description) AGAINST ('". $query ."' IN BOOLEAN MODE) AND f.published = 1";

    $result = cmsCore::c('db')->query($sql);

    if (cmsCore::c('db')->num_rows($result)) {
        global $_LANG;
        cmsCore::loadLanguage('components/photos');

        while ($item = cmsCore::c('db')->fetch_assoc($result)) {
            $result_array = array(
                'link' => '/photos/photo'. $item['id'] .'.html',
                'place' => $_LANG['PHOTOALBUM'] .' &laquo;'. $item['cat'] .'&raquo;',
                'placelink' => '/photos/'. $item['cat_id'],
                'description' => cmsCore::m('search')->getProposalWithSearchWord($item['description']),
                'title' => $item['title'],
                'imageurl' => (file_exists(PATH .'/images/photos/medium/'. $item['file']) ? '/images/photos/medium/'. $item['file'] : ''),
                'pubdate' => $item['pubdate']
            );

            cmsCore::m('search')->addResult($result_array);
        }
    }

    return;
}