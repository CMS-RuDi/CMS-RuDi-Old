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

function sphinx_add_result_board($items) {
    global $_LANG;
    
    foreach ($items as $id => $item) {
        $result_array = array(
            'link' => '/board/read'. $id .'.html',
            'place' => $item['attrs']['cat_title'],
            'placelink' => '/board/'. $item['attrs']['cat_id'],
            'description' => cmsCore::m('search')->getProposalWithSearchWord($item['attrs']['content']),
            'title' => $item['attrs']['obtype'] .' '. $item['attrs']['title'],
            'imageurl' => (file_exists(PATH .'/images/board/medium/'. $item['attrs']['file']) ? '/images/board/medium/'. $item['attrs']['file'] : ''),
            'pubdate' => date('Y-m-d H:i:s', $item['attrs']['pubdate'])
        );

        cmsCore::m('search')->addResult($result_array);
    }
    
    return;
}