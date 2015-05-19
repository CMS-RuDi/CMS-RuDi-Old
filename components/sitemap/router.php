<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/
if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

function routes_sitemap() {
    return array(
        array(
            '_uri' => '#^sitemap/gen$#is',
            'do'   => 'gen'
        ),
        array(
            '_uri' => '#^sitemap/([a-z0-9]+)\.html$#is',
            'do'   => 'view_component',
            1      => 'component'
        ),
        array(
            '_uri' => '#^sitemap/([a-z0-9]+)_([a-z]{0,})_([0-9]+)\.html$#is',
            'do'   => 'view_section',
            1      => 'component',
            2      => 'target',
            3      => 'target_id'
        )
    );
}