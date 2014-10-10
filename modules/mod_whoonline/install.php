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

function info_module_mod_whoonline() {
    $_module = array(
        'title'       => 'Кто онлайн?',
        'name'        => 'Кто онлайн?',
        'description' => 'Отображает на сайте online пользователей',
        'link'        => 'mod_whoonline',
        'position'    => 'sidebar',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'show_today'   => 1,
            'admin_editor' => 1,
            'color_admin'  => '#FF0000',
            'color_editor' => '#009900',
            'tpl'          => 'mod_whoonline'
        )
    );

    return $_module;
}

function install_module_mod_whoonline() {
    return true;
}

function upgrade_module_mod_whoonline() {

    return true;
}