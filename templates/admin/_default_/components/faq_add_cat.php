<form id="addform" name="addform" method="post" action="index.php?view=components&amp;do=config&amp;link=faq">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_NAME_CATEGORY']; ?>:</label>
            <input type="text" class="form-control" name="title" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'title', '')); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_PARENT_CATEGORY']; ?>:</label>
            <select class="form-control" name="parent_id">
                <option value="0" <?php if (cmsCore::getArrVal($mod, 'parent_id', 0) == 0) { echo 'selected="selected"'; } ?>>--</option>
                <?php echo $faq_cats_opt; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_POST_CATEGORY']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
<?php
        if (cmsCore::getArrVal($mod, 'user', 1) == 1) {
?>
        <div class="form-group">
            <label><?php echo $_LANG['AD_DESCR_CATEGORY']; ?>:</label>
            <?php echo cmsCore::insertEditor('description', $mod['description'], '260', '605'); ?>
        </div>
<?php
        }
?>
    </div>
    
    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back3" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&link=faq';" />
        
        <input type="hidden" id="do" name="opt" value="<?php if ($opt == 'add_cat') { echo 'submit_cat'; } else { echo 'update_cat'; } ?>" />
        <?php
            if ($opt == 'edit_cat') {
                echo '<input type="hidden" name="item_id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>