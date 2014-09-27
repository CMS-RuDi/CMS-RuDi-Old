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
	
function search_faq($query, $look) {
    global $_LANG;

    $sql = "SELECT con.*, cat.title cat_title, cat.id cat_id
                    FROM cms_faq_quests con
                    INNER JOIN cms_faq_cats cat ON cat.id = con.category_id AND cat.published = 1
                    WHERE MATCH(con.quest, con.answer) AGAINST ('". $query ."' IN BOOLEAN MODE) AND con.published = 1 LIMIT 100";

    $result = cmsCore::c('db')->query($sql);

    if (cmsCore::c('db')->num_rows($result)) {
        cmsCore::loadLanguage('components/faq');

        while($item = cmsCore::c('db')->fetch_assoc($result)) {
            $result_array = array(
                'link' => '/faq/quest'. $item['id'] .'.html',
                'place' => $_LANG['FAQ'] .' &rarr; '. $item['cat_title'],
                'placelink' => '/faq/'. $item['cat_id'],
                'description' => cmsCore::m('search')->getProposalWithSearchWord($item['answer']),
                'title' => mb_substr($item['quest'], 0, 70) .'...',
                'pubdate' => $item['pubdate']
            );
            
            cmsCore::m('search')->addResult($result_array);			
        }
    }

    return;
}