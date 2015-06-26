<div style="width:650px;">
    <form action="index.php?view=components&do=config&id=<?php echo $id; ?>&opt=saveconfig" method="post" name="optform" target="_self" id="form1">
        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />

        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_TARGET']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                <label class="btn btn-default <?php if ($cfg['show_target']) { echo 'active'; } ?>">
                    <input type="radio" name="show_target" <?php if ($cfg['show_target']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!$cfg['show_target']) { echo 'active'; } ?>">
                    <input type="radio" name="show_target" <?php if (!$cfg['show_target']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_COUNT_ACTIONS_PAGE']; ?>:</label>
            <input class="form-control" name="perpage" size=5 value="<?php echo $cfg['perpage'];?>" />
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_COUNT_ACTIONS_TAB']; ?>:</label>
            <input class="form-control" name="perpage_tab" size=5 value="<?php echo $cfg['perpage_tab'];?>" />
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_ACTIONS_TYPE']; ?>:</label>
            <div class="param-links">
                <label for="is_all"><input type="checkbox" id="is_all" name="is_all" value="1" <?php if($cfg['is_all']) {?>checked="checked" <?php }?> /> <a href="javascript:void(0);" onclick="$('#act_list label input:checkbox, #is_all').prop('checked', true);"><?php echo $_LANG['SELECT_ALL']; ?></a></label> |
                <a href="javascript:void(0);" onclick="$('#act_list label input:checkbox, #is_all').prop('checked', false);"><?php echo $_LANG['REMOVE_ALL']; ?></a>
            </div>
            <div id="act_list" style="margin-left:30px;">
                <?php
                    if (!empty($act_lists)) {
                        foreach ($act_lists as $option) {
                            echo '<label><input type="checkbox" id="act_type_'. $option['name'] .'" name="act_type['. $option['name'] .']" value="'. $option['id'] .'" '.(in_array($option['id'], cmsCore::getArrVal($cfg, 'act_type', array())) ? 'checked="checked"' : '') .' />'. $option['title'] .'</label><br/>';
                        }
                    }
                ?>
            </div>
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_ROOT_METAKEYS']; ?>:</label>
            <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
            <textarea class="form-control" name="meta_keys" rows="2"><?php echo $cfg['meta_keys'] ?></textarea>
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_ROOT_METADESC']; ?>:</label>
            <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
            <textarea class="form-control" name="meta_keys" rows="2"><?php echo $cfg['meta_desc'] ?></textarea>
        </div>

        <p>
            <input type="submit" id="save" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
            <input type="button" id="back" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';"/>
        </p>
    </form>
</div>