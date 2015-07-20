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

function applet_plugins() {
    global $_LANG;
    
    $inCore = cmsCore::getInstance();

    cmsCore::c('page')->setTitle($_LANG['AD_PLUGINS']);
    cpAddPathway($_LANG['AD_PLUGINS'], 'index.php?view=plugins');
    
    global $adminAccess;
    if (!cmsUser::isAdminCan('admin/plugins', $adminAccess)) { cpAccessDenied(); }

    $do = cmsCore::request('do', 'str', 'list');
    $id = cmsCore::request('id', 'int', -1);

    if ($do == 'hide') {
        cmsCore::c('db')->setFlag('cms_plugins', $id, 'published', '0');
        cmsCore::halt('1');
    }

    if ($do == 'show') {
        cmsCore::c('db')->setFlag('cms_plugins', $id, 'published', '1');
        cmsCore::halt('1');
    }

    if ($do == 'list') {
        $toolmenu = array(
            array( 'icon' => 'install.gif', 'title' => $_LANG['AD_INSTALL_PLUGINS'], 'link' => '?view=install&do=plugin' ),
            array( 'icon' => 'help.gif', 'title' => $_LANG['AD_HELP'], 'link' => '?view=help&topic=plugins' )
        );

        cpToolMenu($toolmenu);

        $fields = array(
            array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
            array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'link'=>'?view=plugins&do=config&id=%id%', 'width' => '250' ),
            array( 'title' => $_LANG['DESCRIPTION'], 'field' => 'description', 'width' => '' ),
            array( 'title' => $_LANG['AD_AUTHOR'], 'field' => 'author', 'width' => '160' ),
            array( 'title' => $_LANG['AD_VERSION'], 'field' => 'version', 'width' => '80' ),
            array( 'title' => $_LANG['AD_FOLDER'], 'field' => 'plugin', 'width' => '100' ),
            array( 'title' => $_LANG['AD_ENABLE'], 'field' => 'published', 'width' => '80' ),
        );

        $actions = array(
            array( 'title' => $_LANG['AD_CONFIG'], 'icon' => 'config.gif', 'link' => '?view=plugins&do=config&id=%id%' ),
            array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'link' => '?view=install&do=remove_plugin&id=%id%', 'confirm' => $_LANG['AD_REMOVE_PLUGIN_FROM'] ),
        );

        cpListTable('cms_plugins', $fields, $actions);
    }

    if ($do == 'save_config') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
        
        $plugin_name = cmsCore::request('plugin', 'str', 0);
        $plugin = $inCore->loadPlugin($plugin_name);
        
        $plugin_cfg_fields = $plugin->getConfigFields();
        
        if (empty($plugin_cfg_fields)) {
            $config = cmsCore::request('config', 'array_str');
        } else {
            $config = cmsCore::c('form_gen')->requestForm($plugin->getConfigFields());
        }

        if (!$config || !$plugin_name) { cmsCore::redirectBack(); }

        $inCore->savePluginConfig($plugin_name, $config);

        cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
        cmsCore::redirect('index.php?view=plugins');
    }
    
    if ($do == 'save_auto_config') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
        
        $plugin_name = cmsCore::request('plugin', 'str', '');
        
        $xml_file = PATH.'/plugins/'.$plugin_name.'/backend.xml';
        if(!file_exists($xml_file)){ cmsCore::error404(); }
        
        $cfg = array();
        
        $backend = simplexml_load_file($xml_file);
        
        foreach($backend->params->param as $param){
            $name    = (string) $param['name'];
            $type    = (string) $param['type'];
            $default = (string) $param['default'];
            
            switch($param['type']){
                case 'number':  $value = cmsCore::request($name, 'int', $default); break;
                case 'string':  $value = cmsCore::request($name, 'str', $default); break;
                case 'html':    $value = cmsCore::badTagClear(cmsCore::request($name, 'html', $default)); break;
                case 'flag':    $value = cmsCore::request($name, 'int', 0); break;
                case 'list':    $value = (is_array($_POST[$name]) ? cmsCore::request($name, 'array_str', $default) : cmsCore::request($name, 'str', $default)); break;
                case 'list_function': $value = cmsCore::request($name, 'str', $default); break;
                case 'list_db': $value = (is_array($_POST[$name]) ? cmsCore::request($name, 'array_str', $default) : cmsCore::request($name, 'str', $default)); break;
            }
            
            $cfg[$name] = $value;
        }
        
        if (!$cfg || !$plugin_name) { cmsCore::redirectBack(); }
        
        $inCore->savePluginConfig($plugin_name, $cfg);
        
        cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
        cmsCore::redirect('index.php?view=plugins');
    }

    if ($do == 'config') {
        $plugin_name = $inCore->getPluginById($id);
        if (!$plugin_name) { cmsCore::error404(); }

        $plugin = $inCore->loadPlugin($plugin_name);
        $config = $inCore->loadPluginConfig($plugin_name);
        $plugin_cfg_fields = array();
        
        if (method_exists($plugin, 'getConfigFields')) {
            $plugin_cfg_fields = $plugin->getConfigFields();
        }

        cmsCore::c('page')->setTitle($plugin->info['title']);
        cpAddPathway($plugin->info['title'], 'index.php?view=plugins&do=config&id='. $id);
        
        $xml_file = PATH .'/plugins/'. $plugin_name .'/backend.xml';
        
        $tpl = cmsCore::c('page')->initTemplate('applets', 'plugins_config')->
            assign('plugin_title', $plugin->info['title'])->
            assign('config', $config)->
            assign('plugin_cfg_fields', $plugin_cfg_fields)->
            assign('xml_file_exist', file_exists($xml_file))->
            assign('plugin_name', $plugin_name);
        
        if (!empty($plugin_cfg_fields)) {
            $tpl->assign('form_gen_form', cmsCore::c('form_gen')->generateForm($plugin->getConfigFields(), $config));
        } else if (file_exists($xml_file)) {
            $toolmenu[] = array('icon'=>'save.gif', 'title'=>$_LANG['SAVE'], 'link'=>'javascript:document.addform.submit();');
            $toolmenu[] = array('icon'=>'cancel.gif', 'title'=>$_LANG['CANCEL'], 'link'=>'index.php?view=modules');

            cpToolMenu($toolmenu);

            cmsCore::loadClass('formgen');

            $formGen = new cmsFormGen($xml_file, $config);

            $tpl->assign('form_gen_form', $formGen->getHTML());
        }

        $tpl->display();
    }
}