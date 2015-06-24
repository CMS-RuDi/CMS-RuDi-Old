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

function cpModuleHasConfig($item) {
    return $item['is_external'] ? true : false;
}

function applet_modules() {
    $inCore = cmsCore::getInstance();

    global $_LANG;

    global $adminAccess;
    if (!cmsUser::isAdminCan('admin/modules', $adminAccess)) { cpAccessDenied(); }

    cmsCore::c('page')->setTitle($_LANG['AD_MODULES']);
    cpAddPathway($_LANG['AD_MODULES'], 'index.php?view=modules');
    cmsCore::c('page')->addHeadJS('admin/js/modules.js');

    $do = cmsCore::request('do', 'str', 'list');
    $id = cmsCore::request('id', 'int', -1);
    $co = cmsCore::request('co', 'int', -1);

    if ($do == 'config') {
        $module_name  = cpModuleById($id);
        $module_title = cpModuleTitleById($id);

        if (!$module_name) { cmsCore::redirect('index.php?view=modules&do=edit&id='. $id); }

        $xml_file = PATH .'/admin/modules/'. $module_name .'/backend.xml';
        $php_file = 'modules/'. $module_name .'/backend.php';
        
        if (file_exists($php_file)) {
            include $php_file;
            return;
        }
        
        $cfg = $inCore->loadModuleConfig($id);
        
        cpAddPathway($module_title, '?view=modules&do=edit&id='. $id);
        cpAddPathway($_LANG['AD_SETTINGS']);

        $toolmenu = array(
            array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:submitModuleConfig();' ),
            array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => 'index.php?view=modules' ),
            array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_MODULE_VIEW'], 'link' => '?view=modules&do=edit&id='. $id )
        );

        cpToolMenu($toolmenu);
        
        $tpl = cmsCore::c('page')->initTemplate('applets', 'modules_config')->
            assign('module_title', $module_title)->
            assign('id', $id);
        
        if (file_exists($xml_file)) {
            cmsCore::loadClass('formgen');
            $formGen = new cmsFormGen($xml_file, $cfg);
            $tpl->assign('formGenHtml', $formGen->getHTML());
        } else {
            $tpl->assign('cfg', $cfg);
        }
        
        $tpl->display();
    }

    if ($do == 'save_auto_config') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
        
        $module_name = cpModuleById($id);

        $is_ajax = cmsCore::inRequest('ajax');

        if ($is_ajax) {
            $title      = cmsCore::request('title', 'str', '');
            $published  = cmsCore::request('published', 'int', 0);
            cmsCore::c('db')->query("UPDATE cms_modules SET title='". $title ."', published='". $published ."' WHERE id=". $id);
            if (cmsCore::inRequest('content')) {
                $content = cmsCore::c('db')->escape_string(cmsCore::request('content', 'html'));
                cmsCore::c('db')->query("UPDATE cms_modules SET content='". $content ."' WHERE id=". $id);
            }
        }

        if (cmsCore::inRequest('title_only')) { cmsCore::redirectBack(); }

        $xml_file = PATH .'/admin/modules/'. $module_name .'/backend.xml';
        if (file_exists($xml_file)) {
            $cfg = array();

            $backend = simplexml_load_file($xml_file);

            foreach ($backend->params->param as $param) {
                $name    = (string)$param['name'];
                $type    = (string)$param['type'];
                $default = (string)$param['default'];

                switch($param['type']) {
                    case 'number': $value = cmsCore::request($name, 'int', $default); break;
                    case 'string': $value = cmsCore::request($name, 'str', $default); break;
                    case 'html':   $value = cmsCore::badTagClear(cmsCore::request($name, 'html', $default)); break;
                    case 'flag': $value = cmsCore::request($name, 'int', 0); break;
                    case 'list': $value = (is_array($_POST[$name]) ? cmsCore::request($name, 'array_str', $default) : cmsCore::request($name, 'str', $default)); break;
                    case 'list_function': $value = cmsCore::request($name, 'str', $default); break;
                    case 'list_db': $value = (is_array($_POST[$name]) ? cmsCore::request($name, 'array_str', $default) : cmsCore::request($name, 'str', $default)); break;
                }

                $cfg[$name] = $value;
            }
        }
        
        $cfg['tpl'] = cmsCore::request('tpl', 'str', $module_name);

        $inCore->saveModuleConfig($id, $cfg);

        if (!$is_ajax) {
            cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
        }

        cmsCore::redirectBack();
    }

    if ($do == 'list') {
        $toolmenu = array(
            array( 'icon' => 'new.gif', 'title' => $_LANG['AD_MODULE_ADD'], 'link' => '?view=modules&do=add' ),
            array( 'icon' => 'install.gif', 'title' => $_LANG['AD_MODULES_SETUP'], 'link' => '?view=install&do=module' ),
            array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link' => "javascript:checkSel('?view=modules&do=edit&multiple=1');" ),
            array( 'icon' => 'delete.gif', 'title' => $_LANG['AD_DELETE_SELECTED'], 'link' => "javascript:checkSel('?view=modules&do=delete&multiple=1');" ),
            array( 'icon' => 'show.gif', 'title' => $_LANG['AD_ALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=modules&do=show&multiple=1');" ),
            array( 'icon' => 'hide.gif', 'title' => $_LANG['AD_DISALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=modules&do=hide&multiple=1');" ),
            array( 'icon' => 'autoorder.gif', 'title' => $_LANG['AD_MODULE_ORDER'], 'link' => '?view=modules&do=autoorder' ),
            array( 'icon' => 'reorder.gif', 'title' => $_LANG['AD_SAVE_ORDER'], 'link' => "javascript:checkSel('?view=modules&do=saveorder');" ),
            array( 'icon' => 'help.gif', 'title' => $_LANG['AD_HELP'], 'link' => '?view=help&topic=modules' )
        );

        cpToolMenu($toolmenu);
        
        $fields = array(
            array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
            array(
                'title' => $_LANG['AD_TITLE'],
                'field' => array('title','titles'), 'width'=>'',
                'link'  => '?view=modules&do=edit&id=%id%',
                'prc'   => function ($i) {
                    $i['titles'] = cmsCore::yamlToArray($i['titles']);
                    // переопределяем название пункта меню в зависимости от языка
                    if (!empty($i['titles'][cmsConfig::getConfig('lang')])) {
                        $i['title'] = $i['titles'][cmsConfig::getConfig('lang')];
                    }
                    
                    return $i['title'];
                }
            ),
            array( 'title' => $_LANG['TITLE'], 'field' => 'name', 'width' => '220', 'filter' => '15' ),
            array( 'title' => $_LANG['AD_VERSION'], 'field' => 'version', 'width' => '70' ),
            array( 'title' => $_LANG['AD_AUTHOR'], 'field' => 'author', 'width' => '110' ),
            array( 'title' => $_LANG['SHOW'], 'field' => 'published', 'width' => '80' ),
            array( 'title' => $_LANG['AD_ORDER'], 'field' => 'ordering', 'width' => '100' ),
            array( 'title' => $_LANG['AD_POSITION'], 'field' => 'position', 'width' => '80', 'filter' => '10', 'filterlist' => cpGetList('positions') )
        );
        
        $actions = array(
            array( 'title' => $_LANG['AD_CONFIG'], 'icon' => 'config.gif', 'link' => '?view=modules&do=config&id=%id%', 'condition' => 'cpModuleHasConfig' ),
            array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=modules&do=edit&id=%id%' ),
            array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_MODULE_DELETE'], 'link' => '?view=modules&do=delete&id=%id%' )
        );
        
        cpListTable('cms_modules', $fields, $actions, '', 'published DESC, position, ordering ASC');
    }

    if ($do == 'autoorder'){
        $rs = cmsCore::c('db')->query("SELECT id, position FROM cms_modules ORDER BY position") ;

        if (cmsCore::c('db')->num_rows($rs)) {
            $ord = 1;
            while ($item = cmsCore::c('db')->fetch_assoc($rs)) {
                if (isset($latest_pos)) {
                    if ($latest_pos != $item['position']) {
                        $ord = 1;
                    }
                }
                cmsCore::c('db')->query("UPDATE cms_modules SET ordering = ". $ord ." WHERE id=". $item['id']) ;
                $ord += 1;
                $latest_pos = $item['position'];
            }
        }

        cmsCore::redirect('index.php?view=modules');
    }

    if ($do == 'move_up') {
        if ($id >= 0) { dbMoveUp('cms_modules', $id, $co); }
        cmsCore::redirectBack();
    }

    if ($do == 'move_down') {
        if ($id >= 0) { dbMoveDown('cms_modules', $id, $co); }
        cmsCore::redirectBack();
    }

    if ($do == 'saveorder') {
        if (isset($_REQUEST['ordering'])) {
            $ord = $_REQUEST['ordering'];
            $ids = $_REQUEST['ids'];

            foreach ($ord as $id=>$ordering) {
                cmsCore::c('db')->query("UPDATE cms_modules SET ordering = ". (int)$ordering ." WHERE id = ". (int)$ids[$id]);
            }
            cmsCore::redirect('index.php?view=modules');
        }
    }

    if ($do == 'show') {
        if (!isset($_REQUEST['item'])) {
            if ($id >= 0) { cmsCore::c('db')->setFlag('cms_modules', $id, 'published', '1'); }
            cmsCore::halt('1');
        } else {
            cmsCore::c('db')->setFlags('cms_modules', $_REQUEST['item'], 'published', '1');
            cmsCore::redirectBack();
        }

    }

    if ($do == 'hide') {
        if (!isset($_REQUEST['item'])) {
            if ($id >= 0) { cmsCore::c('db')->setFlag('cms_modules', $id, 'published', '0'); }
            cmsCore::halt('1');
        } else {
            cmsCore::c('db')->setFlags('cms_modules', $_REQUEST['item'], 'published', '0');
            cmsCore::redirectBack();
        }
    }

    if ($do == 'delete') {
        if (!cmsCore::inRequest('item')) {
            $inCore->removeModule($id);
        } else {
            $inCore->removeModule(cmsCore::request('item', 'array_int', array()));
        }
        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
        cmsCore::redirect('index.php?view=modules');
    }

    if ($do == 'update') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
        
        $id = cmsCore::request('id', 'int', 0);
        $mod = cmsCore::c('db')->get_fields('cms_modules', "id = ". $id ."", '*');
        
        $module = array(
            'name'       => cmsCore::request('name', 'str', ''),
            'title'      => cmsCore::request('title', 'str', ''),
            'titles'     => cmsCore::arrayToYaml(cmsCore::request('titles', 'array_str', array())),
            'position'   => cmsCore::request('position', 'str', ''),
            'showtitle'  => cmsCore::request('showtitle', 'int', 0),
            'published'  => cmsCore::request('published', 'int', 0),
            'css_prefix' => cmsCore::request('css_prefix', 'str', ''),
            'is_strict_bind' => cmsCore::request('is_strict_bind', 'int', 0),
            'is_strict_bind_hidden' => cmsCore::request('is_strict_bind_hidden', 'int', 0),
            'template'   => cmsCore::request('template', 'str', ''),
            'cache'      => cmsCore::request('cache', 'int', 0),
            'cachetime'  => cmsCore::request('cachetime', 'int', 0),
            'cacheint'   => cmsCore::request('cacheint', 'str', ''),
            'access_list' => '',
            'hidden_menu_ids' => ''
        );
        
        if (!$mod['is_external']) {
            $module['content'] = cmsCore::c('db')->escape_string(cmsCore::request('content', 'html', ''));
        }

        $is_public = cmsCore::request('is_public', 'int', '');
        if (!$is_public) {
            $module['access_list'] = cmsCore::arrayToYaml(cmsCore::request('allow_group', 'array_int', array()));
        }
        
        cmsCore::c('db')->update('cms_modules', $module, $id);
        cmsCore::c('db')->delete('cms_modules_bind', "module_id=". $id ." AND tpl='". cmsCore::c('config')->template ."'");

        if (cmsCore::request('show_all', 'int', 0)) {
            cmsCore::c('db')->insert(
                'cms_modules_bind',
                array(
                    'module_id' => $id,
                    'menu_id'   => 0,
                    'position'  => $module['position'],
                    'tpl'       => cmsCore::c('config')->template
                )
            );
            
            $hidden_menu_ids = cmsCore::request('hidden_menu_ids', 'array_int', array());
            if (!empty($hidden_menu_ids)) {
                $hidden_menu_ids = cmsCore::arrayToYaml($hidden_menu_ids);
                cmsCore::c('db')->query("UPDATE cms_modules SET hidden_menu_ids='". $hidden_menu_ids ."' WHERE id = '". $id ."' LIMIT 1");
            }
        } else {
            $showin  = cmsCore::request('showin', 'array_int', array());
            $showpos = cmsCore::request('showpos', 'array_str', array());
            if (count($showin) > 0) {
                foreach ($showin as $key => $value) {
                    cmsCore::c('db')->insert(
                        'cms_modules_bind',
                        array(
                            'module_id' => $id,
                            'menu_id'   => $value,
                            'position'  => $showpos[$value],
                            'tpl'       => cmsCore::c('config')->template
                        )
                    );
                }
            }
        }

        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'] , 'success');

        if (!isset($_SESSION['editlist']) || count($_SESSION['editlist']) == 0) {
            cmsCore::redirect('index.php?view=modules');
        } else {
            cmsCore::redirect('index.php?view=modules&do=edit');
        }
    }

    if ($do == 'submit') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
        
        $maxorder = cmsCore::c('db')->get_field('cms_menu', '1=1 ORDER BY ordering DESC', 'ordering') + 1;

        $name           = cmsCore::request('name', 'str', '');
        $title          = cmsCore::request('title', 'str', '');
        $titles         = cmsCore::arrayToYaml(cmsCore::request('titles', 'array_str', array()));
        $position       = cmsCore::request('position', 'str', '');
        $showtitle      = cmsCore::request('showtitle', 'int', 0);
        $content    	= cmsCore::c('db')->escape_string(cmsCore::request('content', 'html', ''));
        $published      = cmsCore::request('published', 'int', 0);
        $css_prefix     = cmsCore::request('css_prefix', 'str', '');

        $is_public      = cmsCore::request('is_public', 'int', '');
        if (!$is_public) {
            $access_list = cmsCore::arrayToYaml(cmsCore::request('allow_group', 'array_int', array()));
        }

        $template       = cmsCore::request('template', 'str', '');
        $cache          = cmsCore::request('cache', 'int', 0);
        $cachetime      = cmsCore::request('cachetime', 'int', 0);
        $cacheint       = cmsCore::request('cacheint', 'str', '');
        $operate        = cmsCore::request('operate', array('user', 'clone'), '');

        $is_strict_bind = cmsCore::request('is_strict_bind', 'int', 0);
        $is_strict_bind_hidden = cmsCore::request('is_strict_bind_hidden', 'int', 0);

        if ($operate == 'user') { //USER MODULE
            $sql = "INSERT INTO cms_modules (position, name, title, titles, is_external, content, ordering, showtitle, published, user, original, css_prefix, access_list, template, is_strict_bind, is_strict_bind_hidden)
                            VALUES ('". $position ."', '". $name ."', '". $title ."', '". $titles ."', 0, '". $content ."', '". $maxorder ."', '". $showtitle ."', '". $published ."', 1, 1, '". $css_prefix ."', '". $access_list ."', '". $template ."', '". $is_strict_bind ."', '". $is_strict_bind_hidden ."')";
            cmsCore::c('db')->query($sql) ;
        }

        if ($operate == 'clone') { //DUPLICATE MODULE
            $mod_id     = cmsCore::request('clone_id', 'int', 0);

            $sql         = "SELECT * FROM cms_modules WHERE id = ". $mod_id ." LIMIT 1";
            $result      = cmsCore::c('db')->query($sql) ;
            $original    = cmsCore::c('db')->escape_string(cmsCore::c('db')->fetch_assoc($result));
            $is_original = cmsCore::request('del_orig', 'int', 0) ? 1 : 0;

            $sql = "INSERT INTO cms_modules (position, name, title, titles, is_external, content, ordering, showtitle, published, original, user, config, css_prefix, template, access_list, is_strict_bind, is_strict_bind_hidden, cache, cachetime, cacheint, version)
                        VALUES (
                            '". $position ."',
                            '". $original['name'] ."',
                            '". $title ."',
                            '". $titles ."',
                            '". $original['is_external'] ."',
                            '". $original['content'] ."',
                            '". $maxorder ."',
                            '". $showtitle ."',
                            '". $published ."',
                            '". $is_original ."',
                            '". $original['user'] ."',
                            '". $original['config'] ."',
                            '". $css_prefix ."',
                            '". $template ."',
                            '". $access_list ."',
                            '". $is_strict_bind ."',
                            '". $is_strict_bind_hidden ."',
                            '". $cache ."', 
                            '". $cachetime ."',
                            '". $cacheint ."',
                            '". $original['version'] ."'
                )";
            cmsCore::c('db')->query($sql);

            if (cmsCore::request('del_orig', 'int', 0)) {
                $sql = "DELETE FROM cms_modules WHERE id = ". $mod_id;
                cmsCore::c('db')->query($sql) ;
            }
        }

        $lastid = cmsCore::c('db')->get_last_id('cms_modules');

        if (cmsCore::request('show_all', 'int', 0)) {
            $sql = "INSERT INTO cms_modules_bind (module_id, menu_id, position, tpl)
                            VALUES (". $lastid .", 0, '". $position ."', '". cmsCore::c('config')->template ."')";
            cmsCore::c('db')->query($sql) ;
            
            $hidden_menu_ids = cmsCore::request('hidden_menu_ids', 'array_int', array());
            if ($hidden_menu_ids) {
                $hidden_menu_ids = cmsCore::arrayToYaml($hidden_menu_ids);
                cmsCore::c('db')->query("UPDATE cms_modules SET hidden_menu_ids='". $hidden_menu_ids ."' WHERE id = '". $lastid ."' LIMIT 1");
            }
        } else {
            $showin = cmsCore::request('showin', 'array', array());
            $showpos = cmsCore::request('showpos', 'array', array());
            if (count($showin) > 0) {
                foreach ($showin as $key=>$value) {
                    $sql = "INSERT INTO cms_modules_bind (module_id, menu_id, position, tpl)
                                    VALUES (". $lastid .", ". $value .", '". $showpos[$value] ."', '". cmsCore::c('config')->template ."')";
                    cmsCore::c('db')->query($sql) ;
                }
            }
        }

        cmsCore::addSessionMessage($_LANG['AD_MODULE_ADD_SITE'] , 'success');
        cmsCore::redirect('index.php?view=modules');
    }

    if ($do == 'add' || $do == 'edit') {
        if ($do == 'add') {
            cpAddPathway($_LANG['AD_MODULE_ADD']);
            echo '<h3>'. $_LANG['AD_MODULE_ADD'] .'</h3>';
            $show_all = false;
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
                if (count($_SESSION['editlist'])==0) {
                   unset($_SESSION['editlist']);
                } else {
                    $ostatok = '('. $_LANG['AD_NEXT_IN'] . count($_SESSION['editlist']) .')';
                }
            } else {
                $item_id = cmsCore::request('id', 'int', 0);
            }

            $mod = cmsCore::c('db')->get_fields('cms_modules', "id = '". $item_id ."'", '*');
            if (!$mod) { cmsCore::error404(); }
            
            $mod['hidden_menu_ids'] = cmsCore::yamlToArray($mod['hidden_menu_ids']);
            $mod['titles'] = cmsCore::yamlToArray($mod['titles']);
            
            $show_all = false;
            
            $default_position = cmsCore::c('db')->get_field('cms_modules_bind', "module_id='". $mod['id'] ."' AND menu_id=0 AND tpl='". cmsCore::c('config')->template ."'", 'position');
            
            if (!empty($default_position)) {
                $show_all = true;
                $mod['position'] = $default_position;
            }

            echo '<h3>'. $_LANG['AD_EDIT_MODULE'] . $ostatok .'</h3>';
            cpAddPathway($mod['name']);
        }

        $toolmenu[] = array( 'icon' => 'save.gif',   'title' => $_LANG['SAVE'],   'link' => 'javascript:document.addform.submit();' );
        $toolmenu[] = array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => 'javascript:history.go(-1);' );

        if (cmsCore::getArrVal($mod, 'is_external')) {
            $php_file = 'modules/'. $mod['content'] .'/backend.php';
            $xml_file = 'modules/'. $mod['content'] .'/backend.xml';
            if (file_exists($php_file) || file_exists($xml_file)) {
                $toolmenu[] = array( 'icon' => 'config.gif', 'title' => $_LANG['CONFIG_MODULE'], 'link' => '?view=modules&do=config&id='. $mod['id'] );
            }
        }

        cpToolMenu($toolmenu);
        
        $bind     = array();
        $bind_pos = array();
        $cache = 0;
        
        if ($do == 'edit') {
            $bind_sql = "SELECT * FROM cms_modules_bind WHERE module_id = ". $mod['id'] ." AND tpl = '". cmsCore::c('config')->template ."'";
            
            $bind_res = cmsCore::c('db')->query($bind_sql);
            
            while ($r = cmsCore::c('db')->fetch_assoc($bind_res)) {
                $bind[] = $r['menu_id'];
                $bind_pos[$r['menu_id']] = $r['position'];
            }
            
            $cache = cmsCore::c('cache')->get('modules', $mod['id'], $mod['content'], array(cmsCore::getArrVal($mod, 'cachetime', 1), cmsCore::getArrVal($mod, 'cacheint', 'MINUTES')));
        }
        
        $menu_sql = "SELECT * FROM cms_menu ORDER BY NSLeft, ordering";
        $menu_res = cmsCore::c('db')->query($menu_sql) ;

        $menu_items = array();

        if (cmsCore::c('db')->num_rows($menu_res)) {
            while ($item = cmsCore::c('db')->fetch_assoc($menu_res)) {
                if ($do == 'edit') {
                    if (in_array($item['id'], $bind)) {
                        $item['selected'] = true;
                        $item['position'] = $bind_pos[$item['id']];
                    }
                }

                $item['titles'] = cmsCore::yamlToArray($item['titles']);
                // переопределяем название пункта меню в зависимости от языка
                if (!empty($item['titles'][cmsCore::c('config')->lang])) {
                    $item['title'] = $item['titles'][cmsCore::c('config')->lang];
                }

                $item['title'] = str_replace($_LANG['AD_ROOT_PAGES'], $_LANG['AD_MAIN'], $item['title']);
                $menu_items[] = $item;
            }
        }
        
        
        cmsCore::c('page')->initTemplate('applets', 'modules_add')->
            assign('do', $do)->
            assign('langs', cmsCore::getDirsList('/languages'))->
            assign('pos', cpModulePositions(cmsCore::c('config')->template))->
            assign('positions_img_exist', file_exists(PATH .'/templates/'. cmsCore::c('config')->template .'/positions.jpg'))->
            assign('tpls', cmsAdmin::getModuleTemplates())->
            assign('modules_opt', $inCore->getListItems('cms_modules'))->
            assign('show_all', $show_all)->
            assign('groups', cmsUser::getGroups())->
            assign('kb_cache', !empty($cache) ? round(mb_strlen($cache)/1024, 2) : false)->
            assign('menu_items', $menu_items)->
            assign('access_list', !empty($mod['access_list']) ? $inCore->yamlToArray($mod['access_list']) : array())->
            assign('mod', $mod)->
            display();
   }
}