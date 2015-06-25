<form action="index.php?view=components&amp;do=config&amp;link=geo" method="post" name="optform">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:500px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_AUTODETECT']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'autodetect', false)) { echo 'active'; } ?>">
                    <input type="radio" name="autodetect" <?php if(cmsCore::getArrVal($cfg, 'autodetect', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'autodetect', false)) { echo 'active'; } ?>">
                    <input type="radio" name="autodetect" <?php if (!cmsCore::getArrVal($cfg, 'autodetect', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_CLASS']; ?>:</label>
            <input type="text" id="maxitems" class="form-control" name="class" size="20" value="<?php echo $cfg['class']; ?>" />
            <div class="help-block"><?php echo $_LANG['AD_CLASS_HINT']; ?></div>
        </div>
    </div>
    
    <div>
        <input type="hidden" name="opt" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&amp;do=config&amp;link=geo';"/>
    </div>
</form>