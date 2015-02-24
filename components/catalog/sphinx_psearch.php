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

function sphinx_add_result_catalog($items) {
    global $_LANG;
    
    foreach ($items as $id => $item) {
        $result_array = array(
            'link' => '/catalog/item'. $id .'.html',
            'place' => $item['attrs']['cat_title'],
            'placelink' => '/catalog/'. $item['attrs']['cat_id'],
            'title' => $item['attrs']['title'],
            'imageurl' => (file_exists(PATH .'/images/catalog/medium/'. $item['attrs']['imageurl']) ? '/images/catalog/medium/'. $item['attrs']['imageurl'] : ''),
            'pubdate' => date('Y-m-d H:i:s', $item['attrs']['pubdate'])
        );

        cmsCore::m('search')->addResult($result_array);
    }
    
    return;
}