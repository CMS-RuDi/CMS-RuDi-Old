<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.4                               //
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

    cmsCore::c('page')->setAdminTitle($_LANG['AD_USERS_GROUP']);
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
?>
<form id="addform" name="addform" method="post" action="index.php?view=usergroups">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:650px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_GROUP_NAME'];?>:</label>
            <input type="text" class="form-control" name="title" size="30" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'title', '')); ?>" />
            <div class="help-block"><?php echo $_LANG['AD_VIEW_SITE']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALIAS'];?>:</label>
            <input type="text" class="form-control" name="alias" size="30" value="<?php echo cmsCore::getArrVal($mod, 'alias', ''); ?>" />
            <?php if ($do == 'edit') { ?>
                <div class="help-block"><?php echo $_LANG['AD_DONT_CHANGE']; ?></div>
            <?php } ?>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_IF_ADMIN'];?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if (cmsCore::getArrVal($mod, 'is_admin')) { echo 'active'; } ?>" onclick="$('#accesstable').hide();$('#admin_accesstable').show();">
                    <input type="radio" name="is_admin" <?php if (cmsCore::getArrVal($mod, 'is_admin')) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'is_admin')) { echo 'active'; } ?>" onclick="$('#accesstable').show();$('#admin_accesstable').hide();">
                    <input type="radio" name="is_admin" <?php if (!cmsCore::getArrVal($mod, 'is_admin')) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <hr>
        
        <div id="admin_accesstable" <?php if (!cmsCore::getArrVal($mod, 'is_admin')) { echo 'style="display:none;"'; } ?>>
            <div class="form-group">
                <label><?php echo $_LANG['AD_AVAILABLE_SECTIONS']; ?></label>
                
                <div style="margin-left:50px;">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_menu" name="access[]" value="admin/menu" <?php if (isset($mod['access'])) { if (in_array('admin/menu', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_MENU_CONTROL']; ?>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_modules" name="access[]" value="admin/modules" <?php if (isset($mod['access'])) { if (in_array('admin/modules', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_MODULES_CONTROL']; ?>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_content" name="access[]" value="admin/content" <?php if (isset($mod['access'])) { if (in_array('admin/content', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_CONTENTS_CONTROL']; ?>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_plugins" name="access[]" value="admin/plugins" <?php if (isset($mod['access'])) { if (in_array('admin/filters', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_CONTENTS_CONTROL']; ?>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_filters" name="access[]" value="admin/filters" <?php if (isset($mod['access'])) { if (in_array('admin/filters', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_FILTERS_CONTROL']; ?>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_components" name="access[]" value="admin/components" <?php if (isset($mod['access'])) { if (in_array('admin/components', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_COMPONENTS_CONTROL']; ?>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_users" name="access[]" value="admin/users" <?php if (isset($mod['access'])) { if (in_array('admin/users', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_USERS_CONTROL']; ?>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_config" name="access[]" value="admin/config" <?php if (isset($mod['access'])) { if (in_array('admin/config', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_SETTINGS_CONTROL']; ?>
                        </label>
                    </div>
                    
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_config" name="access[]" value="admin/tickets" <?php if (isset($mod['access'])) { if (in_array('admin/tickets', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_TICKETS_CONTROL']; ?>
                        </label>
                    </div>
                </div>
                
                <div class="help-block"><?php echo $_LANG['AD_ALL_SECTIONS']; ?></div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_COMPONENTS_SETTINGS_FREE']; ?></label>
                
                <div style="margin-left:50px;">
                    <?php
                        $coms = cmsCore::getInstance()->getAllComponents();
                        foreach ($coms as $com) {
                            if (!file_exists(PATH.'/admin/components/'. $com['link'] .'/backend.php')) { continue; }
                    ?>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="admin_com_<?php echo $com['link']; ?>" name="access[]" value="admin/com_<?php echo $com['link']; ?>" <?php if (isset($mod['access'])) { if (in_array('admin/com_'. $com['link'], $mod['access'])) { echo 'checked="checked"'; } } ?> />
                                <?php echo $com['title']; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
                
                <div class="help-block"><?php echo $_LANG['AD_COMPONENTS_SETTINGS_ON']; ?></div>
            </div>
        </div>
        
        <div id="accesstable" <?php if (cmsCore::getArrVal($mod, 'is_admin')) { echo 'style="display:none;"'; } ?>>
            <div class="form-group">
                <label><?php echo $_LANG['AD_GROUP_RULE'];?></label>
                
                <div style="margin-left:50px;">
                    <?php
                        $sql = "SELECT * FROM cms_user_groups_access ORDER BY access_type";
                        $res = cmsCore::c('db')->query($sql);

                        while ($ga = cmsCore::c('db')->fetch_assoc($res)) {
                            if ($mod['alias'] == 'guest' && $ga['hide_for_guest']) { continue; }
                    ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="<?php echo str_replace('/', '_', $ga['access_type']); ?>" name="access[]" value="<?php echo $ga['access_type']; ?>" <?php if (isset($mod['access'])) { if (in_array($ga['access_type'], $mod['access'])) { echo 'checked="checked"'; } } ?> />
                                <?php echo $ga['access_name']; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php if ($do == 'add') { echo $_LANG['AD_CREATE_GROUP']; } else { echo $_LANG['SAVE']; } ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL'];?>" onclick="window.history.back();"/>
        
        <input type="hidden" name="do" value="<?php if ($do == 'add') { echo 'submit'; } else { echo 'update'; } ?>" />
        <?php
            if ($do == 'edit') {
                echo '<input name="id" type="hidden" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>
<?php
   }
}