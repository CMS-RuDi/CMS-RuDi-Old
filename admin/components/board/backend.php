<?php
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
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

function uploadCategoryIcon($file = '') {
    // Загружаем класс загрузки фото
    cmsCore::loadClass('upload_photo');
    $inUploadPhoto = cmsUploadPhoto::getInstance();
    
    // Выставляем конфигурационные параметры
    $inUploadPhoto->upload_dir    = PATH .'/upload/board/';
    $inUploadPhoto->dir_medium    = 'cat_icons/';
    $inUploadPhoto->medium_size_w = 32;
    $inUploadPhoto->medium_size_h = 32;
    $inUploadPhoto->only_medium   = true;
    $inUploadPhoto->is_watermark  = false;
    // Процесс загрузки фото
    $files = $inUploadPhoto->uploadPhoto($file);

    $icon = $files['filename'] ? $files['filename'] : $file;

    return $icon;
}

$cfg = $inCore->loadComponentConfig('board');
cmsCore::loadModel('board');
$model = new cms_model_board();

define('IS_BILLING', $inCore->isComponentInstalled('billing'));
if (IS_BILLING) { cmsCore::loadClass('billing'); }

$opt = cmsCore::request('opt', 'str', 'list_items');

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg = array(
        'maxcols'            => cmsCore::request('maxcols', 'int', 0),
        'obtypes'            => cmsCore::request('obtypes', 'html', ''),
        'showlat'            => cmsCore::request('showlat', 'str', ''),
        'public'             => cmsCore::request('public', 'int', 0),
        'photos'             => cmsCore::request('photos', 'int', 0),
        'srok'               => cmsCore::request('srok', 'int', 0),
        'pubdays'            => cmsCore::request('pubdays', 'int', 0),
        'watermark'          => cmsCore::request('watermark', 'int', 0),
        'aftertime'          => cmsCore::request('aftertime', 'str', ''),
        'comments'           => cmsCore::request('comments', 'int', 0),
        'extend'             => cmsCore::request('extend', 'int', 0),
        'auto_link'          => cmsCore::request('auto_link', 'int', 0),
        'vip_enabled'        => cmsCore::request('vip_enabled', 'int', 0),
        'vip_prolong'        => cmsCore::request('vip_prolong', 'int', 0),
        'vip_max_days'       => cmsCore::request('vip_max_days', 'int', 30),
        'vip_day_cost'       => cmsCore::request('vip_day_cost', 'str', 5),
        'home_perpage'       => cmsCore::request('home_perpage', 'int', 15),
        'maxcols_on_home'    => cmsCore::request('maxcols_on_home', 'int', 1),
        'publish_after_edit' => cmsCore::request('publish_after_edit', 'int', 0),
        'root_description'   => cmsCore::request('root_description', 'html', ''),
        'meta_keys'          => cmsCore::request('meta_keys', 'str', ''),
        'meta_desc'          => cmsCore::request('meta_desc', 'str', ''),
        'seo_user_access'    => cmsCore::request('seo_user_access', 'int', 0)
    );

    $cfg['vip_day_cost'] = str_replace(',', '.', trim($cfg['vip_day_cost']));

    $inCore->saveComponentConfig('board', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

$toolmenu = array(
    array( 'icon' => 'newstuff.gif', 'title' => $_LANG['ADD_ADV'], 'link' => '/board/add.html' ),
    array( 'icon' => 'newfolder.gif', 'title' => $_LANG['AD_NEW_CAT'], 'link' => '?view=components&do=config&id='. $id .'&opt=add_cat' ),
    array( 'icon' => 'liststuff.gif', 'title' => $_LANG['AD_ALL_AD'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_items' ),
    array( 'icon' => 'folders.gif', 'title' => $_LANG['AD_ALL_CAT'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_cats' )
);

if ($opt == 'list_items') {
    $toolmenu[] = array( 'icon' => 'show.gif', 'title' => $_LANG['AD_ALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=show_item&multiple=1');" );
    $toolmenu[] = array( 'icon' => 'hide.gif', 'title' => $_LANG['AD_DISALLOW_SELECTED'], 'link'=>"javascript:checkSel('?view=components&do=config&id=". $id ."&opt=hide_item&multiple=1');" );
}

$toolmenu[] = array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=components&do=config&id='. $id .'&opt=config');

cpToolMenu($toolmenu);

if ($opt == 'show_item') {
    if (!cmsCore::inRequest('item')){
        if (cmsCore::inRequest('item_id')){
            cmsCore::c('db')->setFlag('cms_board_items', cmsCore::request('item_id', 'int', 0), 'published', '1');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_board_items', cmsCore::request('item', 'array_int', 0), 'published', '1');
        cmsCore::redirectBack();
    }
}

if ($opt == 'hide_item'){
    if (!cmsCore::inRequest('item')){
        if (cmsCore::inRequest('item_id')){
            cmsCore::c('db')->setFlag('cms_board_items', cmsCore::request('item_id', 'int', 0), 'published', '0');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_board_items', cmsCore::request('item', 'array_int', 0), 'published', '0');
        cmsCore::redirectBack();
    }
}

if ($opt == 'delete_item') {
    $model->deleteRecord(cmsCore::request('item_id', 'int', 0));
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

if ($opt == 'config') {
    cpAddPathway($_LANG['AD_SETTINGS']);

    cpCheckWritable('/images/board', 'folder');
    cpCheckWritable('/images/board/medium', 'folder');
    cpCheckWritable('/images/board/small', 'folder');
    
    cmsCore::c('page')->initTemplate('components', 'board_config')->
        assign('is_billing', IS_BILLING)->
        assign('cfg', $cfg)->
        display();
}

if ($opt == 'show_cat') {
    cmsCore::c('db')->setFlag('cms_board_cats', cmsCore::request('item_id', 'int', 0), 'published', 1);
    cmsCore::halt('1');
}

if ($opt == 'hide_cat') {
    cmsCore::c('db')->setFlag('cms_board_cats', cmsCore::request('item_id', 'int', 0), 'published', 0);
    cmsCore::halt('1');
}

if ($opt == 'submit_cat' || $opt == 'update_cat') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $types = array(
        'title'       => array('title', 'str', $_LANG['AD_UNTITLED_CAT']),
        'description' => array('description', 'str', ''),
        'published'   => array('published', 'int', 0),
        'showdate'    => array('showdate', 'int', 0),
        'parent_id'   => array('parent_id', 'int', 0),
        'public'      => array('public', 'int', 0),
        'orderby'     => array('orderby', 'str', 'pubdate'),
        'orderto'     => array('orderto', 'str', 'desc'),
        'perpage'     => array('perpage', 'int', 10),
        'is_photos'   => array('is_photos', 'int', 0),
        'thumb1'      => array('thumb1', 'int', 0),
        'thumb2'      => array('thumb2', 'int', 0),
        'thumbsqr'    => array('thumbsqr', 'int', 0),
        'uplimit'     => array('uplimit', 'int', 0),
        'maxcols'     => array('maxcols', 'int', 0),
        'orderform'   => array('orderform', 'int', 0),
        'form_id'     => array('form_id', 'int', 0),
        'obtypes'     => array('obtypes', 'str', ''),
        'pagetitle'   => array('pagetitle', 'str', ''),
        'meta_keys'   => array('meta_keys', 'str', ''),
        'meta_desc'   => array('meta_desc', 'str', '')
    );

    $item = cmsCore::getArrayFromRequest($types);

    if ($opt == 'submit_cat') {
        $item['icon'] = uploadCategoryIcon();
        $item['pubdate'] = date("Y-m-d H:i:s");

        cmsCore::c('db')->addNsCategory('cms_board_cats', $item);
    } else {
        $item_id = cmsCore::request('item_id', 'int', 0);
        $mod = cmsCore::c('db')->get_fields('cms_board_cats', "id = '". $item_id ."'", '*');
        
        if (!$mod) { cmsCore::error404(); }
        
        $mod['icon'] = ($mod['icon'] == 'folder_grey.png') ? '' : $mod['icon'];
        $icon = uploadCategoryIcon($mod['icon']);
        $item['icon'] = $icon ? $icon : $mod['icon'];

        if ($item['parent_id'] != $mod['parent_id']) {
            cmsCore::nestedSetsInit('cms_board_cats')->MoveNode($item_id, $item['parent_id']);
        }

        cmsCore::c('db')->update('cms_board_cats', $item, $item_id);
    }

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_cats');
}

if ($opt == 'delete_cat') {
    $item_id = cmsCore::request('item_id', 'int', 0);

    $sql = "SELECT id FROM cms_board_items WHERE category_id = '". $item_id ."'";
    $result = cmsCore::c('db')->query($sql);
    if (cmsCore::c('db')->num_rows($result)) {
        while ($photo = cmsCore::c('db')->fetch_assoc($result)) {
            $model->deleteRecord($photo['id']);
        }
    }
    $f_icon = cmsCore::c('db')->get_field('cms_board_cats', "id = '". $item_id ."'", 'icon');
    cmsCore::c('db')->deleteNS('cms_board_cats', $item_id);
    if (file_exists(PATH.'/upload/board/cat_icons/'. $f_icon)) {
        @chmod(PATH.'/upload/board/cat_icons/'. $f_icon, 0777);
        @unlink(PATH.'/upload/board/cat_icons/'. $f_icon);
    }

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_cats');
}

if ($opt == 'list_cats') {
    cpAddPathway($_LANG['AD_ALL_CAT']);

    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_cat&item_id=%id%'),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width' => '100', 'do' => 'opt', 'do_suffix' => '_cat')
    );
    
    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_cat&item_id=%id%'),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_IF_CAT_DELETE'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete_cat&item_id=%id%')
    );

    cpListTable('cms_board_cats', $fields, $actions, 'parent_id>0', 'NSLeft');
}

if ($opt == 'list_items') {
    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['DATE'], 'field' => 'pubdate', 'width' => '80', 'filter' => '15', 'fdate' => '%d/%m/%Y' ),
        array( 'title' => $_LANG['TYPE'], 'field' => 'obtype', 'width' => '80', 'filter' => '15' ),
        array( 'title' => $_LANG['AD_TITLE'], 'field' => 'title', 'width' => '', 'filter' => '15', 'link' => '/board/edit%id%.html' ),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width'=> '60', 'do' => 'opt', 'do_suffix' => '_item' ),
        array( 'title' => $_LANG['AD_VIEWS'], 'field' => 'hits', 'width' => '100' ),
        array( 'title' => 'IP', 'field'=>'ip', 'width' => '80', 'prc' => 'long2ip' ),
        array( 'title' => $_LANG['CAT_BOARD'], 'field' => 'category_id', 'width' => '230', 'prc' => 'cpBoardCatById', 'filter' => '1', 'filterlist' => cpGetList('cms_board_cats') )
    );
    
    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '/board/edit%id%.html'),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['DELETE_ADV'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete_item&item_id=%id%')
    );

    cpListTable('cms_board_items', $fields, $actions, '', 'pubdate DESC');
}

if ($opt == 'add_cat' || $opt == 'edit_cat') {
    cpAddPathway($_LANG['AD_ALL_CAT'], '?view=components&do=config&id='. $id .'&opt=list_cats');
    if ($opt=='add_cat') {
        cpAddPathway($_LANG['AD_NEW_CAT']);
    } else {
        $item_id = cmsCore::request('item_id', 'int', 0);

        $mod = cmsCore::c('db')->get_fields('cms_board_cats', "id = '". $item_id ."'", '*');
        if (!$mod){ cmsCore::error404(); }

        echo '<h3>'. $_LANG['AD_CAT_EDIT'] .'</h3>';
        cpAddPathway($_LANG['AD_CAT_EDIT'].' "'.$mod['title'].'"');
    }

    //DEFAULT VALUES
    if (!isset($mod['thumb1'])) { $mod['thumb1'] = 64; }
    if (!isset($mod['thumb2'])) { $mod['thumb2'] = 400; }
    if (!isset($mod['thumbsqr'])) { $mod['thumbsqr'] = 0; }
    if (!isset($mod['maxcols'])) { $mod['maxcols'] = 1; }
    if (!isset($mod['perpage'])) { $mod['perpage'] = '20'; }
    if (!isset($mod['uplimit'])) { $mod['uplimit'] = 10; }
    if (!isset($mod['public'])) { $mod['public'] = -1; }
    if (!isset($mod['published'])) { $mod['published'] = 1; }
    if (!isset($mod['showdate'])) { $mod['showdate'] = 1; }
    if (!isset($mod['orderform'])) { $mod['orderform'] = 1; }
    if (!isset($mod['orderby'])) { $mod['orderby'] = 'pubdate'; }
    if (!isset($mod['orderto'])) { $mod['orderto'] = 'desc'; }
    
    $rs = cmsCore::c('db')->query('SELECT id, title FROM cms_forms');
    $forms = array();

    if (cmsCore::c('db')->num_rows($rs)) {
        while ($f = cmsCore::c('db')->fetch_assoc($rs)) {
            $forms[] = $f;
        }
    }
    
    cmsCore::c('page')->initTemplate('components', 'board_add_cat')->
        assign('opt', $opt)->
        assign('rootid', cmsCore::c('db')->get_field('cms_board_cats', 'parent_id=0', 'id'))->
        assign('board_cats_opt', $inCore->getListItemsNS('cms_board_cats', cmsCore::getArrVal($mod, 'parent_id', 0)))->
        assign('forms', $forms)->
        assign('mod', $mod)->
        display();
}