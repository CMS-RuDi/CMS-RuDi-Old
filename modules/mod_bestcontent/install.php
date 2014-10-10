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

function info_module_mod_bestcontent() {
    $_module = array(
        'title'       => 'Популярные статьи',
        'name'        => 'Популярные статьи',
        'description' => 'Отображает на сайте популярные статьи',
        'link'        => 'mod_bestcontent',
        'position'    => 'maintop',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'shownum' => 5,
            'subs'    => 1,
            'cat_id'  => 1,
            'tpl'     => 'mod_bestcontent'
        )
    );

    return $_module;
}

function install_module_mod_bestcontent() {
    return true;
}

function upgrade_module_mod_bestcontent() {
    return true;
}