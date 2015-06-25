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

function cpBlogOwner($item) {
    if ($item['owner'] == 'user') {
        $nickname = cmsCore::c('db')->get_field('cms_users', "id='". $item['user_id']. "'", 'nickname');
        $link = '<a href="?view=users&do=edit&id='. $item['user_id'] .'" class="user_link" target="_blank">'.$nickname.'</a>';
    } else {
        $title = cmsCore::c('db')->get_field('cms_clubs', "id='". $item['user_id'] ."'", 'title');
        $link = '<a href="?view=components&do=config&link=clubs&opt=edit&item_id='. $item['user_id'] .'" class="club_link" target="_blank">'. $title .'</a>';
    }
    return $link;
}
/******************************************************************************/

$opt = cmsCore::request('opt', 'str', 'list_blogs');

$cfg = $inCore->loadComponentConfig('blogs');

cmsCore::loadModel('blogs');
$model = new cms_model_blogs();

cmsCore::loadClass('blog');
$inBlog = cmsBlogs::getInstance();

/******************************************************************************/

if ($opt == 'list_blogs') {
    $toolmenu = array(
        array( 'icon' => 'listblogs.gif', 'title' => $_LANG['AD_BLOGS'], 'link'=>'?view=components&do=config&link=blogs&opt=list_blogs'),
        array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&link=blogs&opt=edit_blog&multiple=1');"),
        array( 'icon' => 'delete.gif', 'title' => $_LANG['AD_DELETE_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&link=blogs&opt=delete_blog&multiple=1');"),
        array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=components&do=config&link=blogs&opt=config')
    );

    cpToolMenu($toolmenu);

    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['AD_CREATED'], 'field' => 'pubdate', 'width' => '80', 'filter' => 15, 'fdate' => '%d/%m/%Y' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'filter' => 15, 'link' => '?view=components&do=config&link=blogs&opt=edit_blog&item_id=%id%' ),
        array( 'title' => $_LANG['AD_OWNER'], 'field' => array('id','owner','user_id'), 'width' => '300', 'prc' => 'cpBlogOwner' )
    );
    
    $actions = array(
        array( 'title' => $_LANG['AD_RENAME'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&link=blogs&opt=edit_blog&item_id=%id%'),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_IF_BLOG_DELETE'], 'link' => '?view=components&do=config&link=blogs&opt=delete_blog&item_id=%id%')
    );

    cpListTable('cms_blogs', $fields, $actions, '', 'pubdate DESC');
}

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg['perpage']             = cmsCore::request('perpage', 'int', 0);
    $cfg['perpage_blog']        = cmsCore::request('perpage_blog', 'int', 0);
    $cfg['update_date']         = cmsCore::request('update_date', 'int', 0);
    $cfg['update_seo_link']     = cmsCore::request('update_seo_link', 'int', 0);
    $cfg['min_karma_private']   = cmsCore::request('min_karma_private', 'int', 0);
    $cfg['min_karma_public']    = cmsCore::request('min_karma_public', 'int', 0);
    $cfg['min_karma']           = cmsCore::request('min_karma', 'int', 0);
    $cfg['list_min_rating']     = cmsCore::request('list_min_rating', 'int', 0);
    $cfg['watermark']           = cmsCore::request('watermark', 'int', 0);
    $cfg['img_on']              = cmsCore::request('img_on', 'int', 0);
    $cfg['update_seo_link_blog']= cmsCore::request('update_seo_link_blog', 'int', 0);
    $cfg['meta_keys']           = cmsCore::request('meta_keys', 'str', '');
    $cfg['meta_desc']           = cmsCore::request('meta_desc', 'str', '');
    $cfg['seo_user_access']     = cmsCore::request('seo_user_access', 'int', 0);

    $inCore->saveComponentConfig('blogs', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

if ($opt == 'delete_blog') {
    if (!isset($_REQUEST['item'])) {
        $inBlog->deleteBlog(cmsCore::request('item_id', 'int', 0));
    } else {
        $inBlog->deleteBlogs(cmsCore::request('item', 'array_int', array()));
    }
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

if ($opt == 'update_blog') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $blog = $inBlog->getBlog(cmsCore::request('item_id', 'int', 0));
    if (!$blog) { cmsCore::error404(); }

    $title = cmsCore::request('title', 'str', $blog['title']);

    $seolink_new = $inBlog->updateBlog($blog['id'], array('title'=>$title), true);

    $blog['seolink'] = $seolink_new ? $seolink_new : $blog['seolink'];

    if (stripslashes($title) != $blog['title']) {
        cmsActions::updateLog( 'add_post', array( 'target' => $title, 'target_url' => $model->getBlogURL($blog['seolink']) ), 0, $blog['id'] );
        cmsActions::updateLog( 'add_blog', array( 'object' => $title, 'object_url' => $model->getBlogURL($blog['seolink']) ), $blog['id'] );
    }

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'] .'. '. $_LANG['AD_SAVE_SUCCESS'], 'success');

    if (empty($_SESSION['editlist'])) {
        cmsCore::redirect('?view=components&do=config&link=blogs&opt=list_blogs');
    } else {
        cmsCore::redirect('?view=components&do=config&link=blogs&opt=edit_blog');
    }
}

if ($opt == 'config') {
    cpAddPathway($_LANG['AD_SETTINGS']);

    $toolmenu = array(
        array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.optform.submit();' ),
        array( 'icon' => 'listblogs.gif', 'title' => $_LANG['AD_BLOGS'], 'link'=>'?view=components&do=config&link=blogs&opt=list_blogs'),
        array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=components&do=config&link=blogs&opt=config')
    );

    cpToolMenu($toolmenu);
    
    cmsCore::c('page')->initTemplate('components', 'blogs_config')->
        assign('id', $id)->
        assign('com', $com)->
        assign('cfg', $cfg)->
        display();
}

if ($opt == 'edit_blog') {
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

    $mod = cmsCore::c('db')->get_fields('cms_blogs', "id = '". $item_id ."'", '*');
    if (!$mod) { cmsCore::error404(); }

    
    cpAddPathway($mod['title']);

    cmsCore::c('page')->initTemplate()->
        assign('ostatok', $ostatok)->
        assign('mod', $mod)->
        display();
}