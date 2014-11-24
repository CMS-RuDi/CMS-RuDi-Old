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

function info_module_mod_photo() {
    $_module = array(
        'title'       => 'Фотографии',
        'name'        => 'Фотографии',
        'description' => 'Отображает на сайте фотографии',
        'link'        => 'mod_photo',
        'position'    => 'sidebar',
        'author'      => 'InstantCMS team',
        'version'     => '1.10.5',
        'config'      => array(
            'is_full'     => 1,
            'showmore'    => 1,
            'album_id'    => 0,
            'whatphoto'   => 'all',
            'shownum'     => 5,
            'maxcols'     => 2,
            'sort'        => 'pubdate',
            'showclubs'   => 0,
            'is_subs'     => 1,
            'tpl'         => 'mod_photo',
            'is_lightbox' => 1
        )
    );

    return $_module;
}

function install_module_mod_photo() {
    return true;
}

function upgrade_module_mod_photo() {
    return true;
}