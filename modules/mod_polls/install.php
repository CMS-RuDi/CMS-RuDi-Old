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

function info_module_mod_polls() {
    $_module = array(
        'title'       => 'Голосования',
        'name'        => 'Голосования',
        'description' => 'Отображает на на сайте голосование',
        'link'        => 'mod_polls',
        'position'    => 'sidebar',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'shownum' => 0,
            'poll_id' => 0,
            'tpl'     => 'mod_polls'
        )
    );

    return $_module;
}

function install_module_mod_polls() {
    return true;
}

function upgrade_module_mod_polls() {
    return true;
}