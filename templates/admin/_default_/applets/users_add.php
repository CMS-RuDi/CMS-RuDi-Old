<form action="index.php?view=users" method="post" enctype="multipart/form-data" name="addform" id="addform">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:500px;">
        <div class="form-group">
            <label><?php echo $_LANG['LOGIN']; ?>:</label>
            <input type="text" id="logininput" class="form-control" name="login" value="<?php echo cmsCore::getArrVal($mod, 'login', ''); ?>" onchange="checkLogin()" />
            <?php if ($do == 'edit') { echo '<div class="help-block" style="text-align:right;"><a target="_blank" href="/users/'. $mod['login'] .'" title="'. $_LANG['AD_USER_PROFILE'] .'">'. $_LANG['AD_USER_PROFILE'] .'</a></div>'; } ?>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['NICKNAME']; ?>:</label>
            <input type="text" id="login" class="form-control" name="nickname" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'nickname', '')); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['EMAIL']; ?>:</label>
            <input type="text" id="nickname" class="form-control" name="email" value="<?php echo cmsCore::getArrVal($mod, 'email', ''); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php if ($do == 'edit') { echo $_LANG['AD_NEW_PASS']; } else { echo $_LANG['PASS']; } ?></label>
            <input type="password" id="pass" class="form-control" name="pass" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['REPEAT_PASS']; ?>:</label>
            <input type="password" id="pass2" class="form-control" name="pass2" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_GROUP']; ?>:</label>
            <select id="group_id" class="form-control" name="group_id">
                <?php echo $user_groups_opt; ?>
            </select>
            <?php if ($do == 'edit') { echo '<div class="help-block" style="text-align:right;"><a target="_blank" href="?view=usergroups&do=edit&id='. $mod['group_id'] .'">'. $_LANG['EDIT'] .'</a></div>'; } ?>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_IF_ACCAUNT_LOCK']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if ($mod['is_locked']) { echo 'active'; } ?>">
                    <input type="radio" name="is_locked" <?php if ($mod['is_locked']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!$mod['is_locked']) { echo 'active'; } ?>">
                    <input type="radio" name="is_locked" <?php if (!$mod['is_locked']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
    </div>

    <div>
        <?php if ($do == 'edit') { ?>
            <input type="hidden" name="do" value="update" />
            <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <?php } else { ?>
            <input type="hidden" name="do" value="submit" />
            <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['AD_USER_ADD']; ?>" />
        <?php } ?>
        <input type="button" class="btn btn-default" name="back2" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();" />

        <?php
            if ($do == 'edit') {
                echo '<input type="hidden" name="id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>