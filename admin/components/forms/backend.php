<?php
if (!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
/* * **************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.5                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/* * **************************************************************************/
function autoOrder($form_id) {
    $sql = "SELECT * FROM cms_form_fields WHERE form_id = '". $form_id ."' ORDER BY ordering";
    $rs  = cmsCore::c('db')->query($sql);

    if (cmsCore::c('db')->num_rows($rs)) {
        $ord = 1;
        while ($item = cmsCore::c('db')->fetch_assoc($rs)) {
            cmsCore::c('db')->query("UPDATE cms_form_fields SET ordering = ". $ord ." WHERE id= '". $item['id'] ."'");
            $ord += 1;
        }
    }
    return true;
}

function moveField($id, $form_id, $dir) {
    $sign = $dir > 0 ? '+' : '-';

    $current = cmsCore::c('db')->get_field('cms_form_fields', "id='". $id ."'", 'ordering');
    if ($current === false) { return false; }

    if ($dir>0) {
        $sql = "UPDATE cms_form_fields
                SET ordering = ordering-1
                WHERE form_id='". $form_id ."' AND ordering = (". $current ."+1)
                LIMIT 1";
        cmsCore::c('db')->query($sql);
    }
    
    if ($dir < 0) {
        if ($current == 1) { return false; }

        $sql = "UPDATE cms_form_fields
                SET ordering = ordering+1
                WHERE form_id='". $form_id ."' AND ordering = (". $current ."-1)
                LIMIT 1";
        cmsCore::c('db')->query($sql);
    }

    $sql    = "UPDATE cms_form_fields
               SET ordering = ordering ". $sign ." 1
               WHERE id='". $id ."'";
    cmsCore::c('db')->query($sql);

    return true;
}

cmsCore::c('page')->addHeadJS('admin/js/forms.js');

$opt = cmsCore::request('opt', 'str', 'list');

$toolmenu = array(
    array( 'icon' => 'newform.gif', 'title' => $_LANG['AD_NEW_FORM'], 'link' => '?view=components&do=config&id='. $id .'&opt=add' ),
    array( 'icon' => 'listforms.gif', 'title' => $_LANG['AD_FORMS'], 'link' => '?view=components&do=config&id='. $id .'&opt=list' )
);

cpToolMenu($toolmenu);

cmsCore::loadClass('form');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($opt == 'up_field') {
    moveField(cmsCore::request('item_id', 'int'), cmsCore::request('form_id', 'int'), -1);
    cmsCore::redirectBack();
}

if ($opt == 'down_field') {
    moveField(cmsCore::request('item_id', 'int'), cmsCore::request('form_id', 'int'), 1);
    cmsCore::redirectBack();
}

if ($opt == 'del_field') {
    $item_id = cmsCore::request('item_id', 'int');
    $form_id = cmsCore::request('form_id', 'int');

    cmsCore::c('db')->delete('cms_form_fields', "id = '". $item_id ."'", 1);

    autoOrder($form_id);

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS']);
    cmsCore::redirectBack();
}

if (in_array($opt, array('add_field', 'update_field'))) {
    $item['kind']        = cmsCore::request('kind', 'str', '');
    $item['title']       = cmsCore::request('f_title', 'str', 'NO_TITLE');
    $item['description'] = cmsCore::request('f_description', 'str', '');
    $item['ordering']    = cmsCore::request('f_order', 'int');
    $item['form_id']     = cmsCore::request('form_id', 'int');
    $item['mustbe']      = cmsCore::request('mustbe', 'int');
    
    $item['config'] = array();
    $item['config']['text_is_link'] = cmsCore::request('text_is_link', 'int');
    $item['config']['text_link_prefix'] = cmsCore::request('text_link_prefix', 'str', '');
    $item['config']['max'] = cmsCore::request('text_max', 'int');

    switch ($item['kind']) {
        case 'text':
            $item['config']['size']    = cmsCore::request('f_text_size', 'int');
            break;
        case 'link':
            $item['config']['size']    = cmsCore::request('f_link_size', 'int');
            break;
        case 'textarea':
            $item['config']['size']    = cmsCore::request('f_ta_size', 'int');
            $item['config']['rows']    = cmsCore::request('f_ta_rows', 'int');
            $item['config']['default'] = cmsCore::request('f_ta_default', 'str', '');
            break;
        case 'checkbox':
            $item['config']['checked'] = cmsCore::request('f_checked', 'int');
            break;
        case 'radiogroup':
            $item['config']['items'] = cmsCore::request('f_rg_list', 'str', '');
            break;
        case 'list':
            $item['config']['items'] = cmsCore::request('f_list_list', 'str', '');
            $item['config']['size']  = cmsCore::request('f_list_size', 'int');
            break;
        case 'menu':
            $item['config']['items'] = cmsCore::request('f_menu_list', 'str', '');
            $item['config']['size']  = cmsCore::request('f_menu_size', 'int');
            break;
        case 'file':
            $exts = cmsCore::request('f_file_ext', 'str', '');

            while (mb_strpos($exts, 'htm') ||
                   mb_strpos($exts, 'php') ||
                   mb_strpos($exts, 'ht')) {
                $exts  = str_replace(array('htm','php','ht'), '', mb_strtolower($exts));
            }

            $item['config']['ext']   = str_replace(' ', '', $exts);
            $item['config']['size']  = cmsCore::request('f_file_size', 'int');
            break;
    }

    $item['config'] = cmsCore::c('db')->escape_string(cmsCore::arrayToYaml($item['config']));

    if ($opt == 'add_field') {
        cmsCore::c('db')->insert('cms_form_fields', cmsCore::callEvent('ADD_FORM_FIELD', $item));
    } else {
        cmsCore::c('db')->update('cms_form_fields', cmsCore::callEvent('UPDATE_FORM_FIELD', $item), cmsCore::request('field_id', 'int'));
    }

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS']);
    cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=edit&item_id='.$item['form_id']);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (in_array($opt, array('submit', 'update'))) {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $item['title']       = cmsCore::request('title', 'str', $_LANG['AD_FORM_UNTITLED']);
    $item['description'] = cmsCore::c('db')->escape_string(cmsCore::request('description', 'html', ''));
    $item['sendto']  = cmsCore::request('sendto', 'str', '');
    $item['email']   = cmsCore::request('email', 'email', '');
    $item['user_id'] = cmsCore::request('user_id', 'int', 0);
    $item['form_action'] = cmsCore::request('form_action', 'str', '/forms/process');
    $item['only_fields'] = cmsCore::request('only_fields', 'int', 0);
    $item['showtitle'] = cmsCore::request('showtitle', 'int', 0);
    $item['tpl']       = cmsCore::request('tpl', 'str', 'form');

    if ($opt == 'submit') {
        $form_id = cmsCore::c('db')->insert('cms_forms', cmsCore::callEvent('ADD_FORM', $item));
        cmsCore::addSessionMessage($_LANG['AD_FORM_SUCCESFULL_CREATED']);
    } else {
        $form_id = cmsCore::request('item_id', 'int');

        cmsCore::c('db')->update('cms_forms', cmsCore::callEvent('UPDATE_FORM', $item), $form_id);
        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'].'.');
    }

    cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=edit&item_id='. $form_id);
}

if ($opt == 'delete') {
    $item_id = cmsCore::request('item_id', 'int');
    $mod = cmsCore::c('db')->get_fields('cms_forms', "id = '". $item_id ."'", '*');
    if (!$mod){ cmsCore::error404(); }

    cmsCore::callEvent('DELETE_FORM', $item_id);

    cmsCore::c('db')->delete('cms_forms', "id = '". $item_id ."'", 1);

    cmsCore::c('db')->delete('cms_form_fields', "form_id = '". $item_id ."'");

    files_remove_directory(PATH.'/upload/forms/'. $item_id);

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'].'.');

    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list');
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($opt == 'list') {
    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'link' => '?view=components&do=config&id='. $id .'&opt=edit&item_id=%id%'),
        array( 'title' => $_LANG['AD_E-MAIL'], 'field' => 'email', 'width' => '150')
    );

    $actions = array(
        array( 'title'=> $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit&item_id=%id%' ),
        array( 'title'=> $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_FORM_DELETE'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete&item_id=%id%' )
    );

    cpListTable('cms_forms', $fields, $actions);
}

if (in_array($opt, array('add', 'edit'))) {
    if ($opt == 'add') {
        cpAddPathway($_LANG['AD_NEW_FORM']);
        echo '<h3>'. $_LANG['AD_NEW_FORM'] .'</h3>';

        $mod = array(
            'showtitle' => 1,
            'form_action' => '/forms/process',
            'tpl' => 'form',
            'only_fields' => 0
        );
    } else {
        $item_id  = cmsCore::request('item_id', 'int');
        $field_id = cmsCore::request('field_id', 'int');

        $mod = cmsCore::c('db')->get_fields('cms_forms', "id = '". $item_id ."'", '*');

        $field = cmsCore::c('db')->get_fields('cms_form_fields', "id='". $field_id ."'", '*');
        if ($field) {
            $field['config'] = cmsCore::yamlToArray($field['config']);
        }

        echo '<h3>'. $_LANG['AD_FORM'] .': '. $mod['title'] .'</h3>';
        cpAddPathway($mod['title']);
    }
?>
<div style="width:800px;">
<?php
    if ($opt == 'edit') {
?>
<div class="uitabs">
    <ul>
        <li><a href="#tab_form_desc"><?php echo $_LANG['AD_FORM_PROPERTIES']; ?></a></li>
        <li><a href="#tab_form_edit"><?php echo $_LANG['AD_FIELDS']; ?></a></li>
    </ul>
<?php
    }
?>
    <div id="tab_form_desc">
        <form id="addform" name="addform" method="post" action="index.php?view=components&do=config&id=<?php echo $id; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_FORM_NAME']; ?>:</label>
                <input type="text" id="title" class="form-control" name="title" size="30" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'title', '')); ?>" />
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_FORM_DESTINATION']; ?>:</label>
                <select id="sendto" class="form-control" name="sendto" onChange="toggleSendTo()">
                    <option value="mail" <?php if (cmsCore::getArrVal($mod, 'sendto', 'mail') == 'mail') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_EMAIL_ADDRESS']; ?></option>
                    <option value="user" <?php if (cmsCore::getArrVal($mod, 'sendto', 'mail') == 'user') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PERSON_MESS']; ?></option>
                </select>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_VIEW_FORM_TITLE']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="float:right;">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'showtitle', false)) { echo 'active'; } ?>">
                        <input type="radio" name="showtitle" <?php if(cmsCore::getArrVal($mod, 'showtitle', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'showtitle', false)) { echo 'active'; } ?>">
                        <input type="radio" name="showtitle" <?php if (!cmsCore::getArrVal($mod, 'showtitle', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_FORM_ACTION']; ?>:</label>
                <input type="text" class="form-control" name="form_action" size="30" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'form_action', '')); ?>" />
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_FILED_ONLY']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="float:right;">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'only_fields', false)) { echo 'active'; } ?>">
                        <input type="radio" name="only_fields" <?php if(cmsCore::getArrVal($mod, 'only_fields', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'only_fields', false)) { echo 'active'; } ?>">
                        <input type="radio" name="only_fields" <?php if (!cmsCore::getArrVal($mod, 'only_fields', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_FORM_TPL']; ?>:</label>
                <input type="text" class="form-control" name="tpl" size="30" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'tpl', '')); ?>" />
                <div class="help-block"><?php echo $_LANG['AD_FORM_TPL_HINT']; ?></div>
            </div>
            
            <div id="sendto_mail" class="form-group" style="display:<?php if (cmsCore::getArrVal($mod, 'sendto', 'mail') == 'mail') { echo 'block'; } else { echo 'none'; } ?>">
                <label><span class="fa fa-mail-forward"></span><?php echo $_LANG['AD_E-MAIL_ADDR']; ?>:</label>
                <input type="text" id="email" class="form-control" name="email" size="30" value="<?php echo cmsCore::getArrVal($mod, 'email', ''); ?>" />
                <div class="help-block"><?php echo $_LANG['AD_E-MAIL_ADDR_HINT']; ?></div>
            </div>
            
            <div id="sendto_user" class="form-group" style="display:<?php if (cmsCore::getArrVal($mod, 'sendto', 'mail') == 'user') { echo 'block'; } else { echo 'none'; } ?>">
                <label><span class="fa fa-male"></span><?php echo $_LANG['AD_RECIPIENT']; ?>:</label>
                <select id="user_id" class="form-control" name="user_id">
                    <?php
                        echo $inCore->getListItems('cms_users', cmsCore::getArrVal($mod, 'user_id', 0), 'nickname', 'ASC', 'is_deleted=0 AND is_locked=0', 'id', 'nickname');
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_FORM_EXPLANT']; ?>:</label>
                <?php $inCore->insertEditor('description', $mod['description'], '280', '100%'); ?>
            </div>
            
            <?php if ($opt == 'add') {
                echo '<p><b>'. $_LANG['AD_NOTE'] .': </b>'. $_LANG['AD_AFTER_CREATE'] .'. </p>';
            } else {
                echo '<p><b>'. $_LANG['AD_NOTE'] .': </b>' . $_LANG['AD_TO_INSERT'];
            }
            ?>

            <div>
                <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
                <input type="hidden" id="do" name="opt" value="<?php if ($opt == 'add') { echo 'submit'; } else { echo 'update'; } ?>" />
            <?php
            if ($opt == 'edit') {
                echo '<input type="hidden" name="item_id" value="'. $mod['id'] .'" />';
            }
            ?>
            </div>
        </form>
    </div>
    
