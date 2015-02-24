<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

if (!defined('VALID_CMS')) { die('ACCESS DENIED'); }

function sphinx_add_result_photos($items) {
    global $_LANG;
    cmsCore::loadLanguage('components/photos');
    
    foreach ($items as $id => $item) {
        $result_array = array(
            'link' => '/photos/photo'. $id .'.html',
            'place' => $_LANG['PHOTOALBUM'] .' &laquo;'. $item['attrs']['cat_title'] .'&raquo;',
            'placelink' => '/photos/'. $item['attrs']['cat_id'],
            'description' => cmsCore::m('search')->getProposalWithSearchWord($item['attrs']['description']),
            'title' => $item['attrs']['title'],
            'imageurl' => (file_exists(PATH .'/images/photos/medium/'. $item['attrs']['file']) ? '/images/photos/medium/'. $item['attrs']['file'] : ''),
            'pubdate' => date('Y-m-d H:i:s', $item['attrs']['pubdate'])
        );

        cmsCore::m('search')->addResult($result_array);
    }
    
    return;
}