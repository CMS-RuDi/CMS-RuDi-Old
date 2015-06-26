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

if (!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function getCountUsers($id) {
    $count = cmsCore::c('db')->rows_count('cms_users', "group_id = '". $id ."'");
    return '<a href="?view=users&filter[group_id]='. $id .'">'. $count .'</a>';
}

function applet_usergroups() {
    global $_LANG;
    global $adminAccess;
    if (!cmsUser::isAdminCan('admin/users', $adminAccess)) { cpAccessDenied(); }

    cmsCore::c('page')->setTitle($_LANG['AD_USERS_GROUP']);
    cpAddPathway($_LANG['AD_USERS'], 'index.php?view=users');
    cpAddPathway($_LANG['AD_USERS_GROUP'], 'index.php?view=usergroups');

    $do = cmsCore::request('do', 'str', 'list');
    $id = cmsCore::request('id', 'int', -1);

    cmsCore::loadModel('users');
    $model = new cms_model_users();

    if ($do == 'list') {
        $toolmenu = array(
            array( 'icon' => 'usergroupadd.gif', 'title' => $_LANG['AD_CREATE_GROUP'], 'link' => '?view=usergroups&do=add' ),
            array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link' => "javascript:checkSel('?view=usergroups&do=edit&multiple=1');" ),
            array( 'icon' => 'delete.gif', 'title' => $_LANG['AD_DELETE_SELECTED'], 'link' => "javascript:if(confirm('". $_LANG['AD_REMOVE_GROUP'] ."')) { checkSel('?view=users&do=delete&multiple=1'); }" )
        );
        
        cpToolMenu($toolmenu);
        
        $fields = array(
            array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
            array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'link' => '?view=usergroups&do=edit&id=%id%', 'filter' => '12' ),
            array( 'title' => $_LANG['AD_FROM_USERS'], 'field' => 'id', 'width' => '110', 'prc' => 'getCountUsers' ),
            array( 'title' => $_LANG['AD_IF_ADMIN'], 'field' => 'is_admin', 'width' => '120', 'prc' => 'cpYesNo' ),
            array( 'title' => $_LANG['AD_ALIAS'], 'field' => 'alias', 'width' => '85', 'filter' => '12' )
        );
        
        $actions = array(
            array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=usergroups&do=edit&id=%id%' ),
            array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_REMOVE_GROUP'], 'link' => '?view=usergroups&do=delete&id=%id%' )
        );
        
        cpListTable('cms_user_groups', $fields, $actions);
    }
    
    if ($do == 'delete') {
        if (!cmsCore::inRequest('item')){
            if ($id >= 0){
                $model->deleteGroup($id);
            }
        } else {
            $model->deleteGroups(cmsCore::request('item', 'array_int', array()));
        }
        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
        cmsCore::redirect('index.php?view=usergroups');
    }

    if ($do == 'submit' || $do == 'update') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $types = array(
            'title' => array( 'title', 'str', '' ),
            'alias' => array( 'alias', 'str', '' ),
            'is_admin' => array( 'is_admin', 'int', 0 ),
            'access' => array( 'access', 'array_str', array(), create_function('$a_list', 'return implode(\',\', $a_list);') )
        );

        $items = cmsCore::getArrayFromRequest($types);

        if ($do == 'submit') {
            cmsCore::c('db')->insert('cms_user_groups', $items);
            cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
            cmsCore::redirect('index.php?view=usergroups');
        } else {
            cmsCore::c('db')->update('cms_user_groups', $items, $id);
            cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
            if (empty($_SESSION['editlist'])) {
                cmsCore::redirect('index.php?view=usergroups');
            } else {
                cmsCore::redirect('index.php?view=usergroups&do=edit');
            }
        }
    }

    if ($do == 'add' || $do == 'edit') {
        $toolmenu = array(
            array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.addform.submit();' ),
            array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => 'javascript:history.go(-1);' )
        );
        
        cpToolMenu($toolmenu);

        if ($do == 'add') {
            cpAddPathway($_LANG['AD_CREATE_GROUP']);
            $mod = array();
        } else {
            if(cmsCore::inRequest('multiple')){
                if (cmsCore::inRequest('item')){
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

            $mod = cmsCore::c('db')->get_fields('cms_user_groups', "id = '". $item_id ."'", '*');
            if (!$mod){ cmsCore::error404(); }

            echo '<h3>'. $_LANG['AD_EDIT_GROUP'] .' '. $ostatok .'</h3>';

            cpAddPathway($_LANG['AD_EDIT_GROUP'] .' '. $mod['title']);
        }

        if (isset($mod['access'])) {
            $mod['access'] = str_replace(', ', ',', $mod['access']);
            $mod['access'] = explode(',', $mod['access']);
        }
        
        $gas = array();
        $sql = "SELECT * FROM cms_user_groups_access ORDER BY access_type";
        $res = cmsCore::c('db')->query($sql);

        while ($ga = cmsCore::c('db')->fetch_assoc($res)) {
            $gas[] = $ga;
        }
        
        cmsCore::c('page')->initTemplate('applets', 'usergroups_add')->
            assign('do', $do)->
            assign('coms', cmsCore::getInstance()->getAllComponents())->
            assign('gas', $gas)->
            assign('mod', $mod)->
            display();
   }
}