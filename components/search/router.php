<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.5                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/
if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

function routes_search() {
    return array(
        array(
            '_uri'  => '/^search\/tag\/(.+)\/page([0-9]+).html$/i',
            'do'    => 'tag',
            1       => 'query',
            2       => 'page'
        ),
        array(
            '_uri'  => '/^search\/tag\/(.+)$/i',
            'do'    => 'tag',
            1       => 'query'
        )
    );
}