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

function info_module_mod_rss() {
    $_module = array(
        'title'       => 'RSS-ридер',
        'name'        => 'RSS-ридер',
        'description' => 'Отображает на сайте RSS ленту',
        'link'        => 'mod_rss',
        'position'    => 'maintop',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'rssurl'     => 'http://www.lenta.ru/rss',
            'showdesc'   => 1,
            'itemslimit' => 5,
            'cachetime'  => 1,
            'cols'       => 2,
            'showicon'   => 1,
            'tpl'        => 'mod_rss'
        )
    );

    return $_module;
}

function install_module_mod_rss() {
    return true;
}

function upgrade_module_mod_rss() {
    return true;
}