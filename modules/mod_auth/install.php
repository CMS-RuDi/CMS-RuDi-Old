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

function info_module_mod_auth() {
    $_module = array(
        'title'       => 'Авторизация',
        'name'        => 'Авторизация',
        'description' => 'Авторизация пользователей на сайте',
        'link'        => 'mod_auth',
        'position'    => 'topmenu',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'tpl' => 'mod_auth'
        )
    );

    return $_module;
}

function install_module_mod_auth() {
    return true;
}

function upgrade_module_mod_auth() {
    return true;
}