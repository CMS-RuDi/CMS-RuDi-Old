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

function info_module_mod_user_friend() {
    $_module = array(
        'title'       => 'Друзья онлайн',
        'name'        => 'Друзья онлайн',
        'description' => 'Отображает на сайте онлайн друзей',
        'link'        => 'mod_user_friend',
        'position'    => 'sidebar',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'limit'     => 5,
            'view_type' => 'table',
            'tpl'       => 'mod_user_friend'
        )
    );

    return $_module;
}

function install_module_mod_user_friend() {
    return true;
}

function upgrade_module_mod_user_friend() {
    return true;
}