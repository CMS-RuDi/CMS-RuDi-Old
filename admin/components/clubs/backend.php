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

$cfg = $inCore->loadComponentConfig('clubs');

$opt = cmsCore::request('opt', 'str', 'list');

cmsCore::loadModel('clubs');
$model = new cms_model_clubs();

if ($opt == 'list') {
    $toolmenu = array(
        array( 'icon' => 'new.gif', 'title' => $_LANG['CREATE_CLUB'], 'link' => '?view=components&do=config&id='. $id .'&opt=add' ),
        array( 'icon' => 'show.gif', 'title' => $_LANG['AD_ALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=show_club&multiple=1');" ),
        array( 'icon' => 'hide.gif', 'title' => $_LANG['AD_DISALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=hide_club&multiple=1');" ),
        array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=edit&multiple=1');" )
    );
}

if (in_array($opt, array('add', 'edit', 'config'))) {
    $toolmenu[] = array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.addform.submit();' );
    $toolmenu[] = array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => '?view=components&do=config&id='. $id );
}

$toolmenu[] = array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=components&do=config&id='. $id .'&opt=config' );

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg = array(
        'enabled_blogs'      => cmsCore::request('enabled_blogs', 'str'),
        'enabled_photos'     => cmsCore::request('enabled_photos', 'str'),
        'thumb1'             => cmsCore::request('thumb1', 'int'),
        'thumb2'             => cmsCore::request('thumb2', 'int'),
        'thumbsqr'           => cmsCore::request('thumbsqr', 'int'),
        'cancreate'          => cmsCore::request('cancreate', 'int'),
        'perpage'            => cmsCore::request('perpage', 'int'),
        'member_perpage'     => cmsCore::request('member_perpage', 'int'),
        'club_perpage'       => cmsCore::request('club_perpage', 'int'),
        'wall_perpage'       => cmsCore::request('wall_perpage', 'int'),
        'club_album_perpage' => cmsCore::request('club_album_perpage', 'int'),
        'posts_perpage'      => cmsCore::request('posts_perpage', 'int'),
        'club_posts_perpage' => cmsCore::request('club_posts_perpage', 'int'),
        'photo_perpage'      => cmsCore::request('photo_perpage', 'int'),
        'create_min_karma'   => cmsCore::request('create_min_karma', 'int'),
        'create_min_rating'  => cmsCore::request('create_min_rating', 'int'),
        'notify_in'          => cmsCore::request('notify_in', 'int'),
        'notify_out'         => cmsCore::request('notify_out', 'int'),
        'every_karma'        => cmsCore::request('every_karma', 'int', 100),
        'photo_watermark'    => cmsCore::request('photo_watermark', 'int', 0),
        'photo_thumb_small'  => cmsCore::request('photo_thumb_small', 'int', 96),
        'photo_thumbsqr'     => cmsCore::request('photo_thumbsqr', 'int', 0),
        'photo_thumb_medium' => cmsCore::request('photo_thumb_medium', 'int', 450),
        'photo_maxcols'      => cmsCore::request('photo_maxcols', 'int', 4),
        'meta_keys'          => cmsCore::request('meta_keys', 'str', ''),
        'meta_desc'          => cmsCore::request('meta_desc', 'str', ''),
        'seo_user_access'    => cmsCore::request('seo_user_access', 'int', 0),
        'is_saveorig'        => cmsCore::request('is_saveorig', 'int', 0)
    );

    $inCore->saveComponentConfig('clubs', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

if ($opt == 'show_club') {
    if (!cmsCore::inRequest('item')) {
        if (cmsCore::inRequest('item_id')) {
            cmsCore::c('db')->setFlag('cms_clubs', cmsCore::request('item_id', 'int', 0), 'published', '1');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_clubs', cmsCore::request('item', 'array_int'), 'published', '1');
        cmsCore::redirectBack();
    }
}

if ($opt == 'hide_club') {
    if (!cmsCore::inRequest('item')) {
        if (cmsCore::inRequest('item_id')) {
            cmsCore::c('db')->setFlag('cms_clubs', cmsCore::request('item_id', 'int', 0), 'published', '0');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_clubs', cmsCore::request('item', 'array_int'), 'published', '0');
        cmsCore::redirectBack();
    }
}

if ($opt == 'submit') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $title          = cmsCore::request('title', 'str', 'NO_TITLE');
    $description    = cmsCore::c('db')->escape_string(cmsCore::request('description', 'html', ''));
    $published      = cmsCore::request('published', 'int');
    $admin_id       = cmsCore::request('admin_id', 'int');
    $clubtype       = cmsCore::request('clubtype', 'str');
    $maxsize        = cmsCore::request('maxsize', 'int');
    $enabled_blogs  = cmsCore::request('enabled_blogs', 'int');
    $enabled_photos = cmsCore::request('enabled_photos', 'int');

    $date = explode('.', cmsCore::request('pubdate', 'str'));
    $pubdate = (int)$date[2] .'-'. (int)$date[1] .'-'. (int)$date[0];

    $new_imageurl = $model->uploadClubImage();
    $filename = !empty($new_imageurl['filename']) ? $new_imageurl['filename'] : '';

    $model->addClub(array(
        'admin_id' => $admin_id,
        'title' => $title,
        'description' => $description,
        'imageurl' => $filename,
        'pubdate' => $pubdate,
        'clubtype' => $clubtype,
        'published' => $published,
        'maxsize' => $maxsize,
        'create_karma' => cmsUser::getKarma($admin_id),
        'enabled_blogs' => $enabled_blogs,
        'enabled_photos' => $enabled_photos
    ));

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('index.php?view=components&do=config&opt=list&id='.$id);
}

if ($opt == 'update') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $item_id = cmsCore::request('item_id', 'int', 0);

    $new_club['title']          = cmsCore::request('title', 'str', 'NO_TITLE');
    $new_club['description']    = cmsCore::c('db')->escape_string(cmsCore::request('description', 'html', ''));
    $new_club['published']      = cmsCore::request('published', 'int');
    $new_club['admin_id']       = cmsCore::request('admin_id', 'int');
    $new_club['clubtype']       = cmsCore::request('clubtype', 'str');
    $new_club['maxsize']        = cmsCore::request('maxsize', 'int');
    $new_club['enabled_blogs']	= cmsCore::request('enabled_blogs', 'int');
    $new_club['enabled_photos']	= cmsCore::request('enabled_photos', 'int');

    $olddate = cmsCore::request('olddate', 'str');
    $pubdate = cmsCore::request('pubdate', 'str');

    $club = $model->getClub($item_id);
    if (!$club) { cmsCore::error404(); }

    if ($olddate != $pubdate) {
        $date = explode('.', $pubdate);
        $new_club['pubdate'] = (int)$date[2] .'-'. (int)$date[1] .'-'. (int)$date[0];
    }

    $new_imageurl = $model->uploadClubImage($club['imageurl']);
    $new_club['imageurl'] = !empty($new_imageurl['filename']) ? $new_imageurl['filename'] : $club['imageurl'];

    $model->updateClub($item_id, $new_club);

    cmsCore::addSessionMessage($_LANG['CONFIG_SAVE_OK'], 'success');

    if (empty($_SESSION['editlist'])) {
        cmsCore::redirect('index.php?view=components&do=config&id='. $id .'&opt=list');
    } else {
        cmsCore::redirect('index.php?view=components&do=config&id='. $id .'&opt=edit');
    }
}

if ($opt == 'delete') {
    $model->deleteClub(cmsCore::request('item_id', 'int', 0));
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('index.php?view=components&do=config&id='. $id .'&opt=list');
}

cpToolMenu($toolmenu);

if ($opt == 'list') {
    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40'),
        array( 'title' => $_LANG['DATE'], 'field' => 'pubdate', 'width' => '100', 'filter' => '15', 'fdate' => '%d/%m/%Y'),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'filter' => '15', 'link' => '?view=components&do=config&id='. $id .'&opt=edit&item_id=%id%'),
        array( 'title' => $_LANG['CLUB_TYPE'], 'field' => 'clubtype', 'width' => '100'),
        array( 'title' => $_LANG['MEMBERS'], 'field' => 'members_count', 'width' => '100'),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width' => '100', 'do' => 'opt', 'do_suffix' => '_club')
    );
    
    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit&item_id=%id%'),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_DELETE_CLUB'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete&item_id=%id%')
    );

    cpListTable('cms_clubs', $fields, $actions, '', 'pubdate DESC');
}

