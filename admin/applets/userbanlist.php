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

function applet_userbanlist() {
    $inCore = cmsCore::getInstance();
    
    global $_LANG;
    global $adminAccess;
    
    if (!cmsUser::isAdminCan('admin/users', $adminAccess)) { cpAccessDenied(); }

    cmsCore::c('page')->setTitle($_LANG['AD_BANLIST']);
    cpAddPathway($_LANG['AD_USERS'], 'index.php?view=users');
    cpAddPathway($_LANG['AD_BANLIST'], 'index.php?view=userbanlist');

    $do = cmsCore::request('do', 'str', 'list');
    $id = cmsCore::request('id', 'int', -1);
    $to = cmsCore::request('to', 'int', 0);
    
    // для редиректа обратно в профиль на сайт
    if ($to) {
        cmsUser::sessionPut('back_url', cmsCore::getBackURL());
    }

    if ($do == 'list') {
        $toolmenu = array(
            array( 'icon' => 'useradd.gif', 'title' => $_LANG['AD_TO_BANLIST_ADD'], 'link' => '?view=userbanlist&do=add' ),
            array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link' => "javascript:checkSel('?view=userbanlist&do=edit&multiple=1');" ),
            array( 'icon' => 'delete.gif', 'title' => $_LANG['AD_DELETE_SELECTED'], 'link' => "javascript:checkSel('?view=userbanlist&do=delete&multiple=1');" )
        );

        cpToolMenu($toolmenu);

        $fields = array(
            array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
            array( 'title' => $_LANG['AD_IS_ACTIVE'], 'field' => 'status', 'width' => '65', 'prc' => 'cpYesNo' ),
            array( 'title' => $_LANG['AD_BANLIST_USER'], 'field' => 'user_id', 'width' => '120', 'filter' => '12', 'prc' => 'cpUserNick' ),
            array( 'title' => $_LANG['AD_BANLIST_IP'], 'field' => 'ip', 'width' => '100', 'link' => '?view=userbanlist&do=edit&id=%id%', 'filter' => '12' ),
            array( 'title' => $_LANG['DATE'], 'field' => 'bandate', 'width' => '', 'fdate' => '%d/%m/%Y %H:%i:%s', 'filter' => '12' ),
            array( 'title' => $_LANG['AD_BANLIST_TIME'], 'field' => 'int_num', 'width' => '55' ),
            array( 'title' => '', 'field' => 'int_period', 'width' => '70' ),
            array( 'title' => $_LANG['AD_AUTOREMOVE'], 'field' => 'autodelete', 'width' => '100', 'prc' => 'cpYesNo' )
        );
        
        $actions = array(
            array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=userbanlist&do=edit&id=%id%' ),
            array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_REMOVE_RULE'], 'link' => '?view=userbanlist&do=delete&id=%id%' )
        );

        cpListTable('cms_banlist', $fields, $actions, '1=1', 'ip DESC');
    }

    if ($do == 'delete') {
        if (!cmsCore::inRequest('item')) {
            if ($id >= 0) { dbDelete('cms_banlist', $id); }
        } else {
            dbDeleteList('cms_banlist', cmsCore::request('item', 'array_int', array()));
        }
        cmsCore::redirect('?view=userbanlist');
    }

    if ($do == 'submit' || $do == 'update') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $types = array(
            'user_id' => array( 'user_id', 'int', 0 ),
            'ip' => array( 'ip', 'str', '' ),
            'cause' => array( 'cause', 'str', '' ),
            'autodelete' => array( 'autodelete', 'int', 0 ),
            'int_num' => array( 'int_num', 'int', 0 ),
            'int_period' => array( 'int_period', 'str', '', create_function('$p', 'if(!in_array($p, array("MONTH","DAY","HOUR","MINUTE"))){ $p = "MINUTE"; } return $p;') )
        );

        $items = cmsCore::getArrayFromRequest($types);

        $error = false;

        if (!$items['ip']) {
            $error = true;
            cmsCore::addSessionMessage($_LANG['AD_NEED_IP'], 'error');
        }
        
        if ($items['ip'] == $_SERVER['REMOTE_ADDR'] ||
            $items['user_id'] == cmsCore::c('user')->id) {
            $error = true;
            cmsCore::addSessionMessage($_LANG['AD_ITS_YOUR_IP'], 'error');
        }

        if (cmsUser::userIsAdmin($items['user_id'])) {
            $error = true;
            cmsCore::addSessionMessage($_LANG['AD_ITS_ADMIN'], 'error');
        }

        if ($error) {
            cmsCore::redirectBack();
        }

        if ($do == 'update') {
            cmsCore::c('db')->update('cms_banlist', $items, $id);

            if (empty($_SESSION['editlist'])) {
                cmsCore::redirect('?view=userbanlist');
            } else {
                cmsCore::redirect('?view=userbanlist&do=edit');
            }
        }

        cmsCore::c('db')->insert('cms_banlist', $items);
        $back_url = cmsUser::sessionGet('back_url');
        cmsUser::sessionDel('back_url');
        cmsCore::redirect($back_url ? $back_url : '?view=userbanlist');
    }

    if ($do == 'add' || $do == 'edit') {
        cmsCore::c('page')->addHeadJS('admin/js/banlist.js');

        $toolmenu = array(
            array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.addform.submit();' ),
            array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => 'javascript:history.go(-1);' )
        );

        cpToolMenu($toolmenu);

        if ($do == 'add') {
            echo '<h3>'. $_LANG['AD_TO_BANLIST_ADD'] .'</h3>';
            cpAddPathway($_LANG['AD_TO_BANLIST_ADD']);
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
                if (count($_SESSION['editlist']) == 0) {
                   unset($_SESSION['editlist']);
                } else {
                    $ostatok = '('. $_LANG['AD_NEXT_IN'] . count($_SESSION['editlist']) .')';
                }
            } else {
                $item_id = cmsCore::request('id', 'int', 0);
            }

            $mod = cmsCore::c('db')->get_fields('cms_banlist', "id = '". $item_id ."'", '*');
            if (!$mod) { cmsCore::error404(); }

            echo '<h3>'. $_LANG['AD_EDIT_RULE'] .' '. $ostatok .'</h3>';

            cpAddPathway($_LANG['AD_EDIT_RULE']);
        }
        
        if ($do == 'add' && $to) {
            $mod['user_id'] = $to;
            $mod['ip'] = cmsCore::c('db')->get_field('cms_users', 'id='. $to, 'last_ip');
        }
        
        cmsCore::c('page')->initTemplate('applets', 'userbanlist_add')->
            assign('do', $do)->
            assign('mod', $mod)->
            assign('users_opt', $inCore->getListItems('cms_users', cmsCore::getArrVal($mod, 'user_id', 0), 'nickname', 'ASC', 'is_deleted=0 AND is_locked=0', 'id', 'nickname'))->
            display();
   }
}