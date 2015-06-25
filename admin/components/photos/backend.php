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

$cfg = $inCore->loadComponentConfig('photos');

cmsCore::loadClass('photo');
cmsCore::loadModel('photos');
$model = new cms_model_photos();

$opt = cmsCore::request('opt', 'str', 'list_albums');

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg = array(
        'link'                => cmsCore::request('show_link', 'int', 0),
        'saveorig'            => cmsCore::request('saveorig', 'int', 0),
        'maxcols'             => cmsCore::request('maxcols', 'int', 0),
        'orderby'             => cmsCore::request('orderby', 'str', ''),
        'orderto'             => cmsCore::request('orderto', 'str', ''),
        'showlat'             => cmsCore::request('showlat', 'int', 0),
        'watermark'           => cmsCore::request('watermark', 'int', 0),
        'meta_keys'           => cmsCore::request('meta_keys', 'str', ''),
        'meta_desc'           => cmsCore::request('meta_desc', 'str', ''),
        'seo_user_access'     => cmsCore::request('seo_user_access', 'int', 0),
        'best_latest_perpage' => cmsCore::request('best_latest_perpage', 'int', 0),
        'best_latest_maxcols' => cmsCore::request('best_latest_maxcols', 'int', 0)
    );

    $inCore->saveComponentConfig('photos', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

$toolmenu = array();

if ($opt == 'list_albums') {
    $toolmenu[] = array( 'icon' => 'newfolder.gif', 'title' => $_LANG['AD_ALBUM_ADD'], 'link' => '?view=components&do=config&id='. $id .'&opt=add_album' );
    $toolmenu[] = array( 'icon' => 'folders.gif', 'title' => $_LANG['AD_ALBUMS'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_albums' );
    $toolmenu[] = array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=components&do=config&id='. $id .'&opt=config' );
}

if (in_array($opt, array('config', 'add_album', 'edit_album'))) {
    $toolmenu[] = array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.addform.submit();' );
    $toolmenu[] = array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => '?view=components&do=config&id='. $id );
}

cpToolMenu($toolmenu);

if ($opt == 'config') {
    cpAddPathway($_LANG['AD_SETTINGS']);
    
    cpCheckWritable('/images/photos', 'folder');
    cpCheckWritable('/images/photos/medium', 'folder');
    cpCheckWritable('/images/photos/small', 'folder');
    
    cmsCore::c('page')->initTemplate('components', 'photos_config')->
        assign('cfg', $cfg)->
        display();
}

if ($opt == 'show_album') {
    $item_id = cmsCore::request('item_id', 'int', 0);
    cmsCore::c('db')->query("UPDATE cms_photo_albums SET published = 1 WHERE id = '". $item_id ."'") ;
    cmsCore::halt('1');
}

if ($opt == 'hide_album') {
    $item_id = cmsCore::request('item_id', 'int', 0);
    cmsCore::c('db')->query("UPDATE cms_photo_albums SET published = 0 WHERE id = '". $item_id ."'") ;
    cmsCore::halt('1');
}

if ($opt == 'submit_album') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
    
    $album = array(
        'title'       => cmsCore::request('title', 'str', 'NO_TITLE'),
        'description' => cmsCore::request('description', 'str'),
        'published'   => cmsCore::request('published', 'int'),
        'showdate'    => cmsCore::request('showdate', 'int'),
        'parent_id'   => cmsCore::request('parent_id', 'int'),
        'showtype'    => cmsCore::request('showtype', 'str'),
        'public'      => cmsCore::request('public', 'int'),
        'orderby'     => cmsCore::request('orderby', 'str'),
        'orderto'     => cmsCore::request('orderto', 'str'),
        'perpage'     => cmsCore::request('perpage', 'int'),
        'thumb1'      => cmsCore::request('thumb1', 'int'),
        'thumb2'      => cmsCore::request('thumb2', 'int'),
        'thumbsqr'    => cmsCore::request('thumbsqr', 'int'),
        'cssprefix'   => cmsCore::request('cssprefix', 'str'),
        'nav'         => cmsCore::request('nav', 'int'),
        'uplimit'     => cmsCore::request('uplimit', 'int'),
        'maxcols'     => cmsCore::request('maxcols', 'int'),
        'orderform'   => cmsCore::request('orderform', 'int'),
        'showtags'    => cmsCore::request('showtags', 'int'),
        'bbcode'      => cmsCore::request('bbcode', 'int'),
        'is_comments' => cmsCore::request('is_comments', 'int'),
        'meta_keys'   => cmsCore::request('meta_keys', 'str', ''),
        'meta_desc'   => cmsCore::request('meta_desc', 'str', ''),
        'pagetitle'   => cmsCore::request('pagetitle', 'str', '')
    );
    
    $album = cmsCore::callEvent('ADD_ALBUM', $album);
    
    cmsCore::c('db')->addNsCategory('cms_photo_albums', $album);
    
    cmsCore::addSessionMessage($_LANG['AD_ALBUM'].' "'.stripslashes($album['title']).'" '.$_LANG['AD_ALBUM_CREATED'], 'success');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_albums');
}

if ($opt == 'delete_album') {
    if (cmsCore::inRequest('item_id')) {
        $album = cmsCore::c('db')->getNsCategory('cms_photo_albums', cmsCore::request('item_id', 'int', 0));
        if (!$album) {
            cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_albums');
        }
        
        cmsCore::addSessionMessage($_LANG['AD_ALBUM'] .' "'. stripslashes($album['title']) .'", '. $_LANG['AD_EMBEDED_PHOTOS_REMOVED'] .'.', 'success');
        
        cmsPhoto::getInstance()->deleteAlbum($album['id'], '', $model->initUploadClass($album));
    }
    
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_albums');
}

if ($opt == 'update_album') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
    
    $item_id = cmsCore::request('item_id', 'int', 0);
    
    $old_album = $inDB->getNsCategory('cms_photo_albums', $item_id);
    if (!$old_album) { cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_albums'); }
    
    $album = array(
        'title'         => cmsCore::request('title', 'str', 'NO_TITLE'),
        'description'   => cmsCore::request('description', 'str', ''),
        'published'     => cmsCore::request('published', 'int'),
        'showdate'      => cmsCore::request('showdate', 'int'),
        'parent_id'     => cmsCore::request('parent_id', 'int'),
        'is_comments'   => cmsCore::request('is_comments', 'int'),
        'showtype'      => cmsCore::request('showtype', 'str'),
        'public'        => cmsCore::request('public', 'int'),
        'orderby'       => cmsCore::request('orderby', 'str'),
        'orderto'       => cmsCore::request('orderto', 'str'),
        'perpage'       => cmsCore::request('perpage', 'int'),
        'thumb1'        => cmsCore::request('thumb1', 'int'),
        'thumb2'        => cmsCore::request('thumb2', 'int'),
        'thumbsqr'      => cmsCore::request('thumbsqr', 'int'),
        'cssprefix'     => cmsCore::request('cssprefix', 'str'),
        'nav'           => cmsCore::request('nav', 'int'),
        'uplimit'       => cmsCore::request('uplimit', 'int'),
        'maxcols'       => cmsCore::request('maxcols', 'int'),
        'orderform'     => cmsCore::request('orderform', 'int'),
        'showtags'      => cmsCore::request('showtags', 'int'),
        'bbcode'        => cmsCore::request('bbcode', 'int'),
        'iconurl'       => cmsCore::request('iconurl', 'str'),
        'meta_keys'     => cmsCore::request('meta_keys', 'str', ''),
        'meta_desc'     => cmsCore::request('meta_desc', 'str', ''),
        'pagetitle'     => cmsCore::request('pagetitle', 'str', '')
    );

    // если сменили категорию
    if ($old_album['parent_id'] != $album['parent_id']) {
        // перемещаем ее в дереве
        $inCore->nestedSetsInit('cms_photo_albums')->MoveNode($item_id, $album['parent_id']);
    }
    
    cmsCore::c('db')->update('cms_photo_albums', $album, $item_id);
    cmsCore::addSessionMessage($_LANG['AD_ALBUM'] .' "'. stripslashes($album['title']) .'" '. $_LANG['AD_ALBUM_SAVED'] .'.', 'success');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_albums');
}

if ($opt == 'list_albums') {
    echo '<h3>'. $_LANG['AD_ALBUMS'] .'</h3>';
    
    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_album&item_id=%id%' ),
        array( 'title' => $_LANG['AD_ALBUM_COMMENTS'], 'field' => 'is_comments', 'width' => '100', 'prc' => 'cpYesNo' ),
        array( 'title' => $_LANG['AD_ADDING_USERS'], 'field' => 'public', 'width' => '100', 'prc' => 'cpYesNo' ),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width' => '60', 'do' => 'opt', 'do_suffix' => '_album' )
    );
    
    $actions = array(
        array( 'title' => $_LANG['AD_VIEW_ONLINE'], 'icon' => 'search.gif', 'link' => '/photos/%id%' ),
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_album&item_id=%id%' ),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_ALBUM_PHOTOS_DEL'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete_album&item_id=%id%' )
    );
    
    cpListTable('cms_photo_albums', $fields, $actions, 'parent_id>0 AND NSDiffer=""', 'NSLeft');
}

