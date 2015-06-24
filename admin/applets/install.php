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

function pluginsList($new_plugins, $action) {
    $inCore = cmsCore::getInstance();
    global $_LANG;
    
    $items = array();
    
    foreach ($new_plugins as $plugin) {
        $plugin_obj = $inCore->loadPlugin($plugin);

        if ($action == 'install_plugin') {
            $version = $plugin_obj->info['version'];
        }

        if ($action == 'upgrade_plugin') {
            $version = $inCore->getPluginVersion($plugin) . ' &rarr; '. $plugin_obj->info['version'];
        }

        $items[] = array(
            'version'     => $version,
            'title'       => $plugin_obj->info['title'],
            'description' => $plugin_obj->info['description'],
            'author'      => $plugin_obj->info['author'],
            'folder'      => '/plugins/'. $plugin_obj->info['plugin'],
            'link'        => $plugin_obj->info['plugin']
        );
    }
    
    return $items;
}

function componentsList($new_components, $action) {
    $inCore = cmsCore::getInstance();
    global $_LANG;
    
    $items = array();
    
    foreach ($new_components as $component) {
        if ($inCore->loadComponentInstaller($component)) {
            $_component = call_user_func('info_component_'. $component);

            if ($action == 'install_component') {
                $version = $_component['version'];
            }
            
            if ($action == 'upgrade_component') {
                $version = $inCore->getComponentVersion($component) . ' &rarr; '. $_component['version'];
            }
            
            $items[] = array(
                'version'     => $version,
                'title'       => $_component['title'],
                'description' => $_component['description'],
                'author'      => $_component['author'],
                'folder'      => '/components/'. $_component['link'],
                'link'        => $_component['link']
            );
        }
    }
    
    return $items;
}

function modulesList($new_modules, $action) {
    $inCore = cmsCore::getInstance();
    global $_LANG;
    
    $items = array();
    
    foreach ($new_modules as $module) {
        if ($inCore->loadModuleInstaller($module)) {
            $_module = call_user_func('info_module_'. $module);

            if ($action == 'install_module') {
                $version = $_module['version'];
            }
            
            if ($action == 'upgrade_module') {
                $version = $inCore->getModuleVersion($module) . ' &rarr; '. $_module['version'];
            }
            
            $items[] = array(
                'version'     => $version,
                'title'       => $_module['title'],
                'description' => $_module['description'],
                'author'      => $_module['author'],
                'folder'      => '/modules/'. $_module['link'],
                'link'        => $_module['link']
            );
        }
    }
    
    return $items;
}