if ($opt == 'add' || $opt == 'edit') {
    if ($opt == 'add') {
        cpAddPathway($_LANG['CREATE_CLUB'] );
        $mod = array();
    } else {
        if (cmsCore::inRequest('multiple')) {
            if (cmsCore::inRequest('item')) {
                $_SESSION['editlist'] = cmsCore::request('item', 'array_int', array());
            } else {
                cmsCore::addSessionMessage($_LANG['AD_NO_SELECT_OBJECTS'], 'error');
                cmsCore::redirectBack();
            }
        }

        $ostatok = '';

        if (isset($_SESSION['editlist'])) {
            $item_id = array_shift($_SESSION['editlist']);
            if (sizeof($_SESSION['editlist']) == 0) {
               unset($_SESSION['editlist']);
            } else {
                $ostatok = '('. $_LANG['AD_NEXT_IN'] . sizeof($_SESSION['editlist']) .')';
            }
        } else {
            $item_id = cmsCore::request('item_id', 'int', 0);
        }

        $mod = $model->getClub($item_id);
        if (!$mod) { cmsCore::error404(); }

        cpAddPathway($mod['title']);
    }

    if (!isset($mod['maxsize'])) { $mod['maxsize'] = 0; }
    if (!isset($mod['admin_id'])) { $mod['admin_id'] = cmsCore::c('user')->id; }
    if (!isset($mod['clubtype'])) { $mod['clubtype'] = 'public'; }
    
    cmsCore::c('page')->initTemplate('components', 'clubs_add')->
        assign('opt', $opt)->
        assign('users_opt', $inCore->getListItems('cms_users', cmsCore::getArrVal($mod, 'admin_id', 0), 'nickname', 'ASC', 'is_deleted=0 AND is_locked=0', 'id', 'nickname'))->
        assign('mod', $mod)->
        display();
}

if ($opt == 'config') {
    cpAddPathway($_LANG['AD_SETTINGS']);
    
    cmsCore::c('page')->initTemplate('components', 'clubs_config')->
        assign('cfg', $cfg)->
        display();
}