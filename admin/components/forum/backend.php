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
function uploadCategoryIcon($file='') {
    cmsCore::loadClass('upload_photo');
    $inUploadPhoto = cmsUploadPhoto::getInstance();
    $inUploadPhoto->upload_dir    = PATH .'/upload/forum/';
    $inUploadPhoto->dir_medium    = 'cat_icons/';
    $inUploadPhoto->medium_size_w = 32;
    $inUploadPhoto->medium_size_h = 32;
    $inUploadPhoto->only_medium   = true;
    $inUploadPhoto->is_watermark  = false;
    $files = $inUploadPhoto->uploadPhoto($file);
    $icon = $files['filename'] ? $files['filename'] : $file;
    return $icon;
}

define('IS_BILLING', $inCore->isComponentInstalled('billing'));
if (IS_BILLING) { cmsCore::loadClass('billing'); }

$opt = cmsCore::request('opt', 'str', 'list_forums');

cmsCore::loadModel('forum');
$model = new cms_model_forum();

$cfg = $model->config;

if ($opt == 'list_forums' || $opt == 'list_cats' || $opt == 'config') {
    $toolmenu = array(
        array( 'icon' => 'newfolder.gif', 'title' => $_LANG['AD_CREATE_CATEGORY'], 'link' => '?view=components&do=config&id='. $id .'&opt=add_cat' ),
        array( 'icon' => 'newforum.gif', 'title' => $_LANG['AD_FORUM_NEW'], 'link' => '?view=components&do=config&id='. $id .'&opt=add_forum' ),
        array( 'icon' => 'folders.gif', 'title' => $_LANG['AD_FORUMS_CATS'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_cats' ),
        array( 'icon' => 'listforums.gif', 'title' => $_LANG['AD_FORUMS'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_forums' ),
        array( 'icon' => 'ranks.gif', 'title' => $_LANG['AD_RANKS_FORUM'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_ranks' )
    );

    if ($opt == 'list_forums') {
        $toolmenu[] = array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=edit_forum&multiple=1');" );
        $toolmenu[] = array( 'icon' => 'show.gif', 'title' => $_LANG['AD_ALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=show_forum&multiple=1');" );
        $toolmenu[] = array( 'icon' => 'hide.gif', 'title' => $_LANG['AD_DISALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=hide_forum&multiple=1');" );
    }
    
    $toolmenu[] = array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=components&do=config&id='. $id .'&opt=config' );
} else {
    $toolmenu = array(
        array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.addform.submit();' ),
        array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => '?view=components&do=config&id='. $id )
    );
}

cpToolMenu($toolmenu);

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg['is_rss']     = cmsCore::request('is_rss', 'int', 1);
    $cfg['pp_thread']  = cmsCore::request('pp_thread', 'int', 15);
    $cfg['pp_forum']   = cmsCore::request('pp_forum', 'int', 15);
    $cfg['showimg']    = cmsCore::request('showimg', 'int', 1);
    $cfg['img_on']     = cmsCore::request('img_on', 'int', 1);
    $cfg['img_max']    = cmsCore::request('img_max', 'int', 1);
    $cfg['fast_on']    = cmsCore::request('fast_on', 'int', 1);
    $cfg['fast_bb']    = cmsCore::request('fast_bb', 'int', 1);
    $cfg['fa_on']      = cmsCore::request('fa_on', 'int');
    $cfg['fa_max']     = cmsCore::request('fa_max', 'int');
    $cfg['fa_ext']     = cmsCore::request('fa_ext', 'str');

    while (mb_strpos($cfg['fa_ext'], 'htm') ||
           mb_strpos($cfg['fa_ext'], 'php') ||
           mb_strpos($cfg['fa_ext'], 'ht')) {
        $cfg['fa_ext'] = str_replace(array('htm','php','ht'), '', mb_strtolower($cfg['fa_ext']));
    }
    $cfg['fa_size']       = cmsCore::request('fa_size', 'int');
    $cfg['edit_minutes']  = cmsCore::request('edit_minutes', 'int');
    $cfg['watermark']     = cmsCore::request('watermark', 'int');
    $cfg['min_karma_add'] = cmsCore::request('min_karma_add', 'int', 0);
    
    $cfg['meta_keys'] = cmsCore::request('meta_keys', 'str', '');
    $cfg['meta_desc'] = cmsCore::request('meta_desc', 'str', '');

    $is_access = cmsCore::request('is_access', 'int', '');
    if (!$is_access) {
        $cfg['group_access'] = cmsCore::request('allow_group', 'array_int', '');
    } else {
        $cfg['group_access'] = '';
    }

    $inCore->saveComponentConfig('forum', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'info');
    cmsCore::redirectBack();
}

if ($opt == 'saveranks') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $ranks = cmsCore::request('rank', 'array_str', array());
    $cfg['modrank'] = cmsCore::request('modrank', 'int');
    
    foreach ($ranks as $key => $row) {
        $msg[$key]  = $row['msg'];
    }
    
    array_multisort($msg, SORT_ASC, $ranks);
    $num = 1;
    $cfg['ranks'] = array();
    
    foreach ($ranks as $key => $row) {
        if (!$row['msg'] || !$row['title']) {
            unset($ranks[$key]); continue;
        }
        
        $cfg['ranks'][$num] = $row; $num++; 
    } 

    $inCore->saveComponentConfig('forum', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'info');
    cmsCore::redirectBack();
}

if ($opt == 'show_forum'){
    if (!cmsCore::inRequest('item')){
        if (cmsCore::inRequest('item_id')){
            cmsCore::c('db')->setFlag('cms_forums', cmsCore::request('item_id', 'int', 0), 'published', '1');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_forums', cmsCore::request('item', 'array_int'), 'published', '1');
        cmsCore::redirectBack();
    }
}

if ($opt == 'hide_forum'){
    if (!cmsCore::inRequest('item')){
        if (cmsCore::inRequest('item_id')){
            cmsCore::c('db')->setFlag('cms_forums', cmsCore::request('item_id', 'int', 0), 'published', '0');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_forums', cmsCore::request('item', 'array_int'), 'published', '0');
        cmsCore::redirectBack();
    }
}

if ($opt == 'submit_forum'){
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $moder_list  = cmsCore::request('moder_list', 'array_int', array());
    $moder_list  = $moder_list ? cmsCore::arrayToYaml($moder_list) : '';

    $is_access = cmsCore::request('is_access', 'int', '');
    if (!$is_access) {
        $access_list = cmsCore::request('access_list', 'array_int');
        $group_access = $access_list ? cmsCore::arrayToYaml($access_list) : '';
    } else {
        $group_access = '';
    }

    $icon = uploadCategoryIcon();
    
    cmsCore::c('db')->addNsCategory('cms_forums', array(
        'category_id' => cmsCore::request('category_id', 'int'),
        'parent_id'   => cmsCore::request('parent_id', 'int'),
        'title'       => cmsCore::c('db')->escape_string(cmsCore::request('title', 'str', 'NO_TITLE')),
        'description' => cmsCore::c('db')->escape_string(cmsCore::request('description', 'str', '')),
        'access_list' => $group_access,
        'moder_list'  => $moder_list,
        'published'   => cmsCore::request('published', 'int', 0),
        'icon'        => $icon,
        'pagetitle'   => cmsCore::request('pagetitle', 'str', ''),
        'meta_keys'   => cmsCore::request('meta_keys', 'str', ''),
        'meta_desc'   => cmsCore::request('meta_desc', 'str', ''),
        'topic_cost'  => cmsCore::request('topic_cost', 'int', 0))
    );

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'info');
    cmsCore::redirect('?view=components&do=config&opt=list_forums&id='. $id);
}

if ($opt == 'update_forum'){
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $item_id     = cmsCore::request('item_id', 'int');
    $category_id = cmsCore::request('category_id', 'int');
    $title       = cmsCore::request('title', 'str', 'NO_TITLE');
    $pagetitle   = cmsCore::request('pagetitle', 'str', '');
    $meta_keys   = cmsCore::request('meta_keys', 'str', '');
    $meta_desc   = cmsCore::request('meta_desc', 'str', '');
    $published   = cmsCore::request('published', 'int');
    $parent_id   = cmsCore::request('parent_id', 'int');
    $description = cmsCore::request('description', 'str');
    $topic_cost  = cmsCore::request('topic_cost', 'int', 0);
    $moder_list  = cmsCore::request('moder_list', 'array_int', array());
    $moder_list  = $moder_list ? cmsCore::arrayToYaml($moder_list) : '';

    $is_access = cmsCore::request('is_access', 'int', '');
    if (!$is_access) {
        $access_list = cmsCore::request('access_list', 'array_int');
        $group_access = $access_list ? cmsCore::arrayToYaml($access_list) : '';
        cmsCore::c('db')->query("UPDATE cms_forum_threads SET is_hidden = 1 WHERE forum_id = '". $item_id ."'");
    } else {
        $group_access = '';
        cmsCore::c('db')->query("UPDATE cms_forum_threads SET is_hidden = 0 WHERE forum_id = '". $item_id ."'");
    }

    $ns = $inCore->nestedSetsInit('cms_forums');
    $old = cmsCore::c('db')->get_fields('cms_forums', "id='". $item_id ."'", '*');

    $icon = uploadCategoryIcon($old['icon']);

    if ($parent_id != $old['parent_id']) {
        $ns->MoveNode($item_id, $parent_id);
    }
    
    cmsCore::c('db')->update('cms_forums', array(
        'category_id' => $category_id,
        'title'       => cmsCore::c('db')->escape_string($title),
        'description' => cmsCore::c('db')->escape_string($description),
        'access_list' => $group_access,
        'moder_list'  => $moder_list,
        'published'   => $published,
        'icon'        => $icon,
        'topic_cost'  => $topic_cost,
        'pagetitle'   => cmsCore::c('db')->escape_string($pagetitle),
        'meta_keys'   => cmsCore::c('db')->escape_string($meta_keys),
        'meta_desc'   => cmsCore::c('db')->escape_string($meta_desc)
    ), $item_id);

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'info');

    if (empty($_SESSION['editlist'])) {
        cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_forums');
    } else {
        cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=edit_forum');
    }
}

if ($opt == 'delete_forum') {
    $forum = $model->getForum(cmsCore::request('item_id', 'int'));
    if (!$forum){ cmsCore::error404(); }

    cmsCore::c('db')->addJoin('INNER JOIN cms_forums f ON f.id = t.forum_id');
    $model->whereThisAndNestedForum($forum['NSLeft'], $forum['NSRight']);

    $threads = $model->getThreads();

    foreach ($threads as $thread) {
        $model->deleteThread($thread['id']);
    }

    cmsCore::c('db')->deleteNS('cms_forums', $forum['id']);
    if (file_exists(PATH.'/upload/forum/cat_icons/'. $forum['icon'])) {
        @chmod(PATH.'/upload/forum/cat_icons/'. $forum['icon'], 0777);
        @unlink(PATH.'/upload/forum/cat_icons/'. $forum['icon']);
    }

    cmsCore::addSessionMessage($_LANG['AD_FORUM_IS_DELETE'], 'info');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_forums');
}

if ($opt == 'config') {
    cpAddPathway($_LANG['AD_SETTINGS']);

    cmsCore::c('page')->initTemplate('components', 'forum_config')->
        assign('cfg', $cfg)->
        assign('groups', cmsUser::getGroups())->
        display();
}

if ($opt == 'list_ranks') {
    cpAddPathway($_LANG['AD_RANKS_FORUM']);
    
    cmsCore::c('page')->initTemplate('components', 'forum_list_ranks')->
        assign('cfg', $cfg)->
        display();
}


if ($opt == 'show_cat') {
    $item_id = cmsCore::request('item_id', 'int');
    if (!empty($item_id)) {
        cmsCore::c('db')->setFlag('cms_forum_cats', $item_id, 'published', 1);
        cmsCore::halt('1');
    }
}

if ($opt == 'hide_cat') {
    $item_id = cmsCore::request('item_id', 'int');
    if (!empty($item_id)) {
        cmsCore::c('db')->setFlag('cms_forum_cats', $item_id, 'published', 0);
        cmsCore::halt('1');
    }
}

if ($opt == 'submit_cat') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cat['title']     = cmsCore::request('title', 'str', 'NO_TITLE');
    $cat['pagetitle'] = cmsCore::request('pagetitle', 'str', '');
    $cat['meta_keys'] = cmsCore::request('meta_keys', 'str', '');
    $cat['meta_desc'] = cmsCore::request('meta_desc', 'str', '');
    $cat['published'] = cmsCore::request('published', 'int');
    $cat['ordering']  = cmsCore::request('ordering', 'int');
    $cat['seolink']   = $model->getCatSeoLink($cat['title']);

    cmsCore::c('db')->insert('cms_forum_cats', $cat);

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'info');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_cats');
}

if ($opt == 'delete_cat') {
    $item_id = cmsCore::request('item_id', 'int');
    cmsCore::c('db')->query("UPDATE cms_forums SET category_id = 0, published = 0  WHERE category_id = '". $item_id ."'");
    cmsCore::c('db')->query("DELETE FROM cms_forum_cats WHERE id = '". $item_id ."'");

    cmsCore::addSessionMessage($_LANG['AD_CATEGORY_REMOVED'], 'info');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_cats');
}

if ($opt == 'update_cat') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $item_id = cmsCore::request('item_id', 'int');

    $cat['title']     = cmsCore::request('title', 'str', 'NO_TITLE');
    $cat['pagetitle'] = cmsCore::request('pagetitle', 'str', '');
    $cat['meta_keys'] = cmsCore::request('meta_keys', 'str', '');
    $cat['meta_desc'] = cmsCore::request('meta_desc', 'str', '');
    $cat['published'] = cmsCore::request('published', 'int');
    $cat['ordering']  = cmsCore::request('ordering', 'int');
    $cat['seolink']   = $model->getCatSeoLink($cat['title'], $item_id);

    cmsCore::c('db')->update('cms_forum_cats', $cat, $item_id);
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'info');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_cats');
}

if ($opt == 'list_cats') {
    cpAddPathway($_LANG['AD_FORUMS_CATS']);
    echo '<h3>'. $_LANG['AD_FORUMS_CATS'] .'</h3>';
    
    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_cat&item_id=%id%' ),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width' => '100', 'do' => 'opt', 'do_suffix' => '_cat' )
    );

    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_cat&item_id=%id%' ),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_DELETE_CATEGORY'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete_cat&item_id=%id%' )
    );

    cpListTable('cms_forum_cats', $fields, $actions);
}

