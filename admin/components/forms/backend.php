<?php
if (!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
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

    if ($dir > 0) {
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

if (in_array($opt, array('submit', 'update'))) {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $item['title']       = cmsCore::request('title', 'str', $_LANG['AD_FORM_UNTITLED']);
    $item['description'] = cmsCore::c('db')->escape_string(cmsCore::request('description', 'html', ''));
    $item['sendto']      = cmsCore::request('sendto', 'str', '');
    $item['email']       = cmsCore::request('email', 'email', '');
    $item['user_id']     = cmsCore::request('user_id', 'int', 0);
    $item['form_action'] = cmsCore::request('form_action', 'str', '/forms/process');
    $item['only_fields'] = cmsCore::request('only_fields', 'int', 0);
    $item['showtitle']   = cmsCore::request('showtitle', 'int', 0);
    $item['tpl']         = cmsCore::request('tpl', 'str', 'form');

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
    
    cpListTable('cms_forms', $fields, $actions, '', 'id DESC');
}

if (in_array($opt, array('add', 'edit'))) {
    if ($opt == 'add') {
        cpAddPathway($_LANG['AD_NEW_FORM']);

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
    
    $tpl = cmsCore::c('page')->initTemplate('components', 'forms_add')->
        assign('opt', $opt)->
        assign('users_opt', $inCore->getListItems('cms_users', cmsCore::getArrVal($mod, 'user_id', 0), 'nickname', 'ASC', 'is_deleted=0 AND is_locked=0', 'id', 'nickname'))->
        assign('mod', $mod);
    
    if ($opt == 'edit') {
        $last_order = 1 + cmsCore::c('db')->get_field('cms_form_fields', "form_id='". $mod['id'] ."' ORDER BY ordering DESC", 'ordering');
        $tpl->assign('last_order', $last_order)->
            assign('field', $field)->
            assign('form_html', cmsForm::displayForm($item_id, array(), true));
    }
    
    $tpl->display();
}