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

function info_module_mod_respect() {
    $_module = array(
        'title'       => 'Доска почета',
        'name'        => 'Доска почета',
        'description' => 'Отображает на сайте доску почета',
        'link'        => 'mod_respect',
        'position'    => 'sidebar',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'view_aw'     => 0,
            'view_aw'     => 5,
            'order'       => 'desc',
            'show_awards' => 1,
            'tpl'         => 'mod_respect'
        )
    );

    return $_module;
}

function install_module_mod_respect() {
    return true;
}

function upgrade_module_mod_respect() {
    return true;
}