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

    cmsCore::c('page')->setAdminTitle($_LANG['AD_MODULES']);
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

        echo '<h3>'. $module_title .'</h3>';
        
        $toolmenu = array(
            array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:submitModuleConfig();' ),
            array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => 'index.php?view=modules' ),
            array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_MODULE_VIEW'], 'link' => '?view=modules&do=edit&id='. $id )
        );

        cpToolMenu($toolmenu);
?>
        <form action="index.php?view=modules&do=save_auto_config&id=<?php echo $id; ?>" method="post" name="optform" target="_self" id="optform">
            <div class="panel panel-default" style="width:650px;">
                <div class="panel-body">
<?php
        if (file_exists($xml_file)) {
            cmsCore::loadClass('formgen');
            $formGen = new cmsFormGen($xml_file, $cfg);
            echo $formGen->getHTML();
        } else {
?>
                    <div class="form-group">
                        <label class="col-sm-5 control-label"><?php echo $_LANG['AD_MODULE_TEMPLATE']; ?></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" value="<?php echo $cfg['tpl']; ?>" />
                        </div>
                    </div>
<?php
        }
?>
                </div>
                <div class="panel-footer">
                    <input type="submit" name="save" class="btn btn-primary" value="<?php echo $_LANG['SAVE']; ?>" />
                </div>
            </div>
        <script type="text/javascript">
            function submitModuleConfig(){
                $('#optform').submit();
            }
        </script>
        </form>
<?php

        return;
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

//============================================================================//
//============================================================================//

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

//============================================================================//
//============================================================================//

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
        if (!isset($_REQUEST['item'])) {
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
                    'menu_id' => 0,
                    'position' => $module['position'],
                    'tpl' => cmsCore::c('config')->template
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
                            'menu_id' => $value,
                            'position' => $showpos[$value],
                            'tpl' => cmsCore::c('config')->template
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
        $langs = cmsCore::getDirsList('/languages');
        
        if ($do == 'add') {
            cpAddPathway($_LANG['AD_MODULE_ADD']);
            echo '<h3>'. $_LANG['AD_MODULE_ADD'].'</h3>';
            $show_all = false;
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
               if (sizeof($_SESSION['editlist'])==0) { unset($_SESSION['editlist']); } else
               { $ostatok = '('.$_LANG['AD_NEXT_IN'].sizeof($_SESSION['editlist']).')'; }
            } else { $item_id = cmsCore::request('id', 'int', 0); }

            $mod = cmsCore::c('db')->get_fields('cms_modules', "id = '$item_id'", '*');
            if (!$mod){ cmsCore::error404(); }
            
            $mod['hidden_menu_ids'] = cmsCore::yamlToArray($mod['hidden_menu_ids']);
            $mod['titles'] = cmsCore::yamlToArray($mod['titles']);

            $sql = "SELECT id FROM cms_modules_bind WHERE module_id = $id AND menu_id = 0 AND tpl = '". cmsConfig::getConfig('template') ."' LIMIT 1";
            $result = cmsCore::c('db')->query($sql) ;
            if (cmsCore::c('db')->num_rows($result)) { $show_all = true; } else { $show_all = false; }

            echo '<h3>'.$_LANG['AD_EDIT_MODULE'].$ostatok.'</h3>';
            cpAddPathway($mod['name']);
        }

        $toolmenu[] = array('icon'=>'save.gif', 'title'=>$_LANG['SAVE'], 'link'=>'javascript:document.addform.submit();');
        $toolmenu[] = array('icon'=>'cancel.gif', 'title'=>$_LANG['CANCEL'], 'link'=>'javascript:history.go(-1);');

        if (@$mod['is_external']) {
            $php_file = 'modules/'.$mod['content'].'/backend.php';
            $xml_file = 'modules/'.$mod['content'].'/backend.xml';
            if (file_exists($php_file) || file_exists($xml_file)){
                $toolmenu[] = array('icon'=>'config.gif', 'title'=>$_LANG['CONFIG_MODULE'], 'link'=>'?view=modules&do=config&id='.$mod['id']);
            }
        }

        cpToolMenu($toolmenu);

