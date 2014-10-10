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

function info_module_mod_user_stats() {
    $_module = array(
        'title'       => 'Статистика пользователей',
        'name'        => 'Статистика пользователей',
        'description' => 'Отображает на сайте статистику пользователей',
        'link'        => 'mod_user_stats',
        'position'    => 'sidebar',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'show_total'  => 1,
            'show_online' => 1,
            'show_gender' => 1,
            'show_city'   => 1,
            'show_bday'   => 1,
            'tpl'         => 'mod_user_stats'
        )
    );

    return $_module;
}

function install_module_mod_user_stats() {
    return true;
}

function upgrade_module_mod_user_stats() {
    return true;
}