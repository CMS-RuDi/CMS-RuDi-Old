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
            <input type="text" class="form-control" name="comment" maxlength="200" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'comment', '')); ?>" />
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