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
	
function search_content($query, $look) {
    global $_LANG;

    $sql = "SELECT con.*, cat.title cat_title, cat.id cat_id, cat.seolink as cat_seolink, cat.parent_id as cat_parent_id
                FROM cms_content con
                INNER JOIN cms_category cat ON cat.id = con.category_id AND cat.published = 1
                WHERE MATCH(con.title, con.content) AGAINST ('". $query ."' IN BOOLEAN MODE) AND con.is_end = 0 AND con.published = 1 LIMIT 100";

    $result = cmsCore::c('db')->query($sql);

    if (cmsCore::c('db')->num_rows($result)) {
        cmsCore::loadLanguage('components/content');

        while ($item = cmsCore::c('db')->fetch_assoc($result)) {
            $result_array = array(
                'link' => '/'. $item['seolink'] .'.html',
                'place' => $_LANG['CATALOG_ARTICLES'],
                'placelink' => '/'. ($item['cat_parent_id']>0 ?  $item['cat_seolink'] : $item['seolink'] .'.html'),
                'description' => cmsCore::m('search')->getProposalWithSearchWord($item['content']),
                'title' => $item['title'],
                'imageurl' => (file_exists(PATH .'/images/content/medium/'. ceil($item['id']/100) .'/article'. $item['id' ] .'.jpg') ? '/images/content/medium/'. ceil($item['id']/100) .'/article'. $item['id' ] .'.jpg' : ''),
                'pubdate' => $item['pubdate']
            );

            cmsCore::m('search')->addResult($result_array);
        }
    }

    return;
}