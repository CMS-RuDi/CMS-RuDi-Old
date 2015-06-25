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

function cpStripComment($text) {
    return crop($text, 120, '...');
}

function cpCommentAuthor($item) {
    if (!$item['user_id']) {
        $author = $item['guestname'];
    } else {
        $u = cmsCore::c('db')->get_fields('cms_users', "id='". $item['user_id'] ."'", 'id, nickname, login');
        $author = $u['nickname'] .' (<a href="/admin/index.php?view=users&do=edit&id='. $u['id'] .'" target="_blank">'. $u['login'] .'</a>)';
    }
    
    return $author;
}

function cpCommentTarget($item) {
    return '<a target="_blank" href="'. $item['target_link'] .'#c'. $item['id'] .'">'. $item['target_title'] .'</a>';
}

$opt = cmsCore::request('opt', 'str', 'list');

$toolmenu = array(
    array( 'icon' => 'listcomments.gif', 'title' => $_LANG['AD_ALL_COMENTS'], 'link' => '?view=components&do=config&id='. $id .'&opt=list'),
    array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=components&do=config&id='. $id .'&opt=config')
);

cpToolMenu($toolmenu);

cmsCore::loadModel('comments');
$model = new cms_model_comments();

$cfg = $model->config;

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg['email']          = cmsCore::request('email', 'email', '');
    $cfg['regcap']         = cmsCore::request('regcap', 'int');
    $cfg['subscribe']      = cmsCore::request('subscribe', 'int');
    $cfg['min_karma'] 	   = cmsCore::request('min_karma', 'int');
    $cfg['min_karma_show'] = cmsCore::request('min_karma_show', 'int');
    $cfg['min_karma_add']  = cmsCore::request('min_karma_add', 'int');
    $cfg['perpage'] 	   = cmsCore::request('perpage', 'int');
    $cfg['cmm_ajax'] 	   = cmsCore::request('cmm_ajax', 'int');
    $cfg['cmm_ip']         = cmsCore::request('cmm_ip', 'int');
    $cfg['max_level'] 	   = cmsCore::request('max_level', 'int');
    $cfg['edit_minutes']   = cmsCore::request('edit_minutes', 'int');
    $cfg['watermark'] 	   = cmsCore::request('watermark', 'int');
    $cfg['meta_keys']      = cmsCore::request('meta_keys', 'str', '');
    $cfg['meta_desc']      = cmsCore::request('meta_desc', 'str', '');

    $inCore->saveComponentConfig('comments', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

if ($opt == 'show_comment') {
    $item_id = cmsCore::request('item_id', 'int', 0);
    cmsCore::c('db')->query("UPDATE cms_comments SET published = 1 WHERE id = '$item_id'");
    cmsCore::halt('1');
}

if ($opt == 'hide_comment') {
    $item_id = cmsCore::request('item_id', 'int', 0);
    cmsCore::c('db')->query("UPDATE cms_comments SET published = 0 WHERE id = '$item_id'") ;
    cmsCore::halt('1');
}

if ($opt == 'update') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $item_id = cmsCore::request('item_id', 'int', 0);

    $guestname = cmsCore::request('guestname', 'str', '');
    $pubdate   = cmsCore::request('pubdate', 'str');
    $published = cmsCore::request('published', 'int');
    $content   = cmsCore::c('db')->escape_string(cmsCore::request('content', 'html'));

    $sql = "UPDATE cms_comments
            SET guestname = '$guestname',
                pubdate = '$pubdate',
                published=$published,
                content='$content'
            WHERE id = $item_id
            LIMIT 1";
    cmsCore::c('db')->query($sql) ;

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('index.php?view=components&do=config&id='. $id .'&opt=list');
}

if ($opt == 'delete') {
    $model->deleteComment(cmsCore::request('item_id', 'int'));
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('index.php?view=components&do=config&id='. $id .'&opt=list');
}

if ($opt == 'list') {
    if (cmsCore::inRequest('show_hidden')) {
        cpAddPathway($_LANG['AD_COMENTS_ON_MODERATE']);
        echo '<h3>'. $_LANG['AD_COMENTS_ON_MODERATE'] .'</h3>';
    } else {
        echo '<h3>'. $_LANG['AD_ALL_COMENTS'] .'</h3>';
    }

    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['DATE'], 'field' => 'pubdate', 'width' => '100' ),
        array( 'title' => $_LANG['AD_TEXT'], 'field' => 'content', 'width' => '', 'prc' => 'cpStripComment' ),
        array( 'title' => $_LANG['AD_IP'], 'field' => 'ip', 'width' => '80' ),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width' => '70', 'do' => 'opt', 'do_suffix' => '_comment' ),
        array( 'title' => $_LANG['AD_AUTHOR'], 'field' => array('user_id', 'guestname'), 'width' => '180', 'prc' => 'cpCommentAuthor' ),
        array( 'title' => $_LANG['AD_AIM'], 'field' => array('target_title', 'target_link', 'id'), 'width' => '220', 'prc' => 'cpCommentTarget' )
    );
    
    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit&item_id=%id%' ),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_IF_COMENT_DELETE'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete&item_id=%id%' )
    );

    $where = cmsCore::inRequest('show_hidden') ? 'published = 0' : '1 = 1';

    cpListTable('cms_comments', $fields, $actions, $where, 'pubdate DESC');
}

if ($opt == 'edit') {
    $mod = $model->getComment(cmsCore::request('item_id', 'int'));
    if (!$mod) { cmsCore::error404(); }

    cpAddPathway($_LANG['AD_EDIT_COMENT']);
    
    cmsCore::c('page')->initTemplate('components', 'comments_edit')->
        assign('mod', $mod)->
        display();
}

if ($opt == 'config') {
    cpAddPathway($_LANG['AD_SETTINGS']);
    
    cmsCore::c('page')->initTemplate('components', 'comments_config')->
        assign('cfg', $cfg)->
        display();
}