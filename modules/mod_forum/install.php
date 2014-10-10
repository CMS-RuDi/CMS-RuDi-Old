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

function info_module_mod_forum() {
    $_module = array(
        'title'       => 'Новости форума',
        'name'        => 'Новости форума',
        'description' => 'Отображает на сайте последние сообщения форума',
        'link'        => 'mod_forum',
        'position'    => 'maintop',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'shownum'     => 4,
            'cat_id'      => 0,
            'forum_id'    => 0,
            'subs'        => 0,
            'show_hidden' => 0,
            'show_pinned' => 0,
            'showtext'    => 1,
            'showforum'   => 0,
            'order'       => 'pubdate',
            'tpl'         => 'mod_forum'
        )
    );

    return $_module;
}

function install_module_mod_forum() {
    return true;
}

function upgrade_module_mod_forum() {
    return true;
}