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

if (!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

class cp_cron_tasks {
    public static $cp_cron_tasks;
}
function cp_cron_get_task_name($task_id) {
    if (empty(cp_cron_tasks::$cp_cron_tasks)) {
        cp_cron_tasks::$cp_cron_tasks = array();
        $result = cmsCore::c('db')->query('SELECT id, job_name FROM cms_cron_jobs WHERE 1=1');
        while ($item = cmsCore::c('db')->fetch_assoc($result)) {
            cp_cron_tasks::$cp_cron_tasks[$item['id']] = $item['job_name'];
        }
    }
    
    return isset(cp_cron_tasks::$cp_cron_tasks[$task_id]) ? cp_cron_tasks::$cp_cron_tasks[$task_id] : $task_id;
}

function applet_cron() {
    cmsCore::loadClass('cron');
    
    global $_LANG;
    
    global $adminAccess;
    
    if (!cmsUser::isAdminCan('admin/config', $adminAccess)) { cpAccessDenied(); }

    cmsCore::c('page')->setTitle($_LANG['AD_CRON_MISSION']);
    cpAddPathway($_LANG['AD_SITE_SETTING'], 'index.php?view=config');
    cpAddPathway($_LANG['AD_CRON_MISSION'], 'index.php?view=cron');

    $do = cmsCore::request('do', 'str', 'list');
    $id = cmsCore::request('id', 'int', '0');
    
    if ($do == 'list') {
        $toolmenu = array(
            array( 'icon' => 'new.gif', 'title' => $_LANG['AD_CREATE_CRON_MISSION'], 'link' => '?view=cron&do=add' ),
            array( 'icon' => 'liststuff.gif', 'title' => $_LANG['AD_CRON_MISSION'], 'link' => '?view=cron&do=list' ),
            array( 'icon' => 'listcomments.gif', 'title' => $_LANG['AD_JOB_LOG'], 'link' => '?view=cron&do=view_log' ),
        );

        cpToolMenu($toolmenu, 'list', 'do');
        
        echo $_LANG['AD_CRON_RUN_URL'];
        echo '<pre>php -f '. PATH .'/cron.php '. str_replace(array('http://', 'https://'), '', cmsCore::c('config')->host) .' > /dev/null</pre>';
        
        $fields = array(
            array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
            array( 'title' => $_LANG['TITLE'], 'field' => 'job_name', 'width' => '80', 'link' => '?view=cron&do=view_log&id=%id%' ),
            array( 'title' => $_LANG['DESCRIPTION'], 'field' => 'comment', 'width' => '' ),
            array( 'title' => $_LANG['AD_MISSION_INTERVAL'], 'field' => 'job_interval', 'width' => '80', 'prc' => function($interval) { global $_LANG; return $interval .' '. $_LANG['HOUR']; } ),
            array( 'title' => $_LANG['AD_LAST_START'], 'field' => 'job_run_date', 'width' => '150' ),  
            array( 'title' => $_LANG['AD_IS_ACTIVE'], 'field' => 'is_enabled', 'width' => '80', 'published' => true )
        );

        $actions = array(
            array( 'title' => $_LANG['AD_PERFORM_TASK'], 'icon' => 'play.gif', 'confirm' => $_LANG['AD_PERFORM_TASK'] .' %job_name%?', 'link' => '?view=cron&do=execute&id=%id%' ),
            array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=cron&do=edit&id=%id%' ),
            array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_IF_COMENT_DELETE'], 'link' => '?view=cron&do=delete&id=%id%' )
        );

        cpListTable('cms_cron_jobs', $fields, $actions, '1=1', 'job_run_date ASC');
    }

    if ($do == 'show') {
        if ($id) { cmsCron::jobEnabled($id, true);  }
        cmsCore::halt('1');
    }
    
    if ($do == 'hide') {
        if ($id) { cmsCron::jobEnabled($id, false);  }
        cmsCore::halt('1');
    }
    
    if ($do == 'delete') {
        if ($id) { cmsCron::removeJobById($id); }
        cmsCore::redirect('index.php?view=cron');
    }
    
    if ($do == 'execute') {
        if ($id) {
            $job_result = cmsCron::executeJobById($id);
        }

        if (!empty($job_result)) {
            cmsCore::addSessionMessage($_LANG['AD_MISSION_SUCCESS'], 'success');
        } else {
            cmsCore::addSessionMessage($_LANG['AD_MISSION_ERROR'], 'error');
        }

        cmsCore::redirect('index.php?view=cron');
    }
    
    if ($do == 'submit') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $job_name     = cmsCore::request('job_name', 'str');
        $comment      = cmsCore::request('comment', 'str');
        $job_interval = cmsCore::request('job_interval', 'int');
        $enabled      = cmsCore::request('enabled', 'int');
        $component    = cmsCore::request('component', 'str');
        $model_method = cmsCore::request('model_method', 'str');
        $custom_file  = cmsCore::request('custom_file', 'str');
        $custom_file  = (mb_stripos($custom_file, 'image') || mb_stripos($custom_file, 'upload') || mb_stripos($custom_file, 'cache')) ? '' : $custom_file;
        $custom_file  = preg_replace('/\.+\//', '', $custom_file);
        $class_name   = cmsCore::request('class_name', 'str');
        $class_method = cmsCore::request('class_method', 'str');

        cmsCron::registerJob(
            $job_name,
            array(
                'interval' => $job_interval,
                'component' => $component,
                'model_method' => $model_method,
                'comment' => $comment,
                'custom_file' => $custom_file,
                'enabled' => $enabled,
                'class_name' => $class_name,
                'class_method' => $class_method
            )
        );

        cmsCore::redirect('index.php?view=cron');
    }
    
    if ($do == 'update') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        if (!$id) { cmsCore::halt(); }

        $job_name     = cmsCore::request('job_name', 'str');
        $comment      = cmsCore::request('comment', 'str');
        $job_interval = cmsCore::request('job_interval', 'int');
        $enabled      = cmsCore::request('enabled', 'int');
        $component    = cmsCore::request('component', 'str');
        $model_method = cmsCore::request('model_method', 'str');
        $custom_file  = cmsCore::request('custom_file', 'str');
        $custom_file  = (mb_stripos($custom_file, 'image') || mb_stripos($custom_file, 'upload') || mb_stripos($custom_file, 'cache')) ? '' : $custom_file;
        $custom_file  = preg_replace('/\.+\//', '', $custom_file);
        $class_name   = cmsCore::request('class_name', 'str');
        $class_method = cmsCore::request('class_method', 'str');

        cmsCron::updateJob(
            $id,
            array(
                'job_name' => $job_name,
                'job_interval' => $job_interval,
                'component' => $component,
                'model_method' => $model_method,
                'comment' => $comment,
                'custom_file' => $custom_file,
                'is_enabled' => $enabled,
                'class_name' => $class_name,
                'class_method' => $class_method
            )
        );

        cmsCore::redirect('index.php?view=cron');
    }
    
    if ($do == 'edit' || $do == 'add') {
        $toolmenu = array(
            array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.addform.submit();' ),
            array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => 'javascript:history.go(-1);' )
        );

        cpToolMenu($toolmenu);
        
        if ($do == 'edit') {
            $mod = cmsCron::getJobById($id);
            if (!$mod) { cmsCore::error404(); }
            
            echo '<h3>'. $_LANG['AD_EDIT_MISSION'] .'</h3>';
            cpAddPathway($mod['job_name'], 'index.php?view=cron&do=edit&id='. $mod['id']);
        } else {
            echo '<h3>'. $_LANG['AD_CREATE_CRON_MISSION'] .'</h3>';
            cpAddPathway($_LANG['AD_CREATE_CRON_MISSION'], 'index.php?view=cron&do=add');
            $mod = array();
	}
        
        cmsCore::c('page')->initTemplate('applets', 'cron_edit')->
            assign('do', $do)->
            assign('mod', $mod)->
            display();
    }
    
    if ($do == 'view_log') {
        $job = cmsCron::getJobById($id);
        if (empty($job) && !empty($id)) {
            cmsCore::error404();
        }
        
        $toolmenu = array(
            array( 'icon' => 'new.gif', 'title' => $_LANG['AD_CREATE_CRON_MISSION'], 'link' => '?view=cron&do=add' ),
            array( 'icon' => 'liststuff.gif', 'title' => $_LANG['AD_CRON_MISSION'], 'link' => '?view=cron&do=list' ),
            array( 'icon' => 'listcomments.gif', 'title' => $_LANG['AD_JOB_LOG'], 'link' => '?view=cron&do=view_log' ),
            array( 'icon' => 'delete.gif', 'title' => $_LANG['AD_CLEAR_LOG'], 'link' => '?view=cron&do=clear_log'. (!empty($job['id']) ? '&id='. $id : '') )
        );

        cpToolMenu($toolmenu, 'list', 'do');
        
        cpAddPathway($_LANG['AD_JOB_LOG'] . (!empty($job) ? ': '. $job['job_name'] : ''), 'index.php?view=cron&do=view_log'. (!empty($job['id']) ? '&id='. $id : ''));
        
        $fields = array(
            array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
            array( 'title' => $_LANG['AD_JOB_NAME'], 'field' => 'cron_id', 'width' => '80', 'prc' => 'cp_cron_get_task_name' ),
            array( 'title' => $_LANG['AD_MSG'], 'field' => 'msg', 'width' => '' ),
            array( 'title' => $_LANG['AD_RUN_DATE'], 'field' => 'run_date', 'width' => '150' )
        );

        $actions = array();

        cpListTable('cms_cron_logs', $fields, $actions, (!empty($job['id']) ? 'cron_id='. $job['id'] : '1=1'), 'run_date DESC');
    }
    
    if ($do == 'clear_log') {
        $job = cmsCron::getJobById($id);
        if (empty($job) && !empty($id)) {
            cmsCore::error404();
        }
        
        cmsCore::c('db')->delete('cms_cron_logs', (!empty($id) ? 'cron_id='. $id : '1=1'));
        
        cmsCore::addSessionMessage($_LANG['AD_CRON_LOGS_SUCCESS_CLEAR']);
        cmsCore::redirectBack();
    }
} 