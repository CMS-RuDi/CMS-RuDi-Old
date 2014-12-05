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

function viewAct($value) {
    global $_LANG;
    if (!$value) {
        $value = '<span style="color:green;">'. $_LANG['YES'] .'</span>';
    } else {
        $value = '<span style="color:red;">'. $_LANG['NO'] .'</span>';
    }
    return $value;
}

function viewDel($value) {
    global $_LANG;
    if (!$value) {
        $value = '<span style="color:green;">'. $_LANG['NO'] .'</span>';
    } else {
        $value = '<span style="color:red;">'. $_LANG['YES'] .'</span>';
    }
    return $value;
}

function setRating($item) {
    global $_LANG;
    return '<a href="?view=users&do=rerating&user_id='. $item['id'] .'" title="'. $_LANG['AD_RATING_CALCULATE'] .'">'. $item['rating'] .'</a>';
}

function getIpLink($ip) {
    return '<a target="_blank" href="https://apps.db.ripe.net/search/query.html?searchtext='. $ip .'">'. $ip .'</a>';
}

function applet_users() {
    $inCore = cmsCore::getInstance();
    cmsCore::loadClass('actions');
    cmsCore::loadModel('users');
    $model = new cms_model_users();

    // подключаем язык компонента регистрации
    cmsCore::loadLanguage('components/registration');

    global $_LANG;
    global $adminAccess;
    if (!cmsUser::isAdminCan('admin/users', $adminAccess)) { cpAccessDenied(); }

    cmsCore::c('page')->setAdminTitle($_LANG['AD_USERS']);
    cpAddPathway($_LANG['AD_USERS'], 'index.php?view=users');

    $do = cmsCore::request('do', 'str', 'list');
    $id = cmsCore::request('id', 'int', 0);

    if ($do == 'list') {
        $toolmenu = array(
            array( 'icon' => 'useradd.gif', 'title' => $_LANG['AD_USER_ADD'], 'link' => '?view=users&do=add' ),
            array( 'icon' => 'useredit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link' => "javascript:checkSel('?view=users&do=edit&multiple=1');" ),
            array( 'icon' => 'userdelete.gif', 'title' => $_LANG['AD_DELETE_SELECTED'], 'link' => "javascript:if(confirm('". $_LANG['AD_IF_USERS_SELECT_REMOVE'] ."')) { checkSel('?view=users&do=delete&multiple=1'); }" ),
            array( 'icon' => 'usergroup.gif', 'title' => $_LANG['AD_USERS_GROUP'], 'link' => '?view=usergroups' ),
            array( 'icon' => 'userbanlist.gif', 'title' => $_LANG['AD_BANLIST'], 'link' => '?view=userbanlist' ),
            array( 'icon' => 'user_go.png', 'title' => $_LANG['AD_USERS_SELECT_ACTIVATE'], 'link' => "javascript:if(confirm('". $_LANG['AD_IF_USERS_SELECT_ACTIVATE'] ."')) { checkSel('?view=users&do=activate&multiple=1'); }" ),
            array( 'icon' => 'help.gif', 'title' => $_LANG['AD_HELP'], 'link' => '?view=help&topic=users' )
        );
        
        cpToolMenu($toolmenu);
        
        $fields = array(
            array( 'title' => 'id', 'field' => 'id', 'width' => '40'  ),
            array( 'title' => $_LANG['LOGIN'], 'field' => 'login', 'width' => '100', 'link' => '?view=users&do=edit&id=%id%', 'filter' => 12 ),
            array( 'title' => $_LANG['NICKNAME'], 'field' => 'nickname', 'width' => '', 'link' => '?view=users&do=edit&id=%id%', 'filter' => 12 ),
            array( 'title' => $_LANG['AD_RATING'], 'field' => array( 'rating', 'id' ), 'width' => '70', 'prc' => 'setRating' ),
            array( 'title' => $_LANG['AD_GROUP'], 'field' => 'group_id', 'width' => '110', 'prc' => 'cpGroupById', 'filter' => 1, 'filterlist' => cpGetList('cms_user_groups') ),
            array( 'title' => $_LANG['EMAIL'], 'field' => 'email', 'width' => '120' ),
            array( 'title' => $_LANG['AD_REGISTRATION_DATE'], 'field' => 'regdate', 'width' => '100' ),
            array( 'title' => $_LANG['AD_LAST_LOGIN'], 'field' => 'logdate', 'width' => '100' ),
            array( 'title' => $_LANG['AD_LAST_IP'], 'field' => 'last_ip', 'width' => '90', 'prc' => 'getIpLink' ),
            array( 'title' => $_LANG['AD_IS_LOCKED'], 'field' => 'is_locked', 'width' => '110', 'prc' => 'viewAct' ),
            array( 'title' => $_LANG['AD_IS_DELETED'], 'field' => 'is_deleted', 'width' => '80', 'prc' => 'viewDel' )
        );
        
        $actions = array(
            array( 'title' => $_LANG['AD_PROFILE'], 'icon' => 'profile.gif', 'link' => '/users/%login%' ),
            array( 'title' => $_LANG['AD_BANNED'], 'icon' => 'ban.gif', 'link' => '?view=userbanlist&do=add&to=%id%' ),
            array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_IS_USER_DELETE'], 'link' => '?view=users&do=delete&id=%id%' ),
            array( 'title' => $_LANG['AD_FOREVER_USER_DELETE'], 'icon' => 'off.gif', 'confirm' => $_LANG['AD_IF_FOREVER_USER_DELETE'], 'link' => '?view=users&do=delete_full&id=%id%' )
        );
        
        cpListTable('cms_users', $fields, $actions, '1=1', 'regdate DESC');
    }
    
    if ($do == 'rerating') {
        $user_id = cmsCore::request('user_id', 'int');
        if (!$user_id) { cmsCore::redirectBack(); }
        
        $rating = cmsUser::getRating($user_id);

        $user_sql = "UPDATE cms_users
                     SET rating = ". $rating ."
                     WHERE id = '". $user_id ."'";

        cmsCore::c('db')->query($user_sql);
        
        cmsCore::redirectBack();
    }
    
    if ($do == 'activate') {
        $user_ids = cmsCore::request('item', 'array_int');
        if (!$user_ids) { cmsCore::redirectBack(); }

        foreach ($user_ids as $user_id) {
            $code = cmsCore::c('db')->get_field('cms_users_activate', "user_id = '". $user_id ."'", 'code');

            $sql = "UPDATE cms_users SET is_locked = 0 WHERE id = '". $user_id ."'";
            cmsCore::c('db')->query($sql);

            $sql = "DELETE FROM cms_users_activate WHERE code = '". $code ."'";
            cmsCore::c('db')->query($sql);

            cmsCore::callEvent('USER_ACTIVATED', $user_id);

            // Регистрируем событие
            cmsActions::log(
                'add_user',
                array(
                    'object' => '',
                    'user_id' => $user_id,
                    'object_url' => '',
                    'object_id' => $user_id,
                    'target' => '',
                    'target_url' => '',
                    'target_id' => 0,
                    'description' => ''
                )
            );
        }
        
        cmsCore::redirectBack();
    }
    
    if ($do == 'delete') {
        if (!cmsCore::inRequest('item')) {
            if ($id >= 0) {
                $model->deleteUser($id);
            }
        } else {
            $model->deleteUsers(cmsCore::request('item', 'array_int', array()));
        }
        
        cmsCore::redirectBack();
    }

    if ($do == 'delete_full') {
        $model->deleteUser($id, true);
        cmsCore::redirectBack();
    }

    if ($do == 'submit' || $do == 'update') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $types = array(
            'login' => array( 'login', 'str', '' ),
            'nickname' => array( 'nickname', 'str', '', 'htmlspecialchars' ),
            'email' => array( 'email', 'email', '' ),
            'group_id' => array( 'group_id', 'int', 1 ),
            'is_locked' => array( 'is_locked', 'int', 0 ),
            'password' => array( 'pass', 'str', '', 'stripslashes' ),
            'pass2' => array( 'pass2', 'str', '', 'stripslashes' )
        );

        $items = cmsCore::getArrayFromRequest($types);

        $errors = false;

        // проверяем логин
        if (mb_strlen($items['login']) < 2 ||
                mb_strlen($items['login']) > 15 ||
                is_numeric($items['login']) ||
                !preg_match("/^([a-zA-Z0-9])+$/ui", $items['login'])) {
            cmsCore::addSessionMessage($_LANG['ERR_LOGIN'], 'error');
            $errors = true;
        }

        // проверяем пароль
        if ($do == 'submit') {
            if (!$items['password']) {
                cmsCore::addSessionMessage($_LANG['TYPE_PASS'], 'error');
                $errors = true;
            }
        }
        
        if ($items['password'] && !$items['pass2']) {
            cmsCore::addSessionMessage($_LANG['TYPE_PASS_TWICE'], 'error');
            $errors = true;
        }
        
        if ($items['password'] && $items['pass2'] && mb_strlen($items['password']) < 6) {
            cmsCore::addSessionMessage($_LANG['PASS_SHORT'], 'error');
            $errors = true;
        }
        
        if ($items['password'] && $items['pass2'] && $items['password'] != $items['pass2']) {
            cmsCore::addSessionMessage($_LANG['WRONG_PASS'], 'error');
            $errors = true;
        }

        // никнейм
        if (mb_strlen($items['nickname']) < 2) {
            cmsCore::addSessionMessage($_LANG['SHORT_NICKNAME'], 'error');
            $errors = true;
        }
        
        // Проверяем email
        if (!$items['email']) {
            cmsCore::addSessionMessage($_LANG['ERR_EMAIL'], 'error');
            $errors = true;
        }

        // проверяем есть ли такой пользователь
        if ($do == 'submit') {
            $user_exist = cmsCore::c('db')->get_fields('cms_users', "(login LIKE '". $items['login'] ."' OR email LIKE '". $items['email'] ."') AND is_deleted = 0", 'login');
            if ($user_exist) {
                if ($user_exist['login'] == $items['login']) {
                    cmsCore::addSessionMessage($_LANG['LOGIN'] .' "'. $items['login'] .'" '. $_LANG['IS_BUSY'], 'error');
                    $errors = true;
                } else {
                    cmsCore::addSessionMessage($_LANG['EMAIL_IS_BUSY'], 'error');
                    $errors = true;
                }
            }
        }

        if ($errors) {
            if ($do == 'submit') {
                cmsUser::sessionPut('items', $items);
            }
            cmsCore::redirectBack();
        }

        if ($do == 'submit') {
            $items['regdate']  = date('Y-m-d H:i:s');
            $items['logdate']  = date('Y-m-d H:i:s');
            $items['password'] = md5($items['password']);

            $items['user_id'] = cmsCore::c('db')->insert('cms_users', $items);
            if (!$items['user_id']) { cmsCore::error404(); }

            cmsCore::c('db')->insert('cms_user_profiles', $items);

            cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
            cmsCore::redirect('?view=users');
        } else {
            // главного админа может редактировать только он сам
            if ($id == 1 && cmsCore::c('user')->id != $id) {
                cmsCore::error404();
            }
            
            if ($id == 1) {
                unset($items['group_id']);
                unset($items['is_locked']);
            }

            if (!$items['password']) {
                unset($items['password']);
            } else {
                $items['password'] = md5($items['password']);
            }

            cmsCore::c('db')->update('cms_users', $items, $id);

            cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
            if (empty($_SESSION['editlist'])) {
                cmsCore::redirect('index.php?view=users');
            } else {
                cmsCore::redirect('index.php?view=users&do=edit');
            }
        }
    }

    if ($do == 'edit' || $do == 'add') {
        $toolmenu = array(
            array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.addform.submit();' ),
            array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => 'javascript:history.go(-1);' )
        );

        cpToolMenu($toolmenu);

        if ($do == 'edit') {
            if (cmsCore::inRequest('multiple')){
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

            $mod = cmsCore::c('db')->get_fields('cms_users', "id = '". $item_id ."'", '*');
            if (!$mod) { cmsCore::error404(); }

            echo '<h3>'. $_LANG['AD_USER_EDIT'] .' '. $ostatok .'</h3>';
            cpAddPathway($mod['nickname']);

        } else {
            $mod = cmsUser::sessionGet('items');
            if ($mod) { cmsUser::sessionDel('items'); }
            cpAddPathway($_LANG['AD_USER_ADD']);
        }
        
        cmsCore::c('page')->addHeadJS('components/registration/js/check.js');
?>
<form action="index.php?view=users" method="post" enctype="multipart/form-data" name="addform" id="addform">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:500px;">
        <div class="form-group">
            <label><?php echo $_LANG['LOGIN']; ?>:</label>
            <input type="text" id="logininput" class="form-control" name="login" value="<?php echo cmsCore::getArrVal($mod, 'login', ''); ?>" onchange="checkLogin()" />
            <?php if ($do == 'edit') { echo '<div class="help-block" style="text-align:right;"><a target="_blank" href="/users/'. $mod['login'] .'" title="'. $_LANG['AD_USER_PROFILE'] .'">'. $_LANG['AD_USER_PROFILE'] .'</a></div>'; } ?>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['NICKNAME']; ?>:</label>
            <input type="text" id="login" class="form-control" name="nickname" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'nickname', '')); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['EMAIL']; ?>:</label>
            <input type="text" id="nickname" class="form-control" name="email" value="<?php echo cmsCore::getArrVal($mod, 'email', ''); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php if ($do == 'edit') { echo $_LANG['AD_NEW_PASS']; } else { echo $_LANG['PASS']; } ?></label>
            <input type="password" id="pass" class="form-control" name="pass" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['REPEAT_PASS']; ?>:</label>
            <input type="password" id="pass2" class="form-control" name="pass2" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_GROUP']; ?>:</label>
            <select id="group_id" class="form-control" name="group_id">
                <?php
                    echo $inCore->getListItems('cms_user_groups', cmsCore::getArrVal($mod, 'group_id', 0));
                ?>
            </select>
            <?php if ($do == 'edit') { echo '<div class="help-block" style="text-align:right;"><a target="_blank" href="?view=usergroups&do=edit&id='. $mod['group_id'] .'">'. $_LANG['EDIT'] .'</a></div>'; } ?>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_IF_ACCAUNT_LOCK']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if ($mod['is_locked']) { echo 'active'; } ?>">
                    <input type="radio" name="is_locked" <?php if ($mod['is_locked']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!$mod['is_locked']) { echo 'active'; } ?>">
                    <input type="radio" name="is_locked" <?php if (!$mod['is_locked']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
    </div>

    <div>
        <?php if ($do == 'edit') { ?>
            <input type="hidden" name="do" value="update" />
            <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <?php } else { ?>
            <input type="hidden" name="do" value="submit" />
            <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['AD_USER_ADD']; ?>" />
        <?php } ?>
        <input type="button" class="btn btn-default" name="back2" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();" />

        <?php
            if ($do == 'edit') {
                echo '<input type="hidden" name="id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>
<?php
   }
}