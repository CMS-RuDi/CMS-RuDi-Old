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

function pluginsList($new_plugins, $action_name, $action) {
    $inCore = cmsCore::getInstance();
    global $_LANG;

    echo '<table class="table table-striped">'
            . '<thead>'
                . '<tr>'
                    . '<th>#</th>'
                    . '<th>'. $_LANG['AD_PLUGIN'] .'</th>'
                    . '<th width="150">'. $_LANG['AD_VERSION'] .'</th>'
                    . '<th width="150">'. $_LANG['AD_AUTHOR'] .'</th>'
                    . '<th width="250">'. $_LANG['AD_FOLDER'] .'</th>'
                    . '<th width="100"></th>'
                . '</tr>'
            . '</thead><tbody>';
    
    $k = 0;
    
    foreach ($new_plugins as $plugin) {
        $plugin_obj = $inCore->loadPlugin($plugin);

        if ($action == 'install_plugin') { $version = $plugin_obj->info['version']; }
        if ($action == 'upgrade_plugin') { $version = $inCore->getPluginVersion($plugin) . ' &rarr; '. $plugin_obj->info['version']; }
        
        echo '<tr>';
                echo '<td>'. ++$k .'</td>';
                echo '<td><strong>'. $plugin_obj->info['title'] .'</strong><div class="help-block">'. $plugin_obj->info['description'] .'</div></td>';
                echo '<td>'. $version .'</td>';
                echo '<td>'. $plugin_obj->info['author'] .'</td>';
                echo '<td>/plugins/'. $plugin_obj->info['plugin'] .'</td>';
                echo '<td><a href="index.php?view=install&do='. $action .'&id='. $plugin .'" class="btn btn-primary">'. $action_name .'</a></td>';
        echo '</tr>';
    }
    
    echo '</tbody></table>';

    return;
}

function componentsList($new_components, $action_name, $action) {
    $inCore = cmsCore::getInstance();
    global $_LANG;

    echo '<table class="table table-striped">'
            . '<thead>'
                . '<tr>'
                    . '<th>#</th>'
                    . '<th>'. $_LANG['AD_COMPONENT'] .'</th>'
                    . '<th width="150">'. $_LANG['AD_VERSION'] .'</th>'
                    . '<th width="150">'. $_LANG['AD_AUTHOR'] .'</th>'
                    . '<th width="250">'. $_LANG['AD_FOLDER'] .'</th>'
                    . '<th width="100"></th>'
                . '</tr>'
            . '</thead><tbody>';
    
    $k = 0;
    
    foreach ($new_components as $component) {
        if ($inCore->loadComponentInstaller($component)) {

            $_component = call_user_func('info_component_'. $component);

            if ($action == 'install_component') { $version = $_component['version']; }
            if ($action == 'upgrade_component') { $version = $inCore->getComponentVersion($component) . ' &rarr; '. $_component['version']; }
            
            echo '<tr>';
                echo '<td>'. ++$k .'</td>';
                echo '<td><strong>'. $_component['title'] .'</strong><div class="help-block">'. $_component['description'] .'</div></td>';
                echo '<td>'. $version .'</td>';
                echo '<td>'. $_component['author'] .'</td>';
                echo '<td>/components/'. $_component['link'] .'</td>';
                echo '<td><a href="index.php?view=install&do='. $action .'&id='. $component .'" class="btn btn-primary">'. $action_name .'</a></td>';
            echo '</tr>';
        }
    }
    
    echo '</tbody></table>';

    return;
}

