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

function info_module_mod_usermenu() {
    $_module = array(
        'title'       => 'Меню пользователя',
        'name'        => 'Меню пользователя',
        'description' => 'Отображает на сайте меню пользователя',
        'link'        => 'mod_usermenu',
        'position'    => 'header',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'sw_latest'   => 1,
            'sw_popular'  => 1,
            'sw_stats'    => 1,
            'num_latest'  => 5,
            'num_popular' => 5,
            'tpl'         => 'mod_userfiles'
        )
    );

    return $_module;
}

function install_module_mod_usermenu() {
    return true;
}

function upgrade_module_mod_usermenu() {
    return true;
}