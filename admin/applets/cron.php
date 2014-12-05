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

function applet_cron() {
    cmsCore::loadClass('cron');
    
    global $_LANG;
    
    global $adminAccess;
    
    if (!cmsUser::isAdminCan('admin/config', $adminAccess)) { cpAccessDenied(); }

    cmsCore::c('page')->setAdminTitle($_LANG['AD_CRON_MISSION']);
    cpAddPathway($_LANG['AD_SITE_SETTING'], 'index.php?view=config');
    cpAddPathway($_LANG['AD_CRON_MISSION'], 'index.php?view=cron');

    $do = cmsCore::request('do', 'str', 'list');
    $id = cmsCore::request('id', 'int', '0');
    
    if ($do == 'list') {
        $toolmenu = array(
            array( 'icon' => 'new.gif', 'title' => $_LANG['AD_CREATE_CRON_MISSION'], 'link' => '?view=cron&do=add' )
        );

        cpToolMenu($toolmenu);

        $items = cmsCron::getJobs(false);

        $tpl_file   = 'admin/cron.php';
        $tpl_dir    = file_exists(TEMPLATE_DIR . $tpl_file) ? TEMPLATE_DIR : DEFAULT_TEMPLATE_DIR;

        include($tpl_dir . $tpl_file);
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
        if ($id) { $job_result = cmsCron::executeJobById($id); }

        if ($job_result) {
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
?>
<form action="index.php?view=cron" method="post" enctype="multipart/form-data" name="addform" id="addform">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:650px;">
        <div class="form-group">
            <label><?php echo $_LANG['TITLE']; ?>:</label>
            <input type="text" class="form-control" name="job_name" value="<?php echo cmsCore::getArrVal($mod, 'job_name', ''); ?>" />
            <div class="help-block"><?php echo $_LANG['AD_ONLY_LATIN']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['DESCRIPTION']; ?>:</label>
            <input type="text" class="form-control" name="comment" maxlength="200" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'comment', '')); ?>" />
            <div class="help-block"><?php echo $_LANG['AD_ONLY_200_SIMBOLS']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_MISSION_ON']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if (cmsCore::getArrVal($mod, 'is_enabled')) { echo 'active'; } ?>">
                    <input type="radio" name="enabled" <?php if ($mod['is_enabled']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'is_enabled')) { echo 'active'; } ?>">
                    <input type="radio" name="enabled" <?php if (!$mod['is_enabled']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
            <div class="help-block"><?php echo $_LANG['AD_MISSION_OFF']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_MISSION_INTERVAL']; ?> (<?php echo $_LANG['HOUR1']; ?>):</label>
            <input type="number" class="form-control" name="job_interval" min="0" value="<?php echo cmsCore::getArrVal($mod, 'job_interval', ''); ?>" /> 
            <div class="help-block"><?php echo $_LANG['AD_MISSION_PERIOD']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_PHP_FILE']; ?>:</label>
            <input type="text" class="form-control" name="custom_file" maxlength="250" value="<?php echo cmsCore::getArrVal($mod, 'custom_file', ''); ?>" /> 
            <div class="help-block"><?php echo $_LANG['AD_EXAMPLE'] ; ?>: <b>includes/myphp/test.php</b></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_COMPONENT']; ?>:</label>
            <input type="text" class="form-control" name="component" maxlength="250" value="<?php echo cmsCore::getArrVal($mod, 'component', ''); ?>" /> 
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_METHOD']; ?>:</label>
            <input type="text" class="form-control" name="model_method" maxlength="250" value="<?php echo cmsCore::getArrVal($mod, 'model_method', ''); ?>" /> 
        </div>
        
        <div class="form-group">
            <label><?php echo icms_ucfirst($_LANG['AD_CLASS']); ?></label>
            <input type="text" class="form-control" name="class_name" maxlength="50" value="<?php echo cmsCore::getArrVal($mod, 'class_name', ''); ?>" />
            <div class="help-block">
                <span style="color:#666;font-family: mono"><?php echo $_LANG['AD_FILE_CLASS']; ?></span>, <?php echo $_LANG['AD_EXAMPLE']; ?> <b>actions|cmsActions</b>&nbsp;<?php echo $_LANG['OR']; ?><br/>
                <span style="color:#666;font-family: mono"><?php echo $_LANG['AD_CLASS']; ?></span>, <?php echo $_LANG['AD_EXAMPLE']; ?> <b>cmsDatabase</b>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_CLASS_METHOD']; ?>:</label>
            <input type="text" class="form-control" name="class_method" maxlength="50" value="<?php echo cmsCore::getArrVal($mod, 'class_method', ''); ?>" /> 
        </div>
    </div>
    
    <div>
        <?php if ($do == 'edit') { ?>
            <input type="hidden" name="do" value="update" />
            <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['AD_SAVE_CRON_MISSION']; ?>" />
        <?php } else { ?>
            <input type="hidden" name="do" value="submit" />
            <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['AD_CREATE_CRON_MISSION'] ; ?>" />
        <?php } ?>
        
        <input type="button" class="btn btn-default" name="back2" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();" />
          
        <?php
            if ($do == 'edit') {
                echo '<input type="hidden" name="id" value="'. $mod['id'] .'" />';
            }
        ?>
        </div>
</form>
<?php
   }
}