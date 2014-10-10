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

function info_module_mod_lastreg() {
    $_module = array(
        'title'       => 'Новые пользователи',
        'name'        => 'Новые пользователи',
        'description' => 'Отображает на сайте новых пользователей',
        'link'        => 'mod_lastreg',
        'position'    => 'sidebar',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'newscount' => 5,
            'view_type' => 'table',
            'maxcool'   => 2,
            'tpl'       => 'mod_lastreg'
        )
    );

    return $_module;
}

function install_module_mod_lastreg() {
    return true;
}

function upgrade_module_mod_lastreg() {
    return true;
}