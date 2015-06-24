<form id="addform" name="addform" method="post" action="index.php?view=userbanlist">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:500px;">
        <div class="alert alert-warning">
            <strong><?php echo $_LANG['ATTENTION'];?>!</strong>
            <div><?php echo $_LANG['AD_CAUTION_INFO_0'];?></div>
            <div><?php echo $_LANG['AD_CAUTION_INFO_1'];?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_BANLIST_USER'];?>:</label>
            <select id="user_id" class="form-control" name="user_id" onchange="loadUserIp()">
                <option value="0" <?php if (!cmsCore::getArrVal($mod, 'user_id')){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_WHITHOUT_USER']; ?></option>
                <?php echo $users_opt; ?>
            </select>
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_BANLIST_IP'];?>:</label>
            <input type="text" id="ip" class="form-control" name="ip" value="<?php echo cmsCore::getArrVal($mod, 'ip', ''); ?>"/>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_BANLIST_CAUSE'];?>:</label>
            <textarea class="form-control" name="cause" rows="5"><?php echo cmsCore::getArrVal($mod, 'cause', ''); ?></textarea>
        </div>
        
        <?php $forever = false; if (!cmsCore::getArrVal($mod, 'int_num')) { $forever = true; } ?>
        
        <div class="form-group">
            <label>
                <?php echo $_LANG['AD_BAN_FOREVER'];?>
                <input type="checkbox" name="forever" value="1" <?php if ($forever){ echo 'checked="checked"'; } ?> onclick="$('#bantime').toggle();" />
            </label>
        </div>
        
        <div id="bantime" class="form-group">
            <label><?php echo $_LANG['AD_BAN_FOR_TIME'];?></label>
            <input type="number" id="int_num" class="form-control" name="int_num" min="0" value="<?php echo cmsCore::getArrVal($mod, 'int_num', 0); ?>" />
            <select id="int_period" class="form-control" name="int_period">
                <option value="MINUTE"  <?php if (mb_strstr(cmsCore::getArrVal($mod, 'int_period', ''), 'MINUTE')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['MINUTE10']; ?></option>]
                <option value="HOUR"  <?php if (mb_strstr(cmsCore::getArrVal($mod, 'int_period', ''), 'HOUR')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['HOUR10']; ?></option>
                <option value="DAY" <?php if (mb_strstr(cmsCore::getArrVal($mod, 'int_period', ''), 'DAY')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['DAY10']; ?></option>
                <option value="MONTH" <?php if (mb_strstr(cmsCore::getArrVal($mod, 'int_period', ''), 'MONTH')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['MONTH10']; ?></option>
            </select>
            <div class="checkbox">
                <label><input type="checkbox" id="autodelete" name="autodelete" value="1" <?php if ($mod['autodelete']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['AD_REMOVE_BAN'];?></label>
            </div>
            <?php if ($forever) { ?><script type="text/javascript">$('#bantime').hide();</script><?php } ?>
        </div>
    </div>

    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php if ($do == 'add') { echo $_LANG['AD_TO_BANLIST_ADD']; } else { echo $_LANG['SAVE']; } ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();"/>

        <input name="do" type="hidden" value="<?php if ($do == 'add') { echo 'submit'; } else { echo 'update'; } ?>" />
        <?php
            if ($do == 'edit') {
                echo '<input type="hidden" name="id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>