if ($opt == 'add_album' || $opt == 'edit_album') {
    if ($opt == 'add_album') {
        cpAddPathway($_LANG['AD_ALBUM_ADD']);
        $mod = array();
    } else {
        $item_id = cmsCore::request('item_id', 'int', 0);
        $mod = cmsCore::c('db')->getNsCategory('cms_photo_albums', $item_id);
        
        if (!$mod) { cmsCore::error404(); }

        cpAddPathway($_LANG['AD_ALBUM_EDIT']);
    }

    $mod = array_merge(
        array(
            'thumb1' => 96,
            'thumb2' => 450,
            'thumbsqr' => 1,
            'is_comments' => 0,
            'maxcols' => 4,
            'showtype' => 'lightbox',
            'perpage' => 20,
            'uplimit' => 20,
            'published' => 1,
            'orderby' => 'pubdate'
        ),
        $mod
    );
    
    $icon_exist = !empty($mod['iconurl']) && file_exists(PATH .'/images/photos/small/'. $mod['iconurl']);
    
    $tpl = cmsCore::c('page')->initTemplate()->
        assign('rootid', cmsCore::c('db')->get_field('cms_photo_albums', "parent_id=0 AND NSDiffer=''", 'id'))->
        assign('photo_albums_opt', $inCore->getListItemsNS('cms_photo_albums', cmsCore::getArrVal($mod, 'parent_id', 0)))->
        assign('icon_exist', $icon_exist)->
        assign('opt', $opt)->
        assign('mod', $mod);
    
    if (cmsCore::c('db')->rows_count('cms_photo_files', 'album_id = '. $item_id .'')) {
        $tpl->assign('photo_files_opt', $inCore->getListItems('cms_photo_files', $icon_exist ? $mod['iconurl'] : '', 'id', 'ASC', 'album_id = '. $item_id .' AND published = 1', 'file'));
    }
    
    $tpl->display();
}