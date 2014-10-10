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

function info_module_mod_latestboard() {
    $_module = array(
        'title'       => 'Новые объявления',
        'name'        => 'Новые объявления',
        'description' => 'Отображает на сайте новые объявления',
        'link'        => 'mod_latestboard',
        'position'    => 'maintop',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'shownum'  => 5,
            'showcity' => 1,
            'onlyvip'  => 0,
            'butvip'   => 0,
            'cat_id'   => 0,
            'sub'      => 1,
            'tpl'      => 'mod_latestboadr'
        )
    );

    return $_module;
}

function install_module_mod_latestboard() {
    return true;
}

function upgrade_module_mod_latestboard() {
    return true;
}