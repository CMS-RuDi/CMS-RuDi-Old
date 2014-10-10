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

function info_module_mod_search() {
    $_module = array(
        'title'       => 'Поиск',
        'name'        => 'Поиск',
        'description' => 'Отображает поиск по сайту',
        'link'        => 'mod_search',
        'position'    => 'sidebar',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'tpl' => 'mod_search'
        )
    );

    return $_module;
}

function install_module_mod_search() {
    return true;
}

function upgrade_module_mod_search() {
    return true;
}