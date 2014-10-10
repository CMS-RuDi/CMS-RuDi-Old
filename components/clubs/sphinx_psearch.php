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

function sphinx_add_result_clubs($items) {
    global $_LANG;
    
    cmsCore::m('clubs');
    $search_model = cms_model_search::initModel();
    
    foreach ($items as $id => $item) {
        $result_array = array(
            'link' => cmsCore::m('clubs')->getPostURL($item['attrs']['user_id'], $item['attrs']['seolink']),
            'place' => ' &laquo;'. $item['attrs']['cat_title'] .'&raquo;',
            'placelink' => cmsCore::m('clubs')->getBlogURL($item['attrs']['user_id']),
            'description' => $search_model->getProposalWithSearchWord($item['attrs']['content_html']),
            'title' => $item['attrs']['title'],
            'imageurl' => $item['fileurl'],
            'pubdate' => date('Y-m-d H:i:s', $item['attrs']['pubdate'])
        );

        $search_model->addResult($result_array);
    }
    
    /////// поиск по клубным фоткам //////////
    $cl = new SphinxClient();

    $cl->SetServer('127.0.0.1', 9312);
    $cl->SetMatchMode(SPH_MATCH_EXTENDED2);
    $cl->SetLimits(0, 100);
    
    $result = $cl->Query($search_model->against, $search_model->config['Sphinx_Search']['prefix'] .'_clubs_photos');
            
    if ($result !== false) {
        foreach ($result['matches'] as $id => $item) {
            $result_array = array(
                'link' => '/clubs/photo'. $id .'.html',
                'place' => $_LANG['CLUBS_PHOTOALBUM'] .' &laquo;'. $item['attrs']['cat_title'] .'&raquo;',
                'placelink' => '/clubs/photoalbum'. $item['attrs']['cat_id'],
                'description' => $search_model->getProposalWithSearchWord($item['attrs']['description']),
                'title' => $item['attrs']['title'],
                'imageurl' => (file_exists(PATH .'/images/photos/medium/'. $item['attrs']['file']) ? '/images/photos/medium/'. $item['attrs']['file'] : ''),
                'pubdate' => date('Y-m-d H:i:s', $item['attrs']['pubdate'])
            );

            $search_model->addResult($result_array);
        }
    }
    
    return;
}