if ($opt == 'list_forums') {
    echo '<h3>'. $_LANG['AD_FORUMS'] .'</h3>';
    
    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_forum&item_id=%id%', 'filter' => '15' ),
        array( 'title' => $_LANG['AD_TOPICS'], 'field' => 'thread_count', 'width' => '60'),
        array( 'title' => $_LANG['AD_FORUM_MESSAGES'], 'field' => 'post_count', 'width' => '90' ),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width' => '60', 'do' => 'opt', 'do_suffix' => '_forum' ),
        array( 'title' => $_LANG['AD_CATEGORY'], 'field' => 'category_id', 'width' => '150', 'prc' => 'cpForumCatById', 'filter' => '1', 'filterlist' => cpGetList('cms_forum_cats'))
    );

    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_forum&item_id=%id%' ),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_FORUM_DELETE'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete_forum&item_id=%id%' )
    );

    cpListTable('cms_forums', $fields, $actions, 'parent_id>0', 'NSLeft');
}

if ($opt == 'add_cat' || $opt == 'edit_cat') {
    if ($opt == 'add_cat') {
        cpAddPathway($_LANG['AD_CREATE_CATEGORY']);
        $mod = array( 'published' => 1, 'ordering' => (int)cmsCore::c('db')->get_field('cms_forum_cats', '1=1 ORDER BY ordering DESC', 'ordering')+1 );
    } else {
        $mod = $model->getForumCat(cmsCore::request('item_id', 'int', 0));
        if (!$mod) { cmsCore::error404(); }
        
        cpAddPathway($_LANG['AD_EDIT_CATEGORY']);
    }
    
    cmsCore::c('page')->initTemplate()->
        assign('opt', $opt)->
        assign('mod', $mod)->
        display();
}

