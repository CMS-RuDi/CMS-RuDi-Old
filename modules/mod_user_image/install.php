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

function info_module_mod_user_image() {
    $_module = array(
        'title'       => 'Случайное фото',
        'name'        => 'Случайное фото',
        'description' => 'Отображает на сайте случайное фото пользователей',
        'link'        => 'mod_user_image',
        'position'    => 'sidebar',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'showtitle' => 1,
            'tpl'       => 'mod_user_image'
        )
    );

    return $_module;
}

function install_module_mod_user_image() {
    return true;
}

function upgrade_module_mod_user_image() {
    return true;
}