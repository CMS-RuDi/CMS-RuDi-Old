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

function iconList() {
    global $_LANG;
    
    $items = array();
    
    if ($handle = opendir(PATH .'/images/menuicons')) {
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..'){
                $ext = explode('.', $file);
                if (!in_array($ext[count($ext)-1], array('gif', 'png', 'ico', 'jpg', 'jpeg'))) {
                    continue;
                }
                
                $dir = '/images/menuicons/';
                
                $items[] = array(
                    'file' => $file,
                    'src'  => $dir . $file
                );
            }
        }
        closedir($handle);
    }
    
    return cmsCore::c('page')->initTemplate('applets', 'menu_icons_list')->
        assign('items', $items)->
        fetch();
}

function list_menu($menu) {
    $m = cmsCore::yamlToArray($menu);
    return implode(', ', $m);
}

function cpMenutypeById($item) {
    global $_LANG;

    $maxlen = 35;
    $title = $type = '';

    switch ($item['linktype']) {
        case 'link':
            $title = $item['linkid'];
            $type  = $_LANG['AD_TYPE_LINK'];
            break;
        case 'component':
            $title = cmsCore::c('db')->get_field('cms_components', "link='". $item['linkid'] ."'", 'title');
            $type  = $_LANG['AD_TYPE_COMPONENT'];
            break;
        case 'content':
            $title = cmsCore::c('db')->get_field('cms_content', 'id='. $item['linkid'], 'title');
            $type  = $_LANG['AD_TYPE_ARTICLE'];
            break;
        case 'category':
            $title = cmsCore::c('db')->get_field('cms_category', 'id='. $item['linkid'], 'title');
            $type  = $_LANG['AD_TYPE_PARTITION'];
            break;
        case 'video_cat':
            if (cmsCore::getInstance()->isComponentInstalled('video')) {
                $title = cmsCore::c('db')->get_field('cms_video_category', 'id='. $item['linkid'], 'title');
                $type  = $_LANG['AD_TYPE_VIDEO_PARTITION'];
            } 
            break; 
        case 'uccat':
            $title = cmsCore::c('db')->get_field('cms_uc_cats', 'id='. $item['linkid'], 'title');
            $type  = $_LANG['AD_TYPE_CATEGORY'];
            break;
        case 'blog':
            $title = cmsCore::c('db')->get_field('cms_blogs', 'id='. $item['linkid'], 'title');
            $type  = $_LANG['AD_TYPE_BLOG'];
            break;
        case 'photoalbum':
            $title = cmsCore::c('db')->get_field('cms_photo_albums', 'id='. $item['linkid'], 'title');
            $type  = $_LANG['AD_TYPE_ALBUM'];
            break;
    }
    
    $html = cmsCore::c('page')->initTemplate('applets', 'menu_linktype')->
            assign('title', $title)->
            assign('link', $item['link'])->
            assign('type', $type)->
            fetch();
    
    $clear = strip_tags($html);
    
    $r = mb_strlen($html) - mb_strlen($clear);
    
    if (mb_strlen($clear)>$maxlen) { $html = mb_substr($html, 0, $maxlen+$r) .'...'; }
    
    return $html;
}