if ($opt == 'add_forum' || $opt == 'edit_forum') {
    if ($opt == 'add_forum') {
         cpAddPathway($_LANG['AD_FORUM_NEW']);
         $mod = array('published' => 1);
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
                $ostatok = '('. $_LANG['AD_NEXT_IN'] . count($_SESSION['editlist']) .')';
            }
        } else {
            $item_id = cmsCore::request('item_id', 'int', 0);
        }

        $mod = $model->getForum($item_id);
        if (!$mod){ cmsCore::error404(); }

        cpAddPathway($mod['title']);
    }
    
    if (!empty($mod['moder_list'])) {
        $moder_list = $inCore->yamlToArray($mod['moder_list']);
        if ($moder_list) {
            $moder_list = cmsUser::getAuthorsList($moder_list, $moder_list);
        }
    }
    
    cmsCore::c('page')->initTemplate('components', 'forum_add_forum')->
        assign('is_billing', IS_BILLING)->
        assign('opt', $opt)->
        assign('ostatok', $ostatok)->
        assign('rootid', cmsCore::c('db')->get_field('cms_forums', 'parent_id=0', 'id'))->
        assign('forums_opt', $inCore->getListItemsNS('cms_forums', cmsCore::getArrVal($mod, 'parent_id', 0)))->
        assign('forum_cats_opt', $inCore->getListItems('cms_forum_cats', cmsCore::getArrVal($mod, 'category_id', cmsCore::request('addto', 'int', 0)), 'ordering'))->
        assign('groups', cmsUser::getGroups())->
        assign('access_list', !empty($mod['access_list']) ? $inCore->yamlToArray($mod['access_list']) : false)->
        assign('moder_list', $moder_list)->
        assign('mod', $mod)->
        display();
}