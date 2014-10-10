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

function info_module_mod_comments() {
    $_module = array(
        'title'       => 'Последние комментарии',
        'name'        => 'Последние комментарии',
        'description' => 'Отображает на сайте последние комментарии',
        'link'        => 'mod_comments',
        'position'    => 'sidebar',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'shownum'  => 15,
            'showrss'  => 1,
            'minrate'  => 0,
            'showguest'=> 0,
            'showtarg' => 1,
            'targets'  => array(),
            'tpl'      => 'mod_comments'
        )
    );

    return $_module;
}

function install_module_mod_comments() {
    return true;
}

function upgrade_module_mod_comments() {
    return true;
}