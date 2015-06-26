<form id="addform" name="addform" method="post" action="index.php?view=usergroups">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:650px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_GROUP_NAME'];?>:</label>
            <input type="text" class="form-control" name="title" size="30" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'title', '')); ?>" />
            <div class="help-block"><?php echo $_LANG['AD_VIEW_SITE']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALIAS'];?>:</label>
            <input type="text" class="form-control" name="alias" size="30" <?php if (cmsCore::getArrVal($mod, 'alias', '') == 'guest') { echo 'readonly="readonly"'; } ?> value="<?php echo cmsCore::getArrVal($mod, 'alias', ''); ?>" />
            <?php if ($do == 'edit') { ?>
                <div class="help-block"><?php echo $_LANG['AD_DONT_CHANGE']; ?></div>
            <?php } ?>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_IF_ADMIN'];?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if (cmsCore::getArrVal($mod, 'is_admin')) { echo 'active'; } ?>" onclick="$('#accesstable').hide();$('#admin_accesstable').show();">
                    <input type="radio" name="is_admin" <?php if (cmsCore::getArrVal($mod, 'is_admin')) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'is_admin')) { echo 'active'; } ?>" onclick="$('#accesstable').show();$('#admin_accesstable').hide();">
                    <input type="radio" name="is_admin" <?php if (!cmsCore::getArrVal($mod, 'is_admin')) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <hr>
        
        <div id="admin_accesstable" <?php if (!cmsCore::getArrVal($mod, 'is_admin')) { echo 'style="display:none;"'; } ?>>
            <div class="form-group">
                <label><?php echo $_LANG['AD_AVAILABLE_SECTIONS']; ?></label>
                
                <div style="margin-left:50px;">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_menu" name="access[]" value="admin/menu" <?php if (isset($mod['access'])) { if (in_array('admin/menu', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_MENU_CONTROL']; ?>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_modules" name="access[]" value="admin/modules" <?php if (isset($mod['access'])) { if (in_array('admin/modules', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_MODULES_CONTROL']; ?>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_content" name="access[]" value="admin/content" <?php if (isset($mod['access'])) { if (in_array('admin/content', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_CONTENTS_CONTROL']; ?>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_plugins" name="access[]" value="admin/plugins" <?php if (isset($mod['access'])) { if (in_array('admin/filters', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_CONTENTS_CONTROL']; ?>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_filters" name="access[]" value="admin/filters" <?php if (isset($mod['access'])) { if (in_array('admin/filters', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_FILTERS_CONTROL']; ?>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_components" name="access[]" value="admin/components" <?php if (isset($mod['access'])) { if (in_array('admin/components', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_COMPONENTS_CONTROL']; ?>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_users" name="access[]" value="admin/users" <?php if (isset($mod['access'])) { if (in_array('admin/users', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_USERS_CONTROL']; ?>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_config" name="access[]" value="admin/config" <?php if (isset($mod['access'])) { if (in_array('admin/config', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_SETTINGS_CONTROL']; ?>
                        </label>
                    </div>
                    
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_config" name="access[]" value="admin/tickets" <?php if (isset($mod['access'])) { if (in_array('admin/tickets', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_TICKETS_CONTROL']; ?>
                        </label>
                    </div>
                    
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="admin_config" name="access[]" value="admin/checksystem" <?php if (isset($mod['access'])) { if (in_array('admin/checksystem', $mod['access'])) { echo 'checked="checked"'; } } ?> />
                            <?php echo $_LANG['AD_CHECKSYSTEM_CONTROL']; ?>
                        </label>
                    </div>
                </div>
                
                <div class="help-block"><?php echo $_LANG['AD_ALL_SECTIONS']; ?></div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_COMPONENTS_SETTINGS_FREE']; ?></label>
                
                <div style="margin-left:50px;">
                    <?php
                        foreach ($coms as $com) {
                            if (!file_exists(PATH .'/admin/components/'. $com['link'] .'/backend.php')) { continue; }
                    ?>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="admin_com_<?php echo $com['link']; ?>" name="access[]" value="admin/com_<?php echo $com['link']; ?>" <?php if (isset($mod['access'])) { if (in_array('admin/com_'. $com['link'], $mod['access'])) { echo 'checked="checked"'; } } ?> />
                                <?php echo $com['title']; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
                
                <div class="help-block"><?php echo $_LANG['AD_COMPONENTS_SETTINGS_ON']; ?></div>
            </div>
        </div>
        
        <div id="accesstable" <?php if (cmsCore::getArrVal($mod, 'is_admin')) { echo 'style="display:none;"'; } ?>>
            <div class="form-group">
                <label><?php echo $_LANG['AD_GROUP_RULE'];?></label>
                
                <div style="margin-left:50px;">
                    <?php
                        foreach($gas as $ga) {
                            if ($mod['alias'] == 'guest' && $ga['hide_for_guest']) { continue; }
                    ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="<?php echo str_replace('/', '_', $ga['access_type']); ?>" name="access[]" value="<?php echo $ga['access_type']; ?>" <?php if (isset($mod['access'])) { if (in_array($ga['access_type'], $mod['access'])) { echo 'checked="checked"'; } } ?> />
                                <?php echo $ga['access_name']; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php if ($do == 'add') { echo $_LANG['AD_CREATE_GROUP']; } else { echo $_LANG['SAVE']; } ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL'];?>" onclick="window.history.back();"/>
        
        <input type="hidden" name="do" value="<?php if ($do == 'add') { echo 'submit'; } else { echo 'update'; } ?>" />
        <?php
            if ($do == 'edit') {
                echo '<input name="id" type="hidden" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>