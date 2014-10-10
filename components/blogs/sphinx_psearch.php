<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.7                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

if (!defined('VALID_CMS')) { die('ACCESS DENIED'); }

function sphinx_add_result_blogs($items) {
    global $_LANG;
    
    foreach ($items as $id => $item) {
        $result_array = array(
            'link' => cmsCore::m('blogs')->getPostURL($item['attrs']['bloglink'], $item['attrs']['seolink']),
            'place' => $_LANG['BLOG'].' &laquo;'.$item['attrs']['cat_title'].'&raquo;',
            'placelink' => cmsCore::m('blogs')->getBlogURL($item['attrs']['bloglink']),
            'description' => cmsCore::m('search')->getProposalWithSearchWord($item['attrs']['content_html']),
            'title' => $item['attrs']['title'],
            'imageurl' => $item['attrs']['fileurl'],
            'pubdate' => date('Y-m-d H:i:s', $item['attrs']['pubdate'])
        );

        cmsCore::m('search')->addResult($result_array);
    }
    
    return;
}