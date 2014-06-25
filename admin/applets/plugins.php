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

if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function applet_plugins(){

    global $_LANG;

    $inCore = cmsCore::getInstance();

    cmsCore::c('page')->setAdminTitle($_LANG['AD_PLUGINS']);
    cpAddPathway($_LANG['AD_PLUGINS'], 'index.php?view=plugins');

	global $adminAccess;
	if (!cmsUser::isAdminCan('admin/plugins', $adminAccess)) { cpAccessDenied(); }

	$do = cmsCore::request('do', 'str', 'list');
	$id = cmsCore::request('id', 'int', -1);

// ===================================================================================== //

	if ($do == 'hide'){
            cmsCore::c('db')->setFlag('cms_plugins', $id, 'published', '0');
            cmsCore::halt('1');
	}

// ===================================================================================== //

	if ($do == 'show'){
            cmsCore::c('db')->setFlag('cms_plugins', $id, 'published', '1');
            cmsCore::halt('1');
	}

// ===================================================================================== //

	if ($do == 'list'){

		$toolmenu = array();
		$toolmenu[1]['icon']    = 'install.gif';
		$toolmenu[1]['title']   = $_LANG['AD_INSTALL_PLUGINS'];
		$toolmenu[1]['link']    = '?view=install&do=plugin';

		cpToolMenu($toolmenu);

        $plugin_id = cmsCore::request('installed', 'str', '');

        if ($plugin_id){
            $task = cmsCore::request('task', 'str', 'install');

            if ($task == 'install' || $task == 'upgrade'){
                $plugin     = $inCore->loadPlugin($plugin_id);
                $task_str   = ($task=='install') ? $_LANG['AD_IS_INSTALL'] : $_LANG['AD_IS_UPDATE'];
                echo '<div style="color:green;margin-top:12px;margin-bottom:5px;">'.$_LANG['AD_PLUGIN'].' <strong>"'.$plugin->info['title'].'"</strong> '.$task_str.'. '.$_LANG['AD_ENABLE_PLUGIN'].'.</div>';
            }

            if ($task == 'remove'){
                echo '<div style="color:green;margin-top:12px;margin-bottom:5px;">'.$_LANG['AD_REMOVE_PLUGIN_OK'].'.</div>';
            }
        }

		$fields = array();

		$fields[0]['title'] = 'id';			$fields[0]['field'] = 'id';			$fields[0]['width'] = '20';
		$fields[1]['title'] = $_LANG['TITLE'];       $fields[1]['field'] = 'title';		  $fields[1]['width'] = '250';
		$fields[2]['title'] = $_LANG['DESCRIPTION']; $fields[2]['field'] = 'description'; $fields[2]['width'] = '';
		$fields[3]['title'] = $_LANG['AD_AUTHOR'];   $fields[3]['field'] = 'author';	  $fields[3]['width'] = '160';
		$fields[4]['title'] = $_LANG['AD_VERSION'];  $fields[4]['field'] = 'version';     $fields[4]['width'] = '50';
        $fields[5]['title'] = $_LANG['AD_FOLDER'];   $fields[5]['field'] = 'plugin';      $fields[5]['width'] = '100';
		$fields[6]['title'] = $_LANG['AD_ENABLE'];	 $fields[6]['field'] = 'published';	  $fields[6]['width'] = '60';

		$actions = array();
		$actions[0]['title'] = $_LANG['AD_CONFIG'];
		$actions[0]['icon']  = 'config.gif';
		$actions[0]['link']  = '?view=plugins&do=config&id=%id%';

		$actions[1]['title']   = $_LANG['DELETE'];
		$actions[1]['icon']    = 'delete.gif';
		$actions[1]['confirm'] = $_LANG['AD_REMOVE_PLUGIN_FROM'];
		$actions[1]['link']    = '?view=install&do=remove_plugin&id=%id%';

		cpListTable('cms_plugins', $fields, $actions);

	}

// ===================================================================================== //

    if ($do == 'save_config'){
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
        $plugin_name    = cmsCore::request('plugin', 'str', 0);
        $config         = cmsCore::request('config', 'array_str');

        if (!$config || !$plugin_name) { cmsCore::redirectBack(); }

        $inCore->savePluginConfig($plugin_name, $config);

        cmsUser::clearCsrfToken();

		cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');

        cmsCore::redirect('index.php?view=plugins');

    }

// ===================================================================================== //

    if ($do == 'config'){

        $plugin_name = $inCore->getPluginById($id);
        if(!$plugin_name){ cmsCore::error404(); }

        $plugin = $inCore->loadPlugin($plugin_name);
        $config = $inCore->loadPluginConfig($plugin_name);

        cmsCore::c('page')->setAdminTitle($plugin->info['title']);
        cpAddPathway($plugin->info['title'], 'index.php?view=plugins&do=config&id='.$id);

        echo '<h3>'.$plugin->info['title'].'</h3>';

        if (!$config) {
            echo '<p>'.$_LANG['AD_PLUGIN_DISABLE'].'.</p>';
            echo '<p><a href="javascript:window.history.go(-1);">'.$_LANG['BACK'].'</a></p>';
            return;

        }

        echo '<form action="index.php?view=plugins&do=save_config&plugin='.$plugin_name.'" method="POST">';
            echo '<input type="hidden" name="csrf_token" value="'.cmsUser::getCsrfToken().'" />';

            echo '<table class="proptable" width="605" cellpadding="8" cellspacing="0" border="0">';
                foreach ($config as $field=>$value){
                    echo '<tr>';
                        echo '<td width="150"><strong>'.(isset($_LANG[mb_strtoupper($field)]) ? $_LANG[mb_strtoupper($field)] : $field).':</strong></td>';
                        echo '<td><input type="text" style="width:90%" name="config['.$field.']" value="'.htmlspecialchars($value).'" /></td>';
                    echo '</tr>';
                }
            echo '</table>';

            echo '<div style="margin-top:6px;">';
                echo '<input type="submit" name="save" value="'.$_LANG['SAVE'].'" /> ';
                echo '<input type="button" name="back" value="'.$_LANG['CANCEL'].'" onclick="window.history.go(-1)" />';
            echo '</div>';

        echo '</form>';

    }

// ===================================================================================== //

}

?>