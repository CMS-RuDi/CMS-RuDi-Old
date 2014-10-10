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

function info_module_mod_template() {
    $_module = array(
        'title'       => 'Выбор шаблона',
        'name'        => 'Выбор шаблона',
        'description' => 'Отображает на сайте выбор шаблона',
        'link'        => 'mod_templates',
        'position'    => 'sidebar',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'tpl' => 'mod_template'
        )
    );

    return $_module;
}

function install_module_mod_template() {
    return true;
}

function upgrade_module_mod_template() {
    return true;
}