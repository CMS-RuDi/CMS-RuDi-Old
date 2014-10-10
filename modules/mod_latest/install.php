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

function info_module_mod_latest() {
    $_module = array(
        'title'       => 'Новые статьи',
        'name'        => 'Новые статьи',
        'description' => 'Отображает на сайте новые статьи',
        'link'        => 'mod_latest',
        'position'    => 'maintop',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'showrss'  => 1,
            'subs'     => 1,
            'cat_id'   => 1,
            'newscount'=> 5,
            'is_pag'   => 0,
            'page'     => 1,
            'showdesc' => 1,
            'showdate' => 1,
            'showcom'  => 1,
            'tpl'      => 'mod_latest'
        )
    );

    return $_module;
}

function install_module_mod_latest() {
    return true;
}

function upgrade_module_mod_latest() {
    return true;
}