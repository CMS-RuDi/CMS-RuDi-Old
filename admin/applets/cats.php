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

function createMenuItem($menu, $id, $title) {
    $inCore = cmsCore::getInstance();
    $ns = $inCore->nestedSetsInit('cms_menu');
    
    cmsCore::c('db')->update(
        'cms_menu',
        array(
            'menu' => $menu,
            'title' => $title,
            'link' => $inCore->getMenuLink('category', $id),
            'linktype' => 'category',
            'linkid' => $id,
            'target' => '_self',
            'published' => '1',
            'template' => '0',
            'access_list' => '',
            'iconurl' => ''
        ),
        $ns->AddNode(cmsCore::c('db')->getNsRootCatId('cms_menu'))
    );

    return true;
}

function applet_cats() {
    $inCore = cmsCore::getInstance();

    global $_LANG;

    cmsCore::c('page')->setTitle($_LANG['AD_ARTICLES']);
    cpAddPathway($_LANG['AD_ARTICLES'], 'index.php?view=tree');

    $do = cmsCore::request('do', 'str', 'add');
    $id = cmsCore::request('id', 'int', -1);

    define('IS_BILLING', $inCore->isComponentInstalled('billing'));
    if (IS_BILLING) { cmsCore::loadClass('billing'); }

    if ($do == 'delete') {
        $is_with_content = cmsCore::inRequest('content');
        cmsCore::m('content')->deleteCategory($id, $is_with_content);
        
        cmsCore::addSessionMessage(($is_with_content ? $_LANG['AD_CATEGORY_REMOVED'] : $_LANG['AD_CATEGORY_REMOVED_NOT_ARTICLE']), 'success');
        cmsCore::redirect('?view=tree');
    }

    if ($do == 'update') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
        
        if (cmsCore::inRequest('id')) {
            $category['id']          = cmsCore::request('id', 'int', 0);
            $category['title']       = cmsCore::request('title', 'str', $_LANG['AD_SECTION_UNTITLED']);
            $category['parent_id']   = cmsCore::request('parent_id', 'int');
            $category['description'] = cmsCore::request('description', 'html', '');
            $category['description'] = cmsCore::c('db')->escape_string($category['description']);
            $category['published']   = cmsCore::request('published', 'int', 0);
            $category['showdate']    = cmsCore::request('showdate', 'int', 0);
            $category['showcomm']    = cmsCore::request('showcomm', 'int', 0);
            $category['orderby']     = cmsCore::request('orderby', 'str', '');
            $category['orderto']     = cmsCore::request('orderto', 'str', '');
            $category['modgrp_id']   = cmsCore::request('modgrp_id', 'int', 0);
            $category['maxcols']     = cmsCore::request('maxcols', 'int', 0);
            $category['showtags']    = cmsCore::request('showtags', 'int', 0);
            $category['showrss']     = cmsCore::request('showrss', 'int', 0);
            $category['showdesc']    = cmsCore::request('showdesc', 'int', 0);
            $category['is_public']   = cmsCore::request('is_public', 'int', 0);
            $category['url']         = cmsCore::request('url', 'str', '');
            $category['pagetitle']   = cmsCore::request('pagetitle', 'str', '');
            $category['meta_desc']   = cmsCore::request('meta_desc', 'str', '');
            $category['meta_keys']   = cmsCore::request('meta_keys', 'str', '');
            
            if (!empty($category['url'])) {
                $category['url'] = cmsCore::strToURL($category['url'], cmsCore::m('content')->config['is_url_cyrillic']);
            }
            $category['tpl']         = cmsCore::request('tpl', 'str', 'com_content_view');
            
            $category['cost']        = cmsCore::request('cost', 'str', '');
            if (!is_numeric($category['cost'])) { $category['cost'] = ''; }
            
            $category['fields'] = cmsCore::request('fields', 'array', array());
            foreach ($category['fields'] as $key => $field) {
                $field = json_decode(urldecode($field), true);

                if (!cmsCore::c('db')->isFieldExists('cms_content_fields', $field['name'])) {
                    cmsCore::c('db')->query("ALTER TABLE `cms_content_fields` ADD `". $field['name'] ."` text NOT NULL DEFAULT ''");
                }

                if ($field['type'] == 'select') {
                    $field['items']['default']['items'] = explode("\n", $field['items']['default']['value']);
                }

                $category['fields'][$key] = $field;
            }
            $category['fields'] = cmsCore::c('db')->escape_string(cmsCore::jsonEncode($category['fields'], true));
            
            $album = array();
            $album['id']      = cmsCore::request('album_id', 'int', 0);
            $album['header']  = cmsCore::request('album_header', 'str', '');
            $album['orderby'] = cmsCore::request('album_orderby', 'str', '');
            $album['orderto'] = cmsCore::request('album_orderto', 'str', '');
            $album['maxcols'] = cmsCore::request('album_maxcols', 'int', 0);
            $album['max']     = cmsCore::request('album_max', 'int', 0);

            if ($album['id']) {
                $category['photoalbum'] = serialize($album);
            } else {
                $category['photoalbum'] = '';
            }

            // получаем старую категорию
            $old = cmsCore::c('db')->get_fields('cms_category', "id='". $category['id'] ."'", '*');
            if (!$old) { cmsCore::error404(); } 
            
            // если сменили категорию
            if ($old['parent_id'] != $category['parent_id']) {
                // перемещаем ее в дереве
                $inCore->nestedSetsInit('cms_category')->MoveNode($category['id'], $category['parent_id']);
                // обновляем сеолинки категорий
                cmsCore::c('db')->updateNsCategorySeoLink('cms_category', $category['id'], cmsCore::m('content')->config['is_url_cyrillic']);
                // Обновляем ссылки меню на категории
                cmsCore::m('content')->updateCatMenu();
                // обновляем сеолинки всех вложенных статей
                cmsCore::m('content')->updateArticlesSeoLink($category['id']);
                cmsCore::addSessionMessage($_LANG['AD_CATEGORY_NEW_URL'], 'info');
            }

            cmsCore::c('db')->update('cms_category', $category, $category['id']);

            // если пришел запрос на обновление ссылок
            // и категория не менялась - если менялась, мы выше все обновили
            if (cmsCore::inRequest('update_seolink') && ($old['parent_id'] == $category['parent_id'])) {
                // обновляем сеолинки категорий
                cmsCore::c('db')->updateNsCategorySeoLink('cms_category', $category['id'], cmsCore::m('content')->config['is_url_cyrillic']);
                // Обновляем ссылки меню на категории
                cmsCore::m('content')->updateCatMenu();
                // обновляем сеолинки всех вложенных статей
                cmsCore::m('content')->updateArticlesSeoLink($category['id']);
                cmsCore::addSessionMessage($_LANG['AD_SECTION_AND_ARTICLES_NEW_URL'], 'info');
            }

            if (!cmsCore::request('is_access', 'int', 0)) {
                $showfor = cmsCore::request('showfor', 'array_int');
                cmsCore::setAccess($category['id'], $showfor, 'category');
            } else {
                cmsCore::clearAccess($category['id'], 'category');
            }

            cmsCore::addSessionMessage($_LANG['AD_CATEGORY_SAVED'], 'success');

            if (!isset($_SESSION['editlist']) || @sizeof($_SESSION['editlist']) == 0) {
                cmsCore::redirect('?view=tree&cat_id='. $category['id']);
            } else {
                cmsCore::redirect('?view=tree');
            }
        }
    }

    if ($do == 'submit') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $category['title']       = cmsCore::request('title', 'str', $_LANG['AD_CATEGORY_UNTITLED']);
        $category['url']         = cmsCore::request('url', 'str', '');
        if (!empty($category['url'])) {
            $category['url'] = cmsCore::strToURL($category['url']);
        }
        $category['parent_id']   = cmsCore::request('parent_id', 'int');
        $category['description'] = cmsCore::request('description', 'html', '');
        $category['description'] = cmsCore::c('db')->escape_string($category['description']);
        $category['published']   = cmsCore::request('published', 'int', 0);
        $category['showdate']    = cmsCore::request('showdate', 'int', 0);
        $category['showcomm']    = cmsCore::request('showcomm', 'int', 0);
        $category['orderby']     = cmsCore::request('orderby', 'str', '');
        $category['orderto']     = cmsCore::request('orderto', 'str', '');
        $category['modgrp_id']   = cmsCore::request('modgrp_id', 'int', 0);
        $category['maxcols']     = cmsCore::request('maxcols', 'int', 0);
        $category['showtags']    = cmsCore::request('showtags', 'int', 0);
        $category['showrss']     = cmsCore::request('showrss', 'int', 0);
        $category['showdesc']    = cmsCore::request('showdesc', 'int', 0);
        $category['is_public']   = cmsCore::request('is_public', 'int', 0);
        $category['tpl']         = cmsCore::request('tpl', 'str', 'com_content_view');
        $category['pagetitle']   = cmsCore::request('pagetitle', 'str', '');
        $category['meta_desc']   = cmsCore::request('meta_desc', 'str', '');
        $category['meta_keys']   = cmsCore::request('meta_keys', 'str', '');

        $category['cost']        = cmsCore::request('cost', 'str', 0);
        if (!is_numeric($category['cost'])) { $category['cost'] = ''; }
        
        $category['fields'] = cmsCore::request('fields', 'array', array());
        foreach ($category['fields'] as $key => $field) {
            $field = json_decode(urldecode($field), true);
            
            if (!cmsCore::c('db')->isFieldExists('cms_content_fields', $field['name'])) {
                cmsCore::c('db')->query("ALTER TABLE `cms_content_fields` ADD `". $field['name'] ."` text NOT NULL DEFAULT ''");
            }

            if ($field['type'] == 'select') {
                $field['items']['default']['items'] = explode("\n", $field['items']['default']['value']);
            }

            $category['fields'][$key] = $field;
        }
        $category['fields'] = cmsCore::c('db')->escape_string(cmsCore::jsonEncode($category['fields'], true));

        $album = array();
        $album['id']      = cmsCore::request('album_id', 'int', 0);
        $album['header']  = cmsCore::request('album_header', 'str', '');
        $album['orderby'] = cmsCore::request('album_orderby', 'str', '');
        $album['orderto'] = cmsCore::request('album_orderto', 'str', '');
        $album['maxcols'] = cmsCore::request('album_maxcols', 'int', 0);
        $album['max']     = cmsCore::request('album_max', 'int', 0);

        if ($album['id']) {
            $category['photoalbum'] = serialize($album);
        } else {
            $category['photoalbum'] = '';
        }

        $ns = $inCore->nestedSetsInit('cms_category');
        $category['id'] = $ns->AddNode($category['parent_id']);

        $category['seolink'] = cmsCore::generateCatSeoLink($category, 'cms_category', cmsCore::m('content')->config['is_url_cyrillic']);

        if ($category['id']) {
            cmsCore::c('db')->update('cms_category', $category, $category['id']);

            if (!cmsCore::request('is_access', 'int', 0)) {
                $showfor = cmsCore::request('showfor', 'array_int');
                cmsCore::setAccess($category['id'], $showfor, 'category');
            } else {
                cmsCore::clearAccess($category['id'], 'category');
            }
        }

        $inmenu = cmsCore::request('createmenu', 'str', '');

        if ($inmenu) {
            createMenuItem($inmenu, $category['id'], $category['title']);
        }

        cmsCore::addSessionMessage($_LANG['AD_CATEGORY_ADD'], 'success');

        cmsCore::redirect('?view=tree');
    }

    if ($do == 'add' || $do == 'edit') {
        $menu_list = cpGetList('menu');
        
        if ($do == 'add') {
            echo '<h3>'. $_LANG['AD_CREATE_SECTION'] .'</h3>';
            
            cpAddPathway($_LANG['AD_CREATE_SECTION'], 'index.php?view=cats&do=add');
            
            $mod = array();
            $mod['tpl'] = 'com_content_view';
        } else {
            if (cmsCore::inRequest('multiple')) {
                if (cmsCore::inRequest('item')) {
                    $_SESSION['editlist'] = cmsCore::request('item', 'array_int');
                } else {
                    echo '<p class="error">'. $_LANG['AD_NO_SELECT_OBJECTS'] .'</p>';
                    return;
                }
            }
            
            $ostatok = '';
            
            if (isset($_SESSION['editlist'])) {
                $id = array_shift($_SESSION['editlist']);
                if (sizeof($_SESSION['editlist']) == 0) {
                    unset($_SESSION['editlist']);
                } else {
                    $ostatok = '('. $_LANG['AD_NEXT_IN'] . sizeof($_SESSION['editlist']) .')';
                }
            } else {
                $id = cmsCore::request('id', 'int', 0);
            }
            
            $mod = cmsCore::c('db')->get_fields('cms_category', 'id='.$id, '*');
            
            if (!empty($mod['photoalbum'])) {
                $mod['photoalbum'] = unserialize($mod['photoalbum']);
            }
            
            if (!empty($mod['fields'])) {
                $mod['fields'] = json_decode($mod['fields'], true);
                foreach ($mod['fields'] as $k => $field) {
                    $field['json'] = urlencode(json_encode($field));
                    $mod['fields'][$k] = $field;
                }
            }
            
            echo '<h3>'. $_LANG['AD_EDIT_SECTION'] . $ostatok .'</h3>';
            cpAddPathway($mod['title'], 'index.php?view=cats&do=edit&id='. $mod['id']);
        }
        
        $sql    = "SELECT * FROM cms_user_groups";
        $result = cmsCore::c('db')->query($sql) ;

        $group_style  = 'disabled="disabled"';
        $group_public = 'checked="checked"';

        if ($do == 'edit') {
            $sql2 = "SELECT * FROM cms_content_access WHERE content_id = ". $mod['id'] ." AND content_type = 'category'";
            $result2 = cmsCore::c('db')->query($sql2);
            $ord = array();

            if (cmsCore::c('db')->num_rows($result2)){
                $group_public = '';
                $group_style = '';
                while ($r = cmsCore::c('db')->fetch_assoc($result2)){
                    $ord[] = $r['group_id'];
                }
            }
        }

        $user_groups = array();
        if (cmsCore::c('db')->num_rows($result)) {
            while ($item = cmsCore::c('db')->fetch_assoc($result)) {
                $group = array(
                    'title' => $item['title'],
                    'value' => $item['id']
                );

                if ($do == 'edit' && in_array($item['id'], $ord)) {
                    $group['selected'] = 'selected';
                }

                $user_groups[] = $group;
            }
        }
        
        $rootid = cmsCore::c('db')->getNsRootCatId('cms_category');
        
        cmsCore::c('page')->initTemplate('applets', 'cats_edit')->
            assign('is_billing', IS_BILLING)->
            assign('rootid', $rootid)->
            assign('category_opt', $inCore->getListItemsNS('cms_category', cmsCore::getArrVal($mod, 'parent_id', $rootid)))->
            assign('menu_list', $menu_list)->
            assign('user_group_opt', $inCore->getListItems('cms_user_groups', cmsCore::getArrVal($mod, 'modgrp_id', 0), 'id', 'ASC', 'is_admin = 0'))->
            assign('photo_albums_opt', $inCore->getListItemsNS('cms_photo_albums', cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'id', 0)))->
            assign('user_groups', $user_groups)->
            assign('group_public', $group_public)->
            assign('group_style', $group_style)->
            assign('do', $do)->
            assign('mod', $mod)->
            display();
    }
}