<?php
    if ($opt == 'edit') {
?>
    <div id="tab_form_edit">
        <?php $last_order = 1 + cmsCore::c('db')->get_field('cms_form_fields', "form_id='{$mod['id']}' ORDER BY ordering DESC", 'ordering'); ?>
        <table class="table" width="750">
            <tr>
                <td width="300" valign="top">
                    <h4 style="border-bottom:solid 1px black; font-size: 14px; margin-bottom: 10px"><b><?php if(!@$field){ ?><?php echo $_LANG['AD_FIELD_ADD']; ?><?php } else { ?><?php echo $_LANG['AD_FIELD_EDIT']; ?><?php } ?></b></h4>
                    <form id="fieldform" name="fieldform" method="post" action="index.php?view=components&do=config&id=<?php echo $id; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
                        <input type="hidden" name="opt" value="<?php if(!@$field){ ?>add_field<?php } else { ?>update_field<?php } ?>"/>
                        <input name="form_id" type="hidden" id="form_id" value="<?php echo @$mod['id'] ?>"/>
                        <input name="field_id" type="hidden" value="<?php echo @$field['id'] ?>"/>
                        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="100"><?php echo $_LANG['AD_FIELD_TYPE']; ?>:</td>
                                <td>
                                    <select name="kind" id="kind" onchange="show()">
                                        <option value="text" <?php if (@$field['kind'] == 'text' || !@$field['kind']) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TYPE_TEXT']; ?></option>
                                        <option value="link" <?php if (@$field['kind'] == 'link') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TYPE_URL']; ?></option>
                                        <option value="textarea" <?php if (@$field['kind'] == 'textarea') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TYPE_MILTILINE']; ?></option>
                                        <option value="checkbox" <?php if (@$field['kind'] == 'checkbox') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TYPE_YES_NO']; ?></option>
                                        <option value="radiogroup" <?php if (@$field['kind'] == 'radiogroup') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TYPE_GROUP_OPTIONS'] ; ?></option>
                                        <option value="list" <?php if (@$field['kind'] == 'list') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TYPE_DROP_DOWN']; ?></option>
                                        <option value="menu" <?php if (@$field['kind'] == 'menu') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TYPE_VISIBLE']; ?></option>
                                        <option value="file" <?php if (@$field['kind'] == 'file') { echo 'selected="selected"'; } ?>><?php echo $_LANG['FILE']; ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $_LANG['AD_TITLE']; ?>:</td>
                                <td><input name="f_title" type="text" id="f_title" size="25" value="<?php echo htmlspecialchars(@$field['title']) ?>" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $_LANG['DESCRIPTION']; ?>:</td>
                                <td><input name="f_description" type="text" id="f_description" size="25" value="<?php echo htmlspecialchars(@$field['description']) ?>" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $_LANG['AD_FIELD_ORDER']; ?>:</td>
                                <td><input class="uispin" name="f_order" type="text" id="f_order" value="<?php if(!@$field) { echo $last_order; } else { echo @$field['ordering']; } ?>" size="6" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $_LANG['AD_FIELD_FILLING']; ?>:</td>
                                <td><select name="mustbe" id="mustbe">
                                        <option value="1" <?php if (@$field['mustbe']) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_NECESSARILY']; ?></option>
                                        <option value="0" <?php if (!@$field['mustbe']) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_NOT_NECESSARILY']; ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="text_field">
                                <td><?php echo $_LANG['AD_VALUE_LINK']; ?>?</td>
                                <td>
                                    <label><input name="text_is_link"
                                                  onclick="$('#text_link_prefix').show();"
                                                  type="radio" value="1" <?php if (@$field['config']['text_is_link']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['YES']; ?></label>
                                    <label><input name="text_is_link"
                                                  onclick="$('#text_link_prefix').hide();"
                                                  type="radio" value="0" <?php if (!@$field['config']['text_is_link']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['NO']; ?></label>
                                </td>
                            </tr>
                            <tr id="text_link_prefix" <?php if(!@$field['config']['text_is_link']) { echo 'style="display:none"'; } ?>>
                                <td><?php echo $_LANG['AD_LINK_PREFIX']; ?>:</td>
                                <td><input name="text_link_prefix" type="text" size="25" value="<?php echo (@$field['config']['text_link_prefix'] ? $field['config']['text_link_prefix'] : '/users/hobby/'); ?>" /></td>
                            </tr>
                            <tr class="text_field">
                                <td><?php echo $_LANG['AD_MAXIMUM_LENGTH']; ?>:</td>
                                <td><input class="uispin" name="text_max" type="text" size="6" value="<?php echo (isset($field['config']['max']) ? $field['config']['max'] : 300) ?>" /> <?php echo $_LANG['AD_CHARACTERS']; ?> </td>
                            </tr>
                        </table>

                        <div id="kind_text">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="100"><?php echo $_LANG['AD_WIDTH']; ?>:</td>
                                    <td><input class="uispin" name="f_text_size" type="text" id="f_text_size" value="<?php echo (@$field['config']['size'] ? $field['config']['size'] : 160) ?>" size="6" />  <?php echo $_LANG['AD_PX'] ; ?> </td>
                                </tr>
                            </table>
                        </div>
                        <div id="kind_link" style="display:none">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="100"><?php echo $_LANG['AD_WIDTH']; ?>:</td>
                                    <td><input class="uispin" name="f_link_size" type="text" id="f_text_size" value="<?php echo (@$field['config']['size'] ? $field['config']['size'] : 160) ?>" size="6" />  <?php echo $_LANG['AD_PX'] ; ?> </td>
                                </tr>
                            </table>
                        </div>
                        <div id="kind_textarea" style="display:none">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="100"><?php echo $_LANG['AD_WIDTH']; ?>:</td>
                                    <td><input class="uispin" name="f_ta_size" type="text" id="f_ta_size" value="<?php echo (@$field['config']['size'] ? $field['config']['size'] : 160) ?>" size="6" /> <?php echo $_LANG['AD_PX'] ; ?> </td>
                                </tr>
                                <tr>
                                    <td><?php echo $_LANG['AD_STRINGS']; ?>:</td>
                                    <td><input class="uispin" name="f_ta_rows" type="text" id="f_ta_rows" value="<?php echo (@$field['config']['rows'] ? $field['config']['rows'] : 5) ?>" size="6" /></td>
                                </tr>
                            </table>
                        </div>
                        <div id="kind_checkbox" style="display:none">
                            <div id="div" >
                                <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td width="100"><?php echo $_LANG['AD_MARK']; ?>:</td>
                                        <td><select name="f_checked" id="f_checked">
                                                <option value="1" <?php if (@$field['config']['checked']) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_MARKED']; ?></option>
                                                <option value="0" <?php if (!@$field['config']['checked']) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_NOT_MARKED']; ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div id="kind_radiogroup" style="display:none">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="100"><?php echo $_LANG['AD_ELEMENTS']; ?>:<br />
                                        <small><?php echo $_LANG['AD_THROUTH']; ?> "<b>/</b>"</small> </td>
                                    <td><textarea name="f_rg_list" cols="20" rows="5" id="f_rg_list"><?php echo htmlspecialchars(@$field['config']['items']) ?></textarea></td>
                                </tr>
                            </table>
                        </div>
                        <div id="kind_list" style="display:none">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="100"><?php echo $_LANG['AD_ELEMENTS']; ?>:<br />
                                        <small><?php echo $_LANG['AD_THROUTH']; ?> "<b>/</b>"</small> </td>
                                    <td><textarea name="f_list_list" cols="20" rows="5" id="f_list_list"><?php echo htmlspecialchars(@$field['config']['items']) ?></textarea></td>
                                </tr>
                                <tr>
                                    <td><?php echo $_LANG['AD_WIDTH']; ?>:</td>
                                    <td><input class="uispin" name="f_list_size" type="text" id="f_ta_size" value="<?php echo (@$field['config']['size'] ? $field['config']['size'] : 160) ?>" size="6" /> <?php echo $_LANG['AD_PX']; ?> </td>
                                </tr>
                            </table>
                        </div>
                        <div id="kind_menu" style="display:none">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="100"><?php echo $_LANG['AD_ELEMENTS']; ?>:<br />
                                        <small><?php echo $_LANG['AD_THROUTH']; ?> "<b>/</b>"</small> </td>
                                    <td><textarea name="f_menu_list" cols="20" rows="5" id="f_menu_list"><?php echo htmlspecialchars(@$field['config']['items']) ?></textarea></td>
                                </tr>
                                <tr>
                                    <td><?php echo $_LANG['AD_WIDTH']; ?>:</td>
                                    <td><input class="uispin" name="f_menu_size" type="text" id="f_ta_size" value="<?php echo (@$field['config']['size'] ? $field['config']['size'] : 160) ?>" size="6" /> <?php echo $_LANG['AD_PX']; ?> </td>
                                </tr>
                            </table>
                        </div>
                        <div id="kind_file" style="display:none">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="100"><?php echo $_LANG['AD_EXT']; ?>:<br />
                                        <small><?php echo $_LANG['AD_EXT_HINT']; ?></small></td>
                                    <td><input name="f_file_ext" type="text" value="<?php echo @$field['config']['ext']; ?>" size="25" /></td>
                                </tr>
                                <tr>
                                    <td><?php echo $_LANG['AD_WIDTH']; ?>:</td>
                                    <td><input class="uispin" name="f_file_size" type="text" id="f_file_size" value="<?php echo (@$field['config']['size'] ? $field['config']['size'] : 160) ?>" size="6" /> <?php echo $_LANG['AD_PX']; ?> </td>
                                </tr>
                            </table>
                        </div>

                        <p>
                            <input type="submit" name="Submit" value="<?php if(!@$field){  echo $_LANG['AD_FIELD_ADD']; } else { echo $_LANG['AD_FIELD_SAVE']; } ?>" />
                        </p>
                    </form>

                </td>
                <td width="440" valign="top"><h4 style="border-bottom:solid 1px black;font-size: 14px; margin-bottom: 5px"><b><?php echo $_LANG['AD_PREVIEV']; ?> </b></h4>
                    <?php echo cmsForm::displayForm($item_id, array(), true); ?>
                </td>
            </tr>
        </table>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".uitabs").tabs({active: 1});
            });
        </script>
    </div>
<?php
    }
?>
</div>
</div>
<?php
}