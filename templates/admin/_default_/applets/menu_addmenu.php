<form id="addform" name="addform" action="index.php?view=menu&do=submitmenu" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
            
    <div class="panel panel-default" style="width:650px;">
        <div class="panel-body">
            <div class="form-group">
                <label><?php echo $_LANG['AD_MODULE_MENU_TITLE']; ?></label>
                <input type="text" id="title2" class="form-control" name="title" style="width:99%" value="" />
            </div>

            <div class="form-group">
                <label><?php echo $_LANG['AD_MENU_TO_VIEW']; ?></label>
                <select id="menu" class="form-control" name="menu" style="width:99%">
                    <?php foreach ($menu_list as $menu) { ?>
                        <option value="<?php echo $menu['id']; ?>">
                            <?php echo $menu['title']; ?>
                        </option>
                    <?php } ?>
                </select>
                <div class="help-block"><?php echo $_LANG['AD_TO_CREATE_NEW_POINT']; ?></div>
            </div>
                    
            <div class="form-group">
                <label><?php echo $_LANG['AD_POSITION_TO_VIEW']; ?></label>
                <select id="position" class="form-control" name="position" style="width:99%">
                    <?php
                        if ($pos){
                            foreach($pos as $key => $position) {
                                if (cmsCore::getArrVal($mod, 'position') == $position){
                                    echo '<option value="'. $position .'" selected>'. $position .'</option>';
                                } else {
                                    echo '<option value="'. $position .'">'. $position .'</option>';
                                }
                            }
                        }
                    ?>
                </select>
                <input name="is_external" type="hidden" id="is_external" value="0" />
                <div class="help-block"><?php echo $_LANG['AD_POSITION_MUST_BE']; ?></div>
            </div>
                    
            <div class="form-group">
                <label><?php echo $_LANG['AD_MENU_PUBLIC']; ?></label>
                <label>
                    <input name="published" type="radio" value="1" checked="checked" <?php if (cmsCore::getArrVal($mod, 'published')) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['YES']; ?>
                </label>
                <label>
                    <input name="published" type="radio" value="0"  <?php if (!cmsCore::getArrVal($mod, 'published')) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
                    
            <div class="form-group">
                <label><?php echo $_LANG['AD_PREFIX_CSS']; ?></label>
                <input type="text" id="css_prefix" class="form-control" name="css_prefix" value="<?php echo cmsCore::getArrVal($mod, 'css_prefix'); ?>" style="width:99%" />
            </div>
                    
            <div class="form-group">
                <label><?php echo $_LANG['AD_TAB_ACCESS']; ?>:</label>
                <div class="help-block"><?php echo $_LANG['AD_GROUP_ACCESS'] ; ?></div>
                <label><input name="is_public" type="checkbox" id="is_public" onclick="checkAccesList()" value="1" <?php if ($do != 'edit' || !$mod['access_list']) { ?>checked="checked"<?php } ?> /> <?php echo $_LANG['AD_SHARE']; ?></label>
                <div class="help-block"><?php echo $_LANG['AD_VIEW_IF_CHECK']; ?></div>
            </div>
                    
            <div class="form-group">
                <label><?php echo $_LANG['AD_GROUPS_VIEW']; ?></label>
                <select class="form-control" style="width: 99%" name="allow_group[]" id="allow_group" size="6" multiple="multiple" <?php if ($do != 'edit' || !$mod['access_list']) { ?>disabled="disabled"<?php } ?>>
                <?php
                if ($groups) {
                    foreach ($groups as $group) {
                        echo '<option value="'. $group['id'] .'"';
                        if ($do == 'edit') {
                            if (in_array($group['id'], $access_list)) {
                                echo 'selected="selected"';
                            }
                        }
                        echo '>';
                        echo $group['title'].'</option>';
                    }
                }
                ?>
                </select>

                <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL']; ?></div>
            </div>
                    
            <div class="alert alert-info" role="alert"><?php echo $_LANG['AD_NEW_MENU_NEW_MODULE']; ?></div>
        </div>
    </div>

    <div style="margin-top:5px">
        <input class="btn btn-primary" name="save" type="submit" id="save" value="<?php echo $_LANG['AD_MENU_ADD']; ?>" />
        <input class="btn btn-default" name="back" type="button" id="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=menu';" />
    </div>
</form>