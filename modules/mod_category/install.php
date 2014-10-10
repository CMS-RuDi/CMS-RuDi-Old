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

function info_module_mod_category() {
    $_module = array(
        'title'       => 'Разделы статей',
        'name'        => 'Разделы статей',
        'description' => 'Отображает на сайте pазделы статей',
        'link'        => 'mod_category',
        'position'    => 'sidebar',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'category_id'  => 1,
            'show_subcats' => 1,
            'expand_all'   => 1,
            'tpl'          => 'mod_category.tpl'
        )
    );

    return $_module;
}

function install_module_mod_category() {
    return true;
}

function upgrade_module_mod_category() {
    return true;
}