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

function routes_rssfeed() {
    return array(
        array(
            '_uri' => '/^rssfeed\/([a-z]+)\/(.+)$/i',
            1      => 'target',
            2      => 'item_id'
        )
    );
}