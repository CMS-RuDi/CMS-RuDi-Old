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
	
function search_board($query, $look) {
    $sql = "SELECT f.*, f.title as title, a.title as cat, a.id as cat_id
                    FROM cms_board_items f
                    INNER JOIN cms_board_cats a ON a.id = f.category_id AND a.published = 1
                    WHERE MATCH(f.title, f.content) AGAINST ('". $query ."' IN BOOLEAN MODE) AND f.published = 1 LIMIT 100";

    $result = cmsCore::c('db')->query($sql);

    if (cmsCore::c('db')->num_rows($result)) {
        while($item = cmsCore::c('db')->fetch_assoc($result)) {
            $result_array = array(
                'link' => '/board/read'. $item['id'] .'.html',
                'place' => $item['cat'],
                'placelink' => '/board/'. $item['cat_id'],
                'description' => cmsCore::m('search')->getProposalWithSearchWord($item['content']),
                'title' => $item['obtype'] .' '. $item['title'],
                'imageurl' => (file_exists(PATH .'/images/board/medium/'. $item['file']) ? '/images/board/medium/'. $item['file'] : ''),
                'pubdate' => $item['pubdate']
            );

            cmsCore::m('search')->addResult($result_array);		
        }
    }

    return;
}