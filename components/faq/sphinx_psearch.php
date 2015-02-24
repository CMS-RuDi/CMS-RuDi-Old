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

function sphinx_add_result_faq($items) {
    global $_LANG;
    cmsCore::loadLanguage('components/faq');
    
    foreach ($items as $id => $item) {
        $result_array = array(
            'link' => '/faq/quest'. $id .'.html',
            'place' => $_LANG['FAQ'] .' &rarr; '. $item['attrs']['cat_title'],
            'placelink' => '/faq/'. $item['attrs']['cat_id'],
            'description' => cmsCore::m('search')->getProposalWithSearchWord($item['attrs']['answer']),
            'title' => mb_substr($item['attrs']['quest'], 0, 70) .'...',
            'pubdate' => date('Y-m-d H:i:s', $item['attrs']['pubdate'])
        );

        cmsCore::m('search')->addResult($result_array);
    }
    
    return;
}