?>
    <form id="addform" name="addform" method="post" action="index.php">
        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
        <input type="hidden" name="view" value="modules" />

        <table class="table">
            <tr><td>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_MODULE_TITLE']; ?> (<input type="checkbox" class="uittip" title="<?php echo $_LANG['AD_VIEW_TITLE'];?>" name="showtitle" <?php if ($mod['showtitle'] || $do == 'add') { echo 'checked="checked"'; } ?> value="1" />)</label>
                            <input type="text" id="title" class="form-control" style="width:100%" name="title" value="<?php echo htmlspecialchars($mod['title']);?>" />
                            <div class="help-block"><?php echo $_LANG['AD_VIEW_IN_SITE']; ?></div>
                        </div>
                        
                        <?php if (count($langs) > 1) { ?>
                            <label><?php echo $_LANG['AD_LANG_TITLES']; ?></label>
                            <?php foreach ($langs as $lang) { ?>
                                <div>
                                    <strong><?php echo $lang; ?>:</strong>
                                    <input name="titles[<?php echo $lang; ?>]" type="text" style="width:97%" value="<?php echo htmlspecialchars($mod['titles'][$lang]); ?>" placeholder="<?php echo $_LANG['AD_HINT_DEFAULT']; ?>" />
                                </div>
                            <?php } ?>
                            <div class="help-block"><?php echo $_LANG['AD_LANG_TITLES_HINT']; ?></div>
                        <?php } ?> 
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_MODULE_NAME']; ?></label>
                            <?php if (!isset($mod['user']) || @$mod['user'] == 1) { ?>
                                <input type="text" id="name" class="form-control" style="width:99%" name="name" value="<?php echo htmlspecialchars($mod['name']);?>" />
                            <?php } else { ?>
                                <input type="text" id="name" class="form-control" style="width:99%" name="" value="<?php echo @$mod['name'];?>" disabled="disabled" />
                                <input type="hidden" name="name" value="<?php echo htmlspecialchars($mod['name']);?>" />
                            <?php } ?>
                            <div class="help-block"><?php echo $_LANG['AD_SHOW_ADMIN']; ?></div>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_PREFIX_CSS']; ?></label>
                            <input type="text" id="css_prefix" class="form-control" style="width:154px" name="css_prefix" value="<?php echo @$mod['css_prefix'];?>" />
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_DEFOLT_VIEW']; ?></label>
                            <?php
                                $pos = cpModulePositions(cmsConfig::getConfig('template'));
                            ?>
                            
                            <select id="position" class="form-control" style="width:100%" name="position">
                                <?php
                                    if ($pos){
                                        foreach($pos as $key=>$position) {
                                            if (@$mod['position']==$position) {
                                                echo '<option value="'.$position.'" selected>'.$position.'</option>';
                                            } else {
                                                echo '<option value="'.$position.'">'.$position.'</option>';
                                            }
                                        }
                                    }
                                ?>
                            </select>
                            
                            <div class="help-block">
                                <?php echo $_LANG['AD_POSITION_MUST_BE']; ?>
                                <?php if (file_exists(PATH .'/templates/'. cmsCore::c('config')->template .'/positions.jpg')) { ?>
                                    <a href="#myModal" role="button" class="btn btn-sm btn-default" data-toggle="modal"><?php echo $_LANG['AD_SEE_VISUALLY']; ?></a>
                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    <h4 class="modal-title" id="myModalLabel"><?php echo $_LANG['AD_TPL_POS']; ?></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="/templates/<?php echo cmsCore::c('config')->template; ?>/positions.jpg" alt="<?php echo $_LANG['AD_TPL_POS']; ?>" style="width:100%;height:auto;" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_MODULE_TEMPLATE']; ?></label>
                            <?php
                                $tpls = cmsAdmin::getModuleTemplates();
                            ?>
                            <select id="template" class="form-control" style="width:100%" name="template">
                                <?php
                                    foreach ($tpls as $tpl) {
                                        $selected = ($mod['template'] == $tpl || (!$mod['template'] && $tpl == 'module' )) ? 'selected="selected"' : '';
                                        echo '<option value="'. $tpl .'" '. $selected .'>'. $tpl .'</option>';
                                    }
                                ?>
                            </select>
                            <div class="help-block"><?php echo $_LANG['AD_FOLDER_MODULES'];?></div>
                        </div>
                        
                        <?php if ($do == 'add') { ?>
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_MODULE_TYPE']; ?></label>
                            <select id="operate" class="form-control" style="width:100%" name="operate" onchange="checkDiv()" >
                                <option value="user" selected="selected"><?php echo $_LANG['AD_MODULE_TYPE_NEW'];?></option>
                                <option value="clone"><?php echo $_LANG['AD_MODULE_TYPE_COPY'];?></option>
                            </select>
                        </div>
                        <?php } ?>
                        
                        <?php if (!isset($mod['user']) || $mod['user'] == 1 || $do == 'add') { ?>
                        <div id="user_div" class="form-group">
                            <label><?php echo $_LANG['AD_MODULE_CONTENT']; ?></label>
                            <div><?php insertPanel(); ?></div>
                            <div><?php $inCore->insertEditor('content', $mod['content'], '250', '100%'); ?></div>
                        </div>
                        <?php } ?>
                        
                        <div id="clone_div" class="form-group" style="display:none;">
                            <label><?php echo $_LANG['AD_MODULE_COPY']; ?></label>
                            <select id="clone_id" class="form-control" style="width:100%" name="clone_id">
                                <?php
                                    echo $inCore->getListItems('cms_modules');
                                ?>
                            </select>
                            <label>
                                <input type="checkbox" name="del_orig" value="1" />
                                <?php echo $_LANG['AD_ORIGINAL_MODULE_DELETE'];?>
                            </label>
                        </div>
                    </div>
                </div>
            </td>

            <!-- боковая ячейка -->
            <td width="400" valign="top">
                <div class="uitabs">
                    <ul id="tabs">
                        <li><a href="#upr_publish"><span><?php echo $_LANG['AD_TAB_PUBLISH']; ?></span></a></li>
                        
                        <?php if (($mod['is_external'] && $do == 'edit') || $do == 'add') { ?>
                        <li><a href="#upr_cache"><span><?php echo $_LANG['AD_MODULE_CACHE']; ?></span></a></li>
                        <?php } ?>
                        
                        <li><a href="#upr_access"><span><?php echo $_LANG['AD_TAB_ACCESS']; ?></span></a></li>
                    </ul>
                    
                    <div id="upr_publish">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="published" id="published" value="1" <?php if ($mod['published'] || $do=='add') { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_MODULE_PUBLIC'];?>
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input name="show_all" id="show_all" type="checkbox" value="1"  onclick="checkGroupList()" <?php if ($show_all) { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_VIEW_ALL_PAGES'];?>
                            </label>
                        </div>
                        
                        <?php
                            if ($do == 'edit') {
                                $bind_sql = "SELECT * FROM cms_modules_bind WHERE module_id = ". $mod['id'] ." AND tpl = '". cmsConfig::getConfig('template') ."'";
                                $bind_res = cmsCore::c('db')->query($bind_sql);
                                $bind     = array();
                                $bind_pos = array();
                                while ($r = cmsCore::c('db')->fetch_assoc($bind_res)) {
                                    $bind[] = $r['menu_id'];
                                    $bind_pos[$r['menu_id']] = $r['position'];
                                }
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
                        ?>
                        
                        <div id="grp" class="form-group">
                            <label>
                                <span class="show_list"><?php echo $_LANG['AD_WHERE_MODULE_VIEW'];?></span>
                                <span class="hide_list"><?php echo $_LANG['AD_WHERE_MODULE_NOT_VIEW'];?></span>
                            </label>
                            <div style="height:400px;overflow: auto;border: solid 1px #999; padding:5px 10px; background: #FFF;">
                                <table class="table">
                                    <tr>
                                        <td colspan="2" height="25"><strong><?php echo $_LANG['AD_MENU'];?></strong></td>
                                        <td class="show_list" align="center" width="50"><strong><?php echo $_LANG['AD_POSITION'];?></strong></td>
                                    </tr>
                                    <?php foreach($menu_items as $i) { ?>
                                    <tr class="show_list">
                                        <td width="20" height="25">
                                            <input type="checkbox" name="showin[]" id="mid<?php echo $i['id']; ?>" value="<?php echo $i['id']; ?>" <?php if ($i['selected']){ ?>checked="checked"<?php } ?> onclick="$('#p<?php echo $i['id']; ?>').toggle()"/>
                                        </td>
                                        <td style="padding-left:<?php echo ($i['NSLevel'])*6-6; ?>px"><label for="mid<?php echo $i['id']; ?>"><?php echo $i['title']; ?></label></td>
                                        <td align="center">
                                            <select id="p<?php echo $i['id']; ?>" name="showpos[<?php echo $i['id']; ?>]" style="<?php if (!$i['selected']) { ?>display:none<?php } ?>">
                                                <?php foreach($pos as $position){ ?>
                                                    <option value="<?php echo $position; ?>" <?php if ($i['position']==$position){ ?>selected="selected"<?php } ?>><?php echo $position; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <?php foreach($menu_items as $it) { ?>
                                    <tr class="hide_list">
                                        <td width="20" height="25">
                                            <input type="checkbox" name="hidden_menu_ids[]" id="hmid<?php echo $it['id']; ?>" value="<?php echo $it['id']; ?>" <?php if (in_array($it['id'], $mod['hidden_menu_ids'])){ ?>checked="checked"<?php } ?> />
                                        </td>
                                        <td style="padding-left:<?php echo ($it['NSLevel'])*6-6; ?>px"><label for="hmid<?php echo $it['id']; ?>"><?php echo $it['title']; ?></label></td>
                                    </tr>
                                    <?php } ?>
                                </table>
                            </div>
                            <label class="show_list">
                                <input type="checkbox" name="is_strict_bind" id="is_strict_bind" value="1" <?php if ($mod['is_strict_bind']) { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_DONT_VIEW']; ?>
                            </label>
                            <label class="hide_list">
                                <input type="checkbox" name="is_strict_bind_hidden" id="is_strict_bind_hidden" value="1" <?php if ($mod['is_strict_bind_hidden']) { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_EXCEPT_NESTED']; ?>
                            </label>
                        </div>
                    </div>
                    
                    <?php if (($mod['is_external'] && $do == 'edit') || $do == 'add') { ?>
                    <div id="upr_cache">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_DO_MODULE_CACHE']; ?></label>
                            <select id="cache" class="form-control" style="width:100%" name="cache">
                                <option value="0" <?php if (!cmsCore::getArrVal($mod, 'cache')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['NO']; ?></option>
                                <option value="1" <?php if (cmsCore::getArrVal($mod, 'cache')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['YES']; ?></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_MODULE_CACHE_PERIOD']; ?></label>
                            <table class="table">
                                <tr>
                                    <td valign="top"  width="100">
                                        <input id="int_1" class="form-control" style="width:99%" name="cachetime" type="text" value="<?php echo cmsCore::getArrVal($mod, 'cachetime', 0); ?>"/>
                                    </td>
                                    <td valign="top" style="padding-left:5px">
                                        <select id="int_2" class="form-control" style="width:100%" name="cacheint">
                                            <option value="MINUTE"  <?php if(mb_strstr(cmsCore::getArrVal($mod, 'cacheint', 'MINUTES'), 'MINUTE')) { echo 'selected="selected"'; } ?>><?php echo cmsCore::spellCount(cmsCore::getArrVal($mod, 'cachetime', 0), $_LANG['MINUTE1'], $_LANG['MINUTE2'], $_LANG['MINUTE10'], false); ?></option>
                                            <option value="HOUR"  <?php if(mb_strstr(cmsCore::getArrVal($mod, 'cacheint', 'MINUTES'), 'HOUR')) { echo 'selected="selected"'; } ?>><?php echo cmsCore::spellCount(cmsCore::getArrVal($mod, 'cachetime', 0), $_LANG['HOUR1'], $_LANG['HOUR2'], $_LANG['HOUR10'], false); ?></option>
                                            <option value="DAY" <?php if(mb_strstr(cmsCore::getArrVal($mod, 'cacheint', 'MINUTES'), 'DAY')) { echo 'selected="selected"'; } ?>><?php echo cmsCore::spellCount(cmsCore::getArrVal($mod, 'cachetime', 0), $_LANG['DAY1'], $_LANG['DAY2'], $_LANG['DAY10'], false); ?></option>
                                            <option value="MONTH" <?php if(mb_strstr(cmsCore::getArrVal($mod, 'cacheint', 'MINUTES'), 'MONTH')) { echo 'selected="selected"'; } ?>><?php echo cmsCore::spellCount(cmsCore::getArrVal($mod, 'cachetime', 0), $_LANG['MONTH1'], $_LANG['MONTH2'], $_LANG['MONTH10'], false); ?></option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <div style="margin-top:15px">
                                <?php
                                    if ($do == 'edit') {
                                        $cache = cmsCore::c('cache')->get('modules', $mod['id'], $mod['content'], array(cmsCore::getArrVal($mod, 'cachetime', 1), cmsCore::getArrVal($mod, 'cacheint', 'MINUTES')));
                                        
                                        if (!empty($cache)){
                                            $kb = round(mb_strlen($cache)/1024, 2);
                                            unset($cache);
                                            echo '<a href="index.php?view=cache&component=modules&target='. $mod['content'] .'&target_id='. $mod['id'] .'">'. $_LANG['AD_MODULE_CACHE_DELETE'] .'</a> ('. $kb . $_LANG['SIZE_KB'] .')';
                                        } else {
                                            echo '<span style="color:gray">'. $_LANG['AD_NO_CACHE'] .'</span>';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <div id="upr_access">
                        <div class="form-group">
                            <?php
                                $groups = cmsUser::getGroups();
                                $style  = 'disabled="disabled"';
                                $public = 'checked="checked"';

                                if ($do == 'edit') {
                                    if ($mod['access_list']) {
                                        $public = '';
                                        $style  = '';
                                        $access_list = $inCore->yamlToArray($mod['access_list']);
                                    }
                                }
                            ?>
                            <label>
                                <input name="is_public" type="checkbox" id="is_public" onclick="checkAccesList()" value="1" <?php echo $public; ?> />
                                <?php echo $_LANG['AD_SHARE']; ?>
                            </label>
                            <div class="help-block"><?php echo $_LANG['AD_IF_CHECKED']; ?></div>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_GROUPS_VIEW']; ?></label>
                            <?php
                                echo '<select style="width: 99%" name="allow_group[]" id="allow_group" size="6" multiple="multiple" '.$style.'>';

                                if ($groups) {
                                    foreach($groups as $group) {
                                        echo '<option value="'.$group['id'].'"';
                                        if ($do == 'edit' && $mod['access_list']) {
                                            if (in_array($group['id'], $access_list)) {
                                                echo 'selected="selected"';
                                            }
                                        }

                                        echo '>';
                                        echo $group['title'].'</option>';
                                    }
                                }

                                echo '</select>';
                            ?>
                            <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL']; ?></div>
                        </div>
                    </div>
                </div>
            </td></tr>
        </table>
        <p>
            <input type="submit" id="add_mod" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
            <input type="button" id="back" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();" />
            <input type="hidden" id="do" name="do" <?php if ($do == 'add') { echo 'value="submit"'; } else { echo 'value="update"'; } ?> />
            <?php
                if ($do == 'edit') {
                    echo '<input name="id" type="hidden" value="'. $mod['id'] .'" />';
                }
            ?>
        </p>
    </form>
<?php
   }
}