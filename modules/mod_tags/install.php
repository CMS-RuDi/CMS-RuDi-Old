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

function info_module_mod_tags() {
    $_module = array(
        'title'       => 'Облако тегов',
        'name'        => 'Облако тегов',
        'description' => 'Отображает на сайте облако тегов',
        'link'        => 'mod_tags',
        'position'    => 'sidebar',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'minfreq'    => 0,
            'minlen'     => 0,
            'maxtags'    => 20,
            'colors'     => '',
            'shuffle'    => 0,
            'start_size' => 10,
            'step'       => 4,
            'end_size'   => 50,
            'targets'    => '',
            'sortby'     => 'tag',
            'tpl'        => 'mod_tags'
        )
    );

    return $_module;
}

function install_module_mod_tags() {
    return true;
}

function upgrade_module_mod_tags() {
    return true;
}