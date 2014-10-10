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

function info_module_mod_random_image() {
    $_module = array(
        'title'       => 'Случайная картинка',
        'name'        => 'Случайная картинка',
        'description' => 'Отображает на сайте случайную картинку',
        'link'        => 'mod_random_image',
        'position'    => 'sidebar',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'album_id'  => 0,
            'showtitle' => 1,
            'subs'      => 0,
            'tpl'       => 'mod_random_image'
        )
    );

    return $_module;
}

function install_module_mod_random_image() {
    return true;
}

function upgrade_module_mod_random_image() {
    return true;
}