function applet_menu() {
    $inCore = cmsCore::getInstance();

    global $_LANG;
    global $adminAccess;

    if (!cmsUser::isAdminCan('admin/menu', $adminAccess)) { cpAccessDenied(); }

    cmsCore::c('page')->setTitle($_LANG['AD_MENU']);
    cpAddPathway($_LANG['AD_MENU'], 'index.php?view=menu');

    $do = cmsCore::request('do', 'str', 'list');
    $id = cmsCore::request('id', 'int', -1);

    if ($do == 'list') {
        $toolmenu = array(
            array( 'icon' => 'new.gif', 'title' => $_LANG['AD_MENU_POINT_ADD'], 'link' => '?view=menu&do=add' ),
            array( 'icon' => 'newmenu.gif', 'title' => $_LANG['AD_MENU_ADD'], 'link' => '?view=menu&do=addmenu' ),
            array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link'=> "javascript:checkSel('?view=menu&do=edit&multiple=1');" ),
            array( 'icon' => 'delete.gif', 'title' => $_LANG['AD_DELETE_SELECTED'], 'link' => "javascript:checkSel('?view=menu&do=delete&multiple=1');" ),
            array( 'icon' => 'show.gif', 'title' => $_LANG['AD_ALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=menu&do=show&multiple=1');" ),
            array( 'icon' => 'hide.gif', 'title' => $_LANG['AD_DISALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=menu&do=hide&multiple=1');" ),
            array( 'icon' => 'help.gif', 'title' => $_LANG['AD_HELP'], 'link' => '?view=help&topic=menu' )
        );

        cpToolMenu($toolmenu);

        $fields = array(
            array( 'title' => 'Lt', 'field' => 'NSLeft', 'width' => '40' ),
            array(
                'title' => $_LANG['TITLE'],
                'field' => array('title', 'titles'), 'width'=>'',
                'link'  => '?view=menu&do=edit&id=%id%',
                'prc'   => function ($i) {
                    $i['titles'] = cmsCore::yamlToArray($i['titles']);
                    
                    // переопределяем название пункта меню в зависимости от языка
                    if (!empty($i['titles'][cmsConfig::getConfig('lang')])) {
                        $i['title'] = $i['titles'][cmsConfig::getConfig('lang')];
                    }
                    
                    return $i['title'];
                }
            ),
            array( 'title' => $_LANG['SHOW'], 'field' => 'published', 'width' => '80' ),
            array( 'title' => $_LANG['AD_ORDER'], 'field' => 'ordering', 'width' => '100' ),
            array( 'title' => $_LANG['AD_LINK'], 'field' => array('linktype', 'linkid', 'link'), 'width' => '240', 'prc' => 'cpMenutypeById' ),
            array( 'title' => $_LANG['AD_MENU'], 'field' => 'menu', 'width' => '80', 'filter' => '10', 'filterlist' => cpGetList('menu'), 'prc' => 'list_menu' ),
            array( 'title' => $_LANG['TEMPLATE'], 'field' => 'template', 'width' => '90', 'prc' => 'cpTemplateById' )
        );
        
        $actions = array(
            array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=menu&do=edit&id=%id%' ),
            array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_MENU_POINT_CONFIRM'], 'link' => '?view=menu&do=delete&id=%id%' )
        );

        cpListTable('cms_menu', $fields, $actions, 'parent_id>0', 'NSLeft, ordering');

    } else {
        $toolmenu = array(
            array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.addform.submit();' ),
            array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => 'index.php?view=menu' )
        );

        cpToolMenu($toolmenu);
    }

    if ($do == 'move_up') {
        cmsCore::c('db')->moveNsCategory('cms_menu', $id, 'up');
        cmsCore::redirectBack();
    }

    if ($do == 'move_down') {
        cmsCore::c('db')->moveNsCategory('cms_menu', $id, 'down');
        cmsCore::redirectBack();
    }

    if ($do == 'show') {
        if (!cmsCore::inRequest('item')) {
            if ($id >= 0) { cmsCore::c('db')->setFlag('cms_menu', $id, 'published', '1'); }
            cmsCore::halt('1');
        } else {
            cmsCore::c('db')->setFlags('cms_menu', $_REQUEST['item'], 'published', '1');
            cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'] , 'success');
            cmsCore::redirectBack();
        }
    }

    if ($do == 'hide') {
        if (!cmsCore::inRequest('item')) {
            if ($id >= 0) { cmsCore::c('db')->setFlag('cms_menu', $id, 'published', '0'); }
            cmsCore::halt('1');
        } else {
            cmsCore::c('db')->setFlags('cms_menu', cmsCore::request('item', 'array_int', array()), 'published', '0');
            cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'] , 'success');
            cmsCore::redirectBack();
        }
    }

    if ($do == 'delete') {
        if (!cmsCore::inRequest('item')) {
            if ($id >= 0) { cmsCore::c('db')->deleteNS('cms_menu', (int)$id); }
        } else {
            cmsCore::c('db')->deleteListNS('cms_menu', cmsCore::request('item', 'array_int', array()));
        }
        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'] , 'success');
        cmsCore::redirectBack();
    }

    if ($do == 'update') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $id = cmsCore::request('id', 'int', 0);
        if(!$id){ cmsCore::redirectBack(); }

        $title     = cmsCore::request('title', 'str', '');
        $titles    = cmsCore::arrayToYaml(cmsCore::request('titles', 'array_str', array()));
        $menu      = cmsCore::arrayToYaml(cmsCore::request('menu', 'array_str', ''));
        $linktype  = cmsCore::request('mode', 'str', '');
        $linkid    = cmsCore::request($linktype, 'str', '');
        $link      = $inCore->getMenuLink($linktype, $linkid);
        $target    = cmsCore::request('target', 'str', '');
        $published = cmsCore::request('published', 'int', 0);
        $template  = cmsCore::request('template', 'str', '');
        $iconurl   = cmsCore::request('iconurl', 'str', '');
        $parent_id = cmsCore::request('parent_id', 'int', 0);
        $oldparent = cmsCore::request('oldparent', 'int', 0);
        $is_lax    = cmsCore::request('is_lax', 'int', 0);
        $css_class = cmsCore::request('css_class', 'str', '');

        $is_public = cmsCore::request('is_public', 'int', '');
        if (!$is_public) {
            $access_list = cmsCore::arrayToYaml(cmsCore::request('allow_group', 'array_int'));
        }

        $ns = $inCore->nestedSetsInit('cms_menu');

        if ($oldparent != $parent_id) {
            $ns->MoveNode($id, $parent_id);
        }

        $sql = "UPDATE cms_menu
                SET title='". $title ."',
                    titles='". $titles ."',
                    css_class='". $css_class ."',
                    menu='". $menu ."',
                    link='". $link ."',
                    linktype='". $linktype ."',
                    linkid='". $linkid ."',
                    target='". $target ."',
                    published='". $published ."',
                    template='". $template ."',
                    access_list='". $access_list ."',
                    is_lax='". $is_lax ."',
                    iconurl='". $iconurl ."'
                WHERE id = '". $id ."'
                LIMIT 1";
        cmsCore::c('db')->query($sql) ;

        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'] , 'success');

        if (!isset($_SESSION['editlist']) || count($_SESSION['editlist']) == 0) {
            cmsCore::redirect('?view=menu');
        } else {
            cmsCore::redirect('?view=menu&do=edit');
        }

    }

    if ($do == 'submit') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $title     = cmsCore::request('title', 'str', '');
        $titles    = cmsCore::arrayToYaml(cmsCore::request('titles', 'array_str', array()));
        $menu      = cmsCore::arrayToYaml(cmsCore::request('menu', 'array_str', ''));
        $linktype  = cmsCore::request('mode', 'str', '');
        $linkid    = cmsCore::request($linktype, 'str', '');
        $link      = $inCore->getMenuLink($linktype, $linkid);
        $target    = cmsCore::request('target', 'str', '');
        $published = cmsCore::request('published', 'int', 0);
        $template  = cmsCore::request('template', 'str', '');
        $iconurl   = cmsCore::request('iconurl', 'str', '');
        $parent_id = cmsCore::request('parent_id', 'int', 0);
        $css_class = cmsCore::request('css_class', 'str', '');

        $is_public = cmsCore::request('is_public', 'int', '');
        $is_lax    = cmsCore::request('is_lax', 'int', 0);
        if (!$is_public) {
            $access_list = cmsCore::arrayToYaml(cmsCore::request('allow_group', 'array_int'));
        }

        $ns = $inCore->nestedSetsInit('cms_menu');
        $myid = $ns->AddNode($parent_id);

        $sql = "UPDATE cms_menu
                        SET menu='". $menu ."',
                                title='". $title ."',
                                titles='". $titles ."',
                                css_class='". $css_class ."',
                                link='". $link ."',
                                linktype='". $linktype ."',
                                linkid='". $linkid ."',
                                target='". $target ."',
                                published='". $published ."',
                                template='". $template ."',
                                access_list='". $access_list ."',
                                is_lax='". $is_lax ."',
                                iconurl='". $iconurl ."'
                        WHERE id = '". $myid ."'";

        cmsCore::c('db')->query($sql);

        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'] , 'success');
        cmsCore::redirect('?view=menu');
    }

    if ($do == 'submitmenu') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $sql = "SELECT ordering as max_o FROM cms_modules ORDER BY ordering DESC LIMIT 1";
        $result = cmsCore::c('db')->query($sql) ;
        $row = cmsCore::c('db')->fetch_assoc($result);
        $maxorder = $row['max_o'] + 1;

        $menu       = cmsCore::request('menu', 'str', '');
        $title      = cmsCore::request('title', 'str', '');
        $position   = cmsCore::request('position', 'str', '');
        $published  = cmsCore::request('published', 'int', 0);
        $css_prefix = cmsCore::request('css_prefix', 'str', '');
        $is_public  = cmsCore::request('is_public', 'int', '');
        if (!$is_public) {
            $access_list = $inCore->arrayToYaml(cmsCore::request('allow_group', 'array_int'));
        }

        $cfg['menu'] = $menu;
        $cfg_str = cmsCore::arrayToYaml($cfg);

        $sql = "INSERT INTO cms_modules (position, name, title, is_external, content, ordering, showtitle, published, user, config, css_prefix, access_list)
                VALUES ('". $position ."', '". $_LANG['AD_MENU'] ."', '". $title ."', 1, 'mod_menu', ". $maxorder .", 1, ". $published .", 0, '". $cfg_str ."', '". $css_prefix ."', '". $access_list ."')";

        cmsCore::c('db')->query($sql) ;

        $newid = cmsCore::c('db')->get_last_id('cms_modules');

        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'] , 'success');

        cmsCore::redirect('?view=modules&do=edit&id='.$newid);

    }

    if ($do == 'addmenu' || $do == 'add' || $do == 'edit') {
        cmsCore::c('page')->addHeadJS('admin/js/menu.js');
        echo '<script>';
        echo cmsPage::getLangJS('AD_SPECIFY_LINK_MENU');
        echo '</script>';
    }

    if ($do == 'addmenu') {
        cmsCore::c('page')->setTitle($_LANG['AD_MENU_ADD']);
        cpAddPathway($_LANG['AD_MENU_ADD']);

        cmsCore::c('page')->initTemplate('applets', 'menu_addmenu')->
            assign('menu_list', cpGetList('menu'))->
            assign('pos', cpModulePositions(cmsCore::c('config')->template))->
            assign('groups', cmsUser::getGroups())->
            assign('access_list', !empty($mod['access_list']) ? $inCore->yamlToArray($mod['access_list']) : array())->
            assign('mod', $mod)->
            display();
    }

    if ($do == 'add' || $do == 'edit') {
        if ($do == 'add') {
            cpAddPathway($_LANG['AD_MENU_POINT_ADD']);
            $mod['menu'] = array('mainmenu');
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
                $item_id = cmsCore::request('id', 'int', 0);
            }

            $mod = cmsCore::c('db')->get_fields('cms_menu', "id = '$item_id'", '*');
            if (!$mod) { cmsCore::error404(); }

            $mod['menu']   = cmsCore::yamlToArray($mod['menu']);
            $mod['titles'] = cmsCore::yamlToArray($mod['titles']);

            cpAddPathway($_LANG['AD_MENU_POINT_EDIT'].$ostatok.' "'.$mod['title'].'"');
        }
        
        $linktype = cmsCore::getArrVal($mod, 'linktype', 'link');
        
        $iconList = iconList();
        
        $tpl = cmsCore::c('page')->initTemplate('applets', 'menu_add')->
            assign('linktype', $linktype)->
            assign('langs', cmsCore::getDirsList('/languages'))->
            assign('templates', cmsCore::getDirsList('/templates'))->
            assign('menu_list', cpGetList('menu'))->
            assign('rootid', cmsCore::c('db')->get_field('cms_menu', 'parent_id=0', 'id'))->
            assign('menu_opt', $inCore->getListItemsNS('cms_menu', cmsCore::getArrVal($mod, 'parent_id', 0)))->
            assign('content_opt', $inCore->getListItems('cms_content', (($linktype == 'content') ? $mod['linkid'] : 0)))->
            assign('category_opt',  $inCore->getListItemsNS('cms_category', (($link_type == 'category') ? $mod['linkid'] : 0)))->
            assign('components_opt',  $inCore->getListItems('cms_components', (($link_type == 'component') ? $mod['linkid'] : 0), 'title', 'asc', 'internal=0', 'link'))->
            assign('blogs_opt', $inCore->getListItems('cms_blogs', (($link_type == 'blog') ? $mod['linkid'] : 0), 'title', 'asc', "owner='user'"))->
            assign('uc_cats_opt', $inCore->getListItems('cms_uc_cats', (($link_type == 'uccat') ? $mod['linkid'] : 0)))->
            assign('photo_albums_opt', $inCore->getListItems('cms_photo_albums', (($link_type == 'photoalbum') ? $mod['linkid'] : 0), 'id', 'ASC', 'NSDiffer = ""'))->
            assign('video_installed', $inCore->isComponentInstalled('video'))->
            assign('groups', cmsUser::getGroups())->
            assign('iconList', $iconList)->
            assign('do', $do)->
            assign('mod', $mod);
        
        if ($inCore->isComponentInstalled('video')) {
            $tpl->assign('video_cats_opt', $inCore->getListItemsNS('cms_video_category', (($linktype == 'video_cat') ? $mod['linkid'] : 0)));
        }
        
        if ($do == 'edit') {
            $tpl->assign('access_list', !empty($mod['access_list']) ? $inCore->yamlToArray($mod['access_list']) : array());
        }
        
        $tpl->display();
   }
}