<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.8                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

if (!defined('VALID_CMS')) { die('ACCESS DENIED'); }

function sphinx_add_result_content($items) {
    global $_LANG;
    
    cmsCore::loadLanguage('components/content');
    
    foreach ($items as $id => $item) {
        $result_array = array(
            'link' => '/'. $item['attrs']['seolink'] .'.html',
            'place' => $_LANG['CATALOG_ARTICLES'],
            'placelink' => $item['attrs']['cat_parent_id']>0 ? '/'. $item['attrs']['cat_seolink'] : '/'. $item['attrs']['seolink'] .'.html',
            'description' => cmsCore::m('search')->getProposalWithSearchWord($item['attrs']['content']),
            'title' => $item['attrs']['title'],
            'imageurl' => (file_exists(PATH .'/images/content/medium/'. ceil($id/100) .'/article'. $id .'.jpg') ? '/images/content/medium/'. ceil($id/100) .'/article'. $id .'.jpg' : ''),
            'pubdate' => date('Y-m-d H:i:s', $item['attrs']['pubdate'])
        );

        cmsCore::m('search')->addResult($result_array);
    }
    
    return;
}