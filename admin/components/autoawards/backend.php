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

$opt = cmsCore::request('opt', 'str', 'list');

if ($opt == 'list') {
    $toolmenu = array(
        array( 'icon' => 'newaward.gif', 'title' => $_LANG['AD_NEW_AWARD'], 'link' => '?view=components&do=config&id='. $id .'&opt=add' ),
        array( 'icon' => 'listawards.gif', 'title' => $_LANG['AD_ALL_AWARDS'], 'link' => '?view=components&do=config&id='. $id .'&opt=list' ),
        array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=edit&multiple=1');" ),
        array( 'icon' => 'show.gif', 'title' => $_LANG['AD_ALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=show_award&multiple=1');" ),
        array( 'icon' => 'hide.gif', 'title' => $_LANG['AD_DISALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=hide_award&multiple=1');" )
    );

    cpToolMenu($toolmenu);
}

if ($opt == 'show_award') {
    if (!isset($_REQUEST['item'])) {
        if (isset($_REQUEST['item_id'])) {
            cmsCore::c('db')->setFlag('cms_user_autoawards', $_REQUEST['item_id'], 'published', '1');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_user_autoawards', $_REQUEST['item'], 'published', '1');
        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
        cmsCore::redirectBack();
    }
}

if ($opt == 'hide_award') {
    if (!isset($_REQUEST['item'])) {
        if (isset($_REQUEST['item_id'])) {
            cmsCore::c('db')->setFlag('cms_user_autoawards', $_REQUEST['item_id'], 'published', '0');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_user_autoawards', $_REQUEST['item'], 'published', '0');
        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
        cmsCore::redirectBack();
    }
}

if ($opt == 'submit' || $opt == 'update') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $title       = cmsCore::request('title', 'str', $_LANG['AD_AWARD']);
    $description = cmsCore::request('description', 'str', '');
    $published   = cmsCore::request('published', 'int', 0);
    $imageurl    = preg_replace('/[^a-zA-Z0-9_\.\-]/iu', '', cmsCore::request('imageurl', 'str', ''));
    $p_comment   = cmsCore::request('p_comment', 'int', 0);
    $p_forum     = cmsCore::request('p_forum', 'int', 0);
    $p_content   = cmsCore::request('p_content', 'int', 0);
    $p_blog      = cmsCore::request('p_blog', 'int', 0);
    $p_karma     = cmsCore::request('p_karma', 'int', 0);
    $p_photo     = cmsCore::request('p_photo', 'int', 0);
    $p_privphoto = cmsCore::request('p_privphoto', 'int', 0);

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');

    if ($opt == 'submit') {
        $sql = "INSERT INTO cms_user_autoawards (title, description, imageurl, p_comment, p_blog, p_forum, p_photo, p_privphoto, p_content, p_karma, published)
                VALUES ('$title', '$description', '$imageurl', $p_comment, $p_blog, $p_forum, $p_photo, $p_privphoto, $p_content, $p_karma, $published)";
        cmsCore::c('db')->query($sql);

        cmsCore::redirect('?view=components&do=config&opt=list&id='.$id);
    } else {
        $item_id = cmsCore::request('item_id', 'int', 0);

        $sql = "UPDATE cms_user_autoawards
                SET title='$title',
                    description='$description',
                    imageurl='$imageurl',
                    p_comment=$p_comment,
                    p_blog=$p_blog,
                    p_forum=$p_forum,
                    p_photo=$p_photo,
                    p_privphoto=$p_privphoto,
                    p_content=$p_content,
                    p_karma=$p_karma,
                    published=$published
                WHERE id = '$item_id'";

        cmsCore::c('db')->query($sql);

        if (!isset($_SESSION['editlist']) || @sizeof($_SESSION['editlist'])==0) {
            cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list');
        } else {
            cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=edit');
        }
    }
}

if ($opt == 'delete') {
    $item_id = cmsCore::request('item_id', 'int', 0);
    $sql = "DELETE FROM cms_user_autoawards WHERE id = $item_id";

    cmsCore::c('db')->query($sql);
    $sql = "DELETE FROM cms_user_awards WHERE award_id = $item_id";

    cmsCore::c('db')->query($sql);
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list');
}

if ($opt == 'list') {
    $fields = array(
        array('title' => 'id', 'field' => 'id', 'width' => '40'),
        array('title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '250', 'filter' => 15, 'link' => '?view=components&do=config&id='. $id .'&opt=edit&item_id=%id%'),
        array('title' => $_LANG['DESCRIPTION'], 'field' => 'description', 'width' => '', 'filter' => 15),
        array('title' => $_LANG['AD_GIVING'], 'field' => 'published', 'width' => '100', 'do' => 'opt', 'do_suffix' => '_award'),
    );


    $actions = array(
        array('title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit&item_id=%id%'),
        array('title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=delete&item_id=%id%', 'confirm' => $_LANG['AD_CONFIRM_DELETING']),
    );

    cpListTable('cms_user_autoawards', $fields, $actions);
}

if ($opt == 'add' || $opt == 'edit') {
    if ($opt == 'add') {
        cpAddPathway($_LANG['AD_NEW_AWARD']);
        echo '<h3>'.$_LANG['AD_NEW_AWARD'].'</h3>';
        $mod = array();
    } else {
        if (isset($_REQUEST['multiple'])) {
            if (isset($_REQUEST['item'])) {
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
                $ostatok = '('.$_LANG['AD_NEXT_IN'].sizeof($_SESSION['editlist']).')';
            }
        } else {
            $item_id = cmsCore::request('item_id', 'int', 0);
        }

        $mod = cmsCore::c('db')->get_fields('cms_user_autoawards', "id = '$item_id'", '*');
        if (!$mod) { cmsCore::error404(); }

        echo '<h3>'. $mod['title'] .' '. $ostatok .'</h3>';
        cpAddPathway($mod['title']);
    }
    
    $awards_img = cmsUser::getAwardsImages();
    
    cmsCore::c('page')->initTemplate('components', 'autoawards_add')->
        assign('id', $id)->
        assign('opt', $opt)->
        assign('awards_img', $awards_img)->
        assign('mod', $mod)->
        display();
}