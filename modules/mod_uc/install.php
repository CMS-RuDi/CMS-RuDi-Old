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

function info_module_mod_uc() {
    $_module = array(
        'title'       => 'Записи в каталоге',
        'name'        => 'Записи в каталоге',
        'description' => 'Отображает на сайте записи из каталоге',
        'link'        => 'mod_uc',
        'position'    => 'maintop',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'num'      => 5,
            'sort'     => 'rating',
            'showtype' => 'thumb',
            'showf'    => 5,
            'cat_id'   => 1,
            'subs'     => 1,
            'fulllink' => 1,
            'tpl'      => 'mod_uc'
        )
    );

    return $_module;
}

function install_module_mod_uc() {
    return true;
}

function upgrade_module_mod_uc() {
    return true;
}