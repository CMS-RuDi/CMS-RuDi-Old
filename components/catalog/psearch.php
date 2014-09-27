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
	
function search_catalog($query, $look) {
    $sql = "SELECT i.*, c.title as cat, c.id as cat_id
                    FROM cms_uc_items i
                    INNER JOIN cms_uc_cats c ON c.id = i.category_id AND c.published = 1
                    WHERE MATCH(i.title, i.fieldsdata) AGAINST ('". $query ."' IN BOOLEAN MODE) AND i.published = 1 LIMIT 100";

    $result = cmsCore::c('db')->query($sql);

    if (cmsCore::c('db')->num_rows($result)) {
        while($item = cmsCore::c('db')->fetch_assoc($result)) {
            $result_array = array(
                'link' => '/catalog/item'. $item['id'] .'.html',
                'place' => $item['cat'],
                'placelink' => '/catalog/'. $item['cat_id'],
                'title' => $item['title'],
                'imageurl' => (file_exists(PATH .'/images/catalog/medium/'. $item['imageurl']) ? '/images/catalog/medium/'. $item['imageurl'] : ''),
                'pubdate' => $item['pubdate']
            );
            
            cmsCore::m('search')->addResult($result_array);			
        }
    }

    return;
}