function modulesList($new_modules, $action_name, $action) {
    $inCore = cmsCore::getInstance();
    global $_LANG;

    echo '<table class="table table-striped">'
            . '<thead>'
                . '<tr>'
                    . '<th>#</th>'
                    . '<th>'. $_LANG['AD_MODULE'] .'</th>'
                    . '<th width="150">'. $_LANG['AD_VERSION'] .'</th>'
                    . '<th width="150">'. $_LANG['AD_AUTHOR'] .'</th>'
                    . '<th width="250">'. $_LANG['AD_FOLDER'] .'</th>'
                    . '<th width="100"></th>'
                . '</tr>'
            . '</thead><tbody>';
    
    $k = 0;
    
    foreach ($new_modules as $module) {
        if ($inCore->loadModuleInstaller($module)) {

            $_module = call_user_func('info_module_'. $module);

            if ($action == 'install_module') { $version = $_module['version']; }
            if ($action == 'upgrade_module') { $version = $inCore->getModuleVersion($module) . ' &rarr; '. $_module['version']; }

            echo '<tr>';
                echo '<td>'. ++$k .'</td>';
                echo '<td><strong>'. $_module['title'] .'</strong><div class="help-block">'. $_module['description'] .'</div></td>';
                echo '<td>'. $version .'</td>';
                echo '<td>'. $_module['author'] .'</td>';
                echo '<td>/modules/'. $_module['link'] .'</td>';
                echo '<td><a href="index.php?view=install&do='. $action .'&id='. $module .'" class="btn btn-primary">'. $action_name .'</a></td>';
            echo '</tr>';
        }
    }
    
    echo '</tbody></table>';

    return;
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

        echo '<h3>'. $_LANG['AD_SETUP_MODULES'] .'</h3>';

        if (!$new_modules && !$upd_modules) {
            echo '<p>'. $_LANG['AD_NO_SEARCH_MODULES'] .'</p>';
            echo '<p>'. $_LANG['AD_IF_WANT_SETUP_MODULES'] .'</p>';
            echo '<p><a class="btn btn-default" href="javascript:window.history.go(-1);">'. $_LANG['BACK'] .'</a></p>';
            return;
        }

        if ($new_modules) {
            echo '<div class="panel panel-default"><div class="panel-heading">'. $_LANG['AD_SEARCH_MODULES'] .'</div><div class="panel-body">';
                modulesList($new_modules, $_LANG['AD_SETUP'], 'install_module');
            echo '</div></div>';
        }

        if ($upd_modules) {
            echo '<div class="panel panel-default"><div class="panel-heading">'. $_LANG['AD_MODULES_UPDATE'] .'</div><div class="panel-body">';
                modulesList($upd_modules, $_LANG['AD_UPDATE'], 'upgrade_module');
            echo '</div></div>';
        }

        echo '<p><a class="btn btn-default" href="javascript:window.history.go(-1);">'. $_LANG['BACK'] .'</a></p>';
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

        echo '<h3>'. $_LANG['AD_SETUP_COMPONENTS'] .'</h3>';

        if (!$new_components && !$upd_components) {
            echo '<p>'. $_LANG['AD_NO_SEARCH_COMPONENTS'] .'</p>';
            echo '<p>'. $_LANG['AD_IF_WANT_SETUP_COMPONENTS'] .'</p>';
            echo '<p><a href="javascript:window.history.go(-1);" class="btn btn-default">'. $_LANG['BACK'] .'</a></p>';
            return;
        }

        if ($new_components) {
            echo '<div class="panel panel-default"><div class="panel-heading">'. $_LANG['AD_COMPONENTS_SETUP'] .'</div><div class="panel-body">';
                componentsList($new_components, $_LANG['AD_SETUP'], 'install_component');
            echo '</div></div>';
        }

        if ($upd_components) {
            echo '<div class="panel panel-default"><div class="panel-heading">'. $_LANG['AD_COMPONENTS_UPDATE'] .'</div><div class="panel-body">';
                componentsList($upd_components, $_LANG['AD_UPDATE'], 'upgrade_component');
            echo '</div></div>';

        }

        echo '<p><a href="javascript:window.history.go(-1);" class="btn btn-default">'. $_LANG['BACK'] .'</a></p>';
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

        echo '<h3>'. $_LANG['AD_SETUP_PLUGINS'] .'</h3>';

        if (!$new_plugins && !$upd_plugins) {
            echo '<p>'. $_LANG['AD_NO_SEARCH_PLUGINS'] .'</p>';
            echo '<p>'. $_LANG['AD_IF_WANT_SETUP_PLUGINS'] .'</p>';
            echo '<p><a href="javascript:window.history.go(-1);" class="btn btn-default">'. $_LANG['BACK'] .'</a></p>';
            return;
        }

        if ($new_plugins) {
            echo '<div class="panel panel-default"><div class="panel-heading">'. $_LANG['AD_PLUGINS_SETUP'] .'</div><div class="panel-body">';
                pluginsList($new_plugins, $_LANG['AD_SETUP'], 'install_plugin');
            echo '</div></div>';
        }

        if ($upd_plugins) {
            echo '<div class="panel panel-default"><div class="panel-heading">'. $_LANG['AD_PLUGINS_UPDATE'] .'</div><div class="panel-body">';
                pluginsList($upd_plugins, $_LANG['AD_UPDATE'], 'upgrade_plugin');
            echo '</div></div>';
        }

        echo '<p><a href="javascript:window.history.go(-1);" class="btn btn-default">'. $_LANG['BACK'] .'</a></p>';
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