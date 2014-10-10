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

function info_module_mod_uc_random() {
    $_module = array(
        'title'       => 'Случайное в каталоге',
        'name'        => 'Случайное в каталоге',
        'description' => 'Отображает на сайте случайные записи из каталоге',
        'link'        => 'mod_uc_random',
        'position'    => 'maintop',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'cat_id'    => 0,
            'subs'      => 1,
            'count'     => 5,
            'showtitle' => 1,
            'showcat'   => 1,
            'tpl'       => 'mod_uc_random'
        )
    );

    return $_module;
}

function install_module_mod_uc_random() {
    return true;
}

function upgrade_module_mod_uc_random() {
    return true;
}