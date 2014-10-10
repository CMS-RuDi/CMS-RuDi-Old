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

function info_module_mod_menu() {
    $_module = array(
        'title'       => 'Меню',
        'name'        => 'Меню',
        'description' => 'Отображает на сайте меню',
        'link'        => 'mod_menu',
        'position'    => 'topmenu',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'menu'        => 'mainmenu',
            'show_home'   => 1,
            'is_sub_menu' => 0,
            'tpl'         => 'mod_menu'
        )
    );

    return $_module;
}

function install_module_mod_menu() {
    return true;
}

function upgrade_module_mod_menu() {
    return true;
}