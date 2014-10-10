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

function info_module_mod_arhive() {
    $_module = array(
        'title'       => 'Архив новостей',
        'name'        => 'Архив новостей',
        'description' => 'Отображает на сайте aрхив новостей',
        'link'        => 'mod_arhive',
        'position'    => 'sidebar',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'cat_id' => 1,
            'tpl'    => 'mod_archive'
        )
    );

    return $_module;
}

function install_module_mod_arhive() {
    return true;
}

function upgrade_module_mod_arhive() {
    return true;
}