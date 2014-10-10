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

function info_module_mod_blogs() {
    $_module = array(
        'title'       => 'Записи в блогах',
        'name'        => 'Записи в блогах',
        'description' => 'Отображает на сайте записи в блогах',
        'link'        => 'mod_blogs',
        'position'    => 'maintop',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'sort'    => 'pubdate',
            'owner'   => 'user',
            'shownum' => 5,
            'minrate' => 0,
            'blog_id' => 0,
            'showrss' => 1,
            'tpl'     => 'mod_blogs'
        )
    );

    return $_module;
}

function install_module_mod_blogs() {
    return true;
}

function upgrade_module_mod_blogs() {
    return true;
}