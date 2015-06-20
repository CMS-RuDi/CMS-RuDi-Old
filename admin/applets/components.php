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

function cpComponentHasConfig($item) {
    return (file_exists(PATH .'/admin/components/'. $item['link'] .'/backend.php') || file_exists(PATH .'/admin/components/'. $item['link'] .'/backend_cfg.php') || file_exists(PATH .'/components/'. $item['link'] .'/backend_cfg.json'));
}

function cpComponentCanRemove($item) {
    if($item['system']) { return false; }

    global $adminAccess;

    return cmsUser::isAdminCan('admin/com_'. $item['link'], $adminAccess);
}

function applet_components() {
    $inCore = cmsCore::getInstance();
    $inDB   = cmsCore::c('db');
    $inUser = cmsCore::c('user');

    global $_LANG;

    global $adminAccess;
    
    if (!cmsUser::isAdminCan('admin/components', $adminAccess)) { cpAccessDenied(); }
    
    cmsCore::c('page')->setTitle($_LANG['AD_COMPONENTS']);
    cpAddPathway($_LANG['AD_COMPONENTS'], 'index.php?view=components');
    
    $do = cmsCore::request('do', 'str', 'list');
    
    $id   = cmsCore::request('id', 'int', 0);
    $link = cmsCore::request('link', 'str', '');
    
    if (!empty($link)) { $_REQUEST['id'] = $id = $inCore->getComponentId($link); }

    if ($do != 'list') {
        $com = $inCore->getComponent($id);
        if (!$com) { cmsCore::error404(); }
        if (!cmsUser::isAdminCan('admin/com_'. $com['link'], $adminAccess)) { cpAccessDenied(); }
    }

    if ($do == 'show') {
        cmsCore::c('db')->setFlag('cms_components', $id, 'published', '1');
        cmsCore::halt('1');
    }
    
    if ($do == 'hide') {
        cmsCore::c('db')->setFlag('cms_components', $id, 'published', '0');
        cmsCore::halt('1');
    }
    
    if ($do == 'config') {
        $file      = PATH .'/admin/components/'. $com['link'] .'/backend.php';
        $file_cfg  = PATH .'/admin/components/'. $com['link'] .'/backend_cfg.php';
        $file_json = PATH .'/admin/components/'. $com['link'] .'/backend_cfg.json';
        
        cmsCore::loadLanguage('components/'. $com['link']);
        cmsCore::loadLanguage('admin/components/'. $com['link']);
        
        cpAddPathway($com['title'] .' v'. $com['version'], '?view=components&do=config&id='. $com['id']);

        if (file_exists($file)) {
            include($file);
            return;
        } else if (file_exists($file_cfg) || file_exists($file_json)) {
            echo '<h3>'. $_LANG['AD_SETTINGS'] .': '. $com['title'] .'</h3>';
            
            if (file_exists($file_cfg)) {
                include($file_cfg);
            } else {
                $com_cfg = json_decode(file_get_contents($file_json));
            }
            
            if (!empty($com_cfg)) {
                echo '<form action="index.php?view=components&do=save_config&id='. $com['id'] .'" method="POST">';
                    echo '<div style="width:650px;">'. cmsCore::c('form_gen')->generateForm($com_cfg, $inCore->loadComponentConfig($com['link'])) .'</div>';
                    echo '<div style="margin-top:6px;">';
                        echo '<input type="submit" class="btn btn-primary" name="save" value="'. $_LANG['SAVE'] .'" /> ';
                        echo '<input type="button" class="btn btn-default" name="back" value="'. $_LANG['CANCEL'] .'" onclick="window.history.go(-1)" />';
                    echo '</div>';
                echo '</form>';
            }
            
            return;
        }
        
        cmsCore::redirect('index.php?view=components');
    }

    if ($do == 'save_config') {
        if (cmsUser::checkCsrfToken()) {
            $file_cfg  = PATH .'/admin/components/'. $com['link'] .'/backend_cfg.php';
            $file_json = PATH .'/admin/components/'. $com['link'] .'/backend_cfg.json';

            if (file_exists($file_cfg) || file_exists($file_json)) {
                if (file_exists($file_cfg)) {
                    include($file_cfg);
                } else {
                    $com_cfg = json_decode(file_get_contents($file_json), true);
                }
                
                if (!empty($com_cfg)) {
                    $config = cmsCore::c('form_gen')->requestForm($com_cfg);
                    
                    $inCore->saveComponentConfig($com['link'], $config);

                    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');

                    cmsCore::redirect('?view=components&do=config&id='. $id);
                }
            }
        }
        
        cmsCore::error404();
    }
    
    if ($do == 'list') {
        $toolmenu = array(
            array( 'icon' => 'install.gif', 'title' => $_LANG['AD_INSTALL_COMPONENTS'], 'link' => '?view=install&do=component' ),
            array( 'icon' => 'help.gif', 'title' => $_LANG['AD_HELP'], 'link' => '?view=help&topic=components' )
        );
        
        cpToolMenu($toolmenu);

        $fields = array(
            array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
            array( 'title' => $_LANG['TITLE'], 'field' => 'title','link' => '?view=components&do=config&id=%id%', 'width' => '' ),
            array( 'title' => $_LANG['AD_VERSION'], 'field' => 'version', 'width' => '80' ),
            array( 'title' => $_LANG['AD_ENABLE'], 'field' => 'published', 'width' => '80' ),
            array( 'title' => $_LANG['AD_AUTHOR'], 'field' => 'author', 'width' => '200' ),
            array( 'title' => $_LANG['AD_LINK'], 'field' => 'link', 'width' => '100' )
        );

        $actions = array(
            array( 'title' => $_LANG['AD_CONFIG'], 'icon' => 'config.gif', 'link' => '?view=components&do=config&id=%id%', 'condition' => 'cpComponentHasConfig'),
            array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'link' => '?view=install&do=remove_component&id=%id%', 'condition' => 'cpComponentCanRemove', 'confirm' => $_LANG['AD_DELETED_COMPONENT_FROM'])
        );
        
        $where = '';

        if (cmsCore::c('user')->id > 1) {
            foreach($adminAccess as $key => $value){
                if (mb_strstr($value, 'admin/com_')) {
                    if ($where) { $where .= ' OR '; }
                    $value = str_replace('admin/com_', '', $value);
                    $where .= "link='". $value ."'";
                }
            }
        }
        
        if (!$where) { $where = 'id>0'; }
        
        cpListTable('cms_components', $fields, $actions, $where);
    }
}