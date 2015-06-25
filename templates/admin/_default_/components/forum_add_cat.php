<?php if ($opt == 'add_cat') { ?>
    <h3><?php echo $_LANG['AD_CREATE_CATEGORY']; ?></h3>
<?php } else { ?>
    <h3><?php echo $_LANG['AD_EDIT_CATEGORY']; ?></h3>
<?php } ?>

<form id="addform" name="addform" method="post" action="index.php?view=components&amp;do=config&amp;link=forum">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:550px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_CATEGORY_NAME']; ?>:</label>
            <input type="text" class="form-control" name="title" size="30" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'title', '')); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_CATEGORY_POST']; ?>?</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SERIAL_NUMBER']; ?>:</label>
            <input type="number" class="form-control" name="ordering" size="30" value="<?php echo cmsCore::getArrVal($mod, 'ordering', ''); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_PAGETITLE']; ?>:</label>
            <textarea class="form-control" name="pagetitle" rows="2"><?php echo cmsCore::getArrVal($mod, 'pagetitle', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['SEO_PAGETITLE_HINT']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_METAKEYS']; ?>:</label>
            <textarea class="form-control" name="meta_keys" rows="2"><?php echo cmsCore::getArrVal($mod, 'meta_keys', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_METADESCR']; ?>:</label>
            <textarea class="form-control" name="meta_desc" rows="4"><?php echo cmsCore::getArrVal($mod, 'meta_desc', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
        </div>
    </div>
    
    <div>
        <input type="hidden" id="opt" name="opt" value="<?php if ($opt == 'add_cat') { echo 'submit_cat'; } else { echo 'update_cat'; } ?>" />
        
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back2" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>';" />
        <?php
            if ($opt == 'edit_cat') {
                echo '<input type="hidden" name="item_id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>