function applet_install() {
    $inCore = cmsCore::getInstance();
    global $_LANG;
    
    cmsCore::c('page')->setTitle($_LANG['AD_SETUP_EXTENSION']);

    $do = cmsCore::request('do', 'str', 'list');
    
    global $adminAccess;
    
    //-------------------------------- Модули ----------------------------------
    //----------- Список модулей готовых к установке или обновлению ------------
    if ($do == 'module') {
        if (!cmsUser::isAdminCan('admin/modules', $adminAccess)) { cpAccessDenied(); }

      	cpAddPathway($_LANG['AD_SETUP_MODULES'], 'index.php?view=install&do=module');

        $new_modules = $inCore->getNewModules();
        $upd_modules = $inCore->getUpdatedModules();
        
        $tpl = cmsCore::c('page')->initTemplate('applets', 'install')->
            assign('title', $_LANG['AD_SETUP_MODULES'])->
            assign('addon_type', $_LANG['AD_MODULE'])->
            assign('text1', $_LANG['AD_NO_SEARCH_MODULES'])->
            assign('text2', $_LANG['AD_IF_WANT_SETUP_MODULES'])->
            assign('text3', $new_modules ? $_LANG['AD_SEARCH_MODULES'] : $_LANG['AD_MODULES_UPDATE'])->
            assign('action_name', $new_modules ? $_LANG['AD_SETUP'] : $_LANG['AD_UPDATE'])->
            assign('action', $new_modules ? 'install_module' : 'upgrade_module');
        
        if ($new_modules) {
            $tpl->assign('items', modulesList($new_modules, 'install_module'));
        } else if ($upd_modules) {
            $tpl->assign('items', modulesList($upd_modules, 'upgrade_module'));
        }
        
        $tpl->display();
    }
    
    //--------------------------- Установка модуля -----------------------------
    if ($do == 'install_module') {

        if (!cmsUser::isAdminCan('admin/modules', $adminAccess)) { cpAccessDenied(); }

        $error = '';

        $module_id = cmsCore::request('id', 'str', '');

        if(!$module_id){ cmsCore::redirectBack(); }

        if ($inCore->loadModuleInstaller($module_id)){
            $_module = call_user_func('info_module_'.$module_id);
            //////////////////////////////////////
            $error   = call_user_func('install_module_'.$module_id);
        } else {
            $error = $_LANG['AD_MODULE_WIZARD_FAILURE'];
        }

        if ($error === true) {
            $inCore->installModule($_module, $_module['config']);
            cmsCore::addSessionMessage($_LANG['AD_MODULE'].' <strong>"'.$_module['title'].'"</strong> '.$_LANG['AD_SUCCESS'].$_LANG['AD_IS_INSTALL'], 'success');
            cmsCore::redirect('/admin/index.php?view=modules');
        } else {
            cmsCore::addSessionMessage($error , 'error');
            cmsCore::redirectBack();
        }

    }
    
    //--------------------------- Обновление модуля ----------------------------
    if ($do == 'upgrade_module') {
	if (!cmsUser::isAdminCan('admin/modules', $adminAccess)) { cpAccessDenied(); }

        $error = '';

        $module_id = cmsCore::request('id', 'str', '');

        if(!$module_id){ cmsCore::redirectBack(); }

        if ($inCore->loadModuleInstaller($module_id)) {
            $_module = call_user_func('info_module_'.$module_id);
            if (isset($_module['link'])) {
                $_module['content'] = $_module['link'];
            }
            $error = call_user_func('upgrade_module_'.$module_id);
        } else {
            $error = $_LANG['AD_SETUP_WIZARD_FAILURE'];
        }

        if ($error === true) {
            $inCore->upgradeModule($_module, $_module['config']);
            cmsCore::addSessionMessage($_LANG['AD_MODULE'].' <strong>"'.$_module['title'].'"</strong> '.$_LANG['AD_SUCCESS'].$_LANG['AD_IS_UPDATE'], 'success');
            cmsCore::redirect('/admin/index.php?view=modules');
        } else {
            cmsCore::addSessionMessage($error , 'error');
            cmsCore::redirectBack();
        }

    }
    //==========================================================================
    
    //------------------------------ Компоненты --------------------------------
    //--------- Список компонентов готовых к установке или обновлению ----------
    if ($do == 'component') {
        if (!cmsUser::isAdminCan('admin/components', $adminAccess)) { cpAccessDenied(); }

      	cpAddPathway($_LANG['AD_SETUP_COMPONENTS'], 'index.php?view=install&do=component');

        $new_components = $inCore->getNewComponents();
        $upd_components = $inCore->getUpdatedComponents();
        
        $tpl = cmsCore::c('page')->initTemplate('applets', 'install')->
            assign('title', $_LANG['AD_SETUP_COMPONENTS'])->
            assign('addon_type', $_LANG['AD_COMPONENT'])->
            assign('text1', $_LANG['AD_NO_SEARCH_COMPONENTS'])->
            assign('text2', $_LANG['AD_IF_WANT_SETUP_COMPONENTS'])->
            assign('text3', $new_components ? $_LANG['AD_COMPONENTS_SETUP'] : $_LANG['AD_COMPONENTS_UPDATE'])->
            assign('action_name', $new_components ? $_LANG['AD_SETUP'] : $_LANG['AD_UPDATE'])->
            assign('action', $new_components ? 'install_component' : 'upgrade_component');
        
        if ($new_components) {
            $tpl->assign('items', componentsList($new_components, 'install_component'));
        } else if ($upd_components) {
            $tpl->assign('items', componentsList($upd_components, 'upgrade_component'));
        }
        
        $tpl->display();
    }

    //------------------------- Установка компонента ---------------------------
    if ($do == 'install_component') {
        $error = '';

        $component = cmsCore::request('id', 'str', '');
        if (!$component) { cmsCore::redirectBack(); }

		if (!cmsUser::isAdminCan('admin/components', $adminAccess)) { cpAccessDenied(); }

        if ($inCore->loadComponentInstaller($component)) {
            $_component = call_user_func('info_component_'.$component);
            $error      = call_user_func('install_component_'.$component);
        } else {
            $error = $_LANG['AD_COMPONENT_WIZARD_FAILURE'];
        }

        if ($error === true) {
            $inCore->installComponent($_component, $_component['config']);

            $info_text = '<p>'. $_LANG['AD_COMPONENT'] .' <strong>"'. $_component['title'] .'"</strong> '. $_LANG['AD_SUCCESS'] . $_LANG['AD_IS_INSTALL'] .'</p>';
            if (isset($_component['modules'])){
                if (is_array($_component['modules'])) {
                    $info_text .= '<p>'. $_LANG['AD_OPT_INSTALL_MODULES'] .':</p>';
                    $info_text .= '<ul>';
                        foreach ($_component['modules'] as $module => $title) {
                            $info_text .= '<li>'. $title .'</li>';
                        }
                    $info_text .= '</ul>';
                }
            }
            if (isset($_component['plugins'])){
                if(is_array($_component['plugins'])){
                    $info_text .= '<p>'. $_LANG['AD_OPT_INSTALL_PLUGINS'] .':</p>';
                    $info_text .= '<ul>';
                        foreach($_component['plugins'] as $module=>$title){
                            $info_text .= '<li>'. $title .'</li>';
                        }
                    $info_text .= '</ul>';
                }
            }

            cmsCore::addSessionMessage($info_text, 'success');
            cmsCore::redirect('/admin/index.php?view=components');
        } else {
            cmsCore::addSessionMessage($error , 'error');
            cmsCore::redirectBack();
        }

    }

    //------------------------- Обновление компонента --------------------------
    if ($do == 'upgrade_component') {
        cpAddPathway($_LANG['AD_UPDATE_COMPONENTS'], 'index.php?view=install&do=component');

        $error = '';

        $component = cmsCore::request('id', 'str', '');
        if (!$component) { cmsCore::redirectBack(); }

		if (!cmsUser::isAdminCan('admin/components', $adminAccess)) { cpAccessDenied(); }
		if (!cmsUser::isAdminCan('admin/com_'.$component, $adminAccess)) { cpAccessDenied(); }

        if ($inCore->loadComponentInstaller($component)) {
            $_component = call_user_func('info_component_'.$component);
            $error      = call_user_func('upgrade_component_'.$component);
        } else {
            $error = $_LANG['AD_COMPONENT_WIZARD_FAILURE'];
        }

        if ($error === true) {
            $inCore->upgradeComponent($_component, $_component['config']);
            $info_text = $_LANG['AD_COMPONENT'].' <strong>"'.$_component['title'].'"</strong> '.$_LANG['AD_SUCCESS'].$_LANG['AD_IS_UPDATE'];
            cmsCore::addSessionMessage($info_text, 'success');
            cmsCore::redirect('/admin/index.php?view=components');
        } else {
            cmsCore::addSessionMessage($error , 'error');
            cmsCore::redirectBack();
        }

    }

    //-------------------------- Удаление компонента ---------------------------
    if ($do == 'remove_component') {
        $component_id = cmsCore::request('id', 'int', '');

        if (!$component_id) { cmsCore::redirectBack(); }
        
        $com = $inCore->getComponentById($component_id);
        if (!cmsUser::isAdminCan('admin/components', $adminAccess)) { cpAccessDenied(); }
        if (!cmsUser::isAdminCan('admin/com_'.$com, $adminAccess)) { cpAccessDenied(); }

        if ($inCore->loadComponentInstaller($com)) {
            if (function_exists('remove_component_'. $com)) {
            	call_user_func('remove_component_'. $com);
            }
        }

        $inCore->removeComponent($component_id);

        cmsCore::addSessionMessage($_LANG['AD_COMPONENT_IS_DELETED'], 'success');
        cmsCore::redirect('/admin/index.php?view=components');
    }
    //==========================================================================

    //------------------------------- Плагины ----------------------------------
    //---------- Список плагинов готовых к установке или обновлению ------------
    if ($do == 'plugin') {
        if (!cmsUser::isAdminCan('admin/plugins', $adminAccess)) { cpAccessDenied(); }

      	cpAddPathway($_LANG['AD_SETUP_PLUGINS']	, 'index.php?view=install&do=plugin');

        $new_plugins = $inCore->getNewPlugins();
        $upd_plugins = $inCore->getUpdatedPlugins();
        
        $tpl = cmsCore::c('page')->initTemplate('applets', 'install')->
            assign('title', $_LANG['AD_SETUP_PLUGINS'])->
            assign('addon_type', $_LANG['AD_PLUGIN'])->
            assign('text1', $_LANG['AD_NO_SEARCH_PLUGINS'])->
            assign('text2', $_LANG['AD_IF_WANT_SETUP_PLUGINS'])->
            assign('text3', $new_plugins ? $_LANG['AD_PLUGINS_SETUP'] : $_LANG['AD_PLUGINS_UPDATE'])->
            assign('action_name', $new_plugins ? $_LANG['AD_SETUP'] : $_LANG['AD_UPDATE'])->
            assign('action', $new_plugins ? 'install_plugin' : 'upgrade_plugin');
        
        if ($new_plugins) {
            $tpl->assign('items', pluginsList($new_plugins, 'install_plugin'));
        } else if ($upd_plugins) {
            $tpl->assign('items', pluginsList($upd_plugins, 'upgrade_plugin'));
        }
        
        $tpl->display();
    }

    //--------------------------- Установка плагина ----------------------------
    if ($do == 'install_plugin') {
        if (!cmsUser::isAdminCan('admin/plugins', $adminAccess)) { cpAccessDenied(); }

        cpAddPathway($_LANG['AD_SETUP_PLUGIN']	, 'index.php?view=install&do=plugin');

        $error = '';

        $plugin_id = cmsCore::request('id', 'str', '');

        if (!$plugin_id) { cmsCore::redirectBack(); }

        $plugin = $inCore->loadPlugin($plugin_id);

        if (!$plugin) { $error = $_LANG['AD_PLUGIN_FAILURE']	; }

        if (!$error && $plugin->install()) {
            cmsCore::addSessionMessage($_LANG['AD_PLUGIN'] .' <strong>"'. $plugin->info['title'] .'"</strong> '. $_LANG['AD_SUCCESS'] . $_LANG['AD_IS_INSTALL'] .'. '. $_LANG['AD_ENABLE_PLUGIN'], 'success');
            cmsCore::redirect('/admin/index.php?view=plugins');
        }

        if ($error) { echo '<p style="color:red">'. $error .'</p>'; }

        echo '<p><a href="index.php?view=install&do=plugin">'. $_LANG['BACK'] .'</a></p>';
    }

    //-------------------------- Обновление плагина ----------------------------
    if ($do == 'upgrade_plugin') {
        if (!cmsUser::isAdminCan('admin/plugins', $adminAccess)) { cpAccessDenied(); }

        cpAddPathway($_LANG['AD_UPDATE_PLUGIN'], 'index.php?view=install&do=plugin');

        $error = '';

        $plugin_id = cmsCore::request('id', 'str', '');

        if (empty($plugin_id)) { cmsCore::redirectBack(); }

        $plugin = $inCore->loadPlugin($plugin_id);

        if (!$plugin) { $error = $_LANG['AD_PLUGIN_FAILURE']; }

        if (!$error && $plugin->upgrade()) {
            cmsCore::addSessionMessage($_LANG['AD_PLUGIN'] .' <strong>"'. $plugin->info['title'] .'"</strong> '. $_LANG['AD_SUCCESS'] . $_LANG['AD_IS_UPDATE'], 'success');
            cmsCore::redirect('/admin/index.php?view=plugins');
        }

        if ($error) { echo '<p style="color:red">'. $error .'</p>'; }

        echo '<p><a href="index.php?view=install&do=plugin">'. $_LANG['BACK'] .'</a></p>';
    }

    //--------------------------- Удаление плагина -----------------------------
    if ($do == 'remove_plugin') {
        if (!cmsUser::isAdminCan('admin/plugins', $adminAccess)) { cpAccessDenied(); }
        
        $plugin_name = $inCore->getPluginById(cmsCore::request('id', 'int', 0));

        if (!$plugin_name) { cmsCore::redirectBack(); }
        
        $plugin = $inCore->loadPlugin($plugin_name);

        if (!$plugin) { $error = $_LANG['AD_PLUGIN_FAILURE']; }
        
        $plugin->uninstall();
        
        cmsCore::addSessionMessage($_LANG['AD_REMOVE_PLUGIN_OK'], 'success');
        cmsCore::redirect('/admin/index.php?view=plugins');
    }
    //==========================================================================
}