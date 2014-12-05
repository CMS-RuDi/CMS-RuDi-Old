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

if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function applet_filters() {
    global $_LANG;
    
    global $adminAccess;
    
    if (!cmsUser::isAdminCan('admin/plugins', $adminAccess)) { cpAccessDenied(); }
    if (!cmsUser::isAdminCan('admin/filters', $adminAccess)) { cpAccessDenied(); }
    
    cmsCore::c('page')->setAdminTitle($_LANG['AD_FILTERS']);
    cpAddPathway($_LANG['AD_FILTERS'], 'index.php?view=filters');

    $do = cmsCore::request('do', 'str', 'list');
    $id = cmsCore::request('id', 'int', -1);

    if ($do == 'hide') {
        cmsCore::c('db')->setFlag('cms_filters', $id, 'published', '0');
        cmsCore::halt('1');
    }

    if ($do == 'show') {
        cmsCore::c('db')->setFlag('cms_filters', $id, 'published', '1');
        cmsCore::halt('1');
    }

    if ($do == 'list') {
        $fields = array(
            array( 'title' =>  'id', 'field' => 'id', 'width' => '40' ),
            array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '250' ),
            array( 'title' => $_LANG['DESCRIPTION'], 'field' => 'description', 'width' => '' ),
            array( 'title' => $_LANG['AD_ENABLE'], 'field' => 'published', 'width' => '100' )
        );

        cpListTable('cms_filters', $fields, array());
    }
}