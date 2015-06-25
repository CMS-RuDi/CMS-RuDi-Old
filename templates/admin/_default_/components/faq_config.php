<form action="index.php?view=components&do=config&link=faq&opt=config" method="post" name="optform" target="_self" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:650px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_QUEST_FROM_UNREG']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'guest_enabled', false)) { echo 'active'; } ?>">
                    <input type="radio" name="guest_enabled" <?php if (cmsCore::getArrVal($cfg, 'guest_enabled', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'guest_enabled', false)) { echo 'active'; } ?>">
                    <input type="radio" name="guest_enabled" <?php if (!cmsCore::getArrVal($cfg, 'guest_enabled', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_LINK_QUEST_MEM']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'user_link', false)) { echo 'active'; } ?>">
                    <input type="radio" name="user_link" <?php if(cmsCore::getArrVal($cfg, 'user_link', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'user_link', false)) { echo 'active'; } ?>">
                    <input type="radio" name="user_link" <?php if (!cmsCore::getArrVal($cfg, 'user_link', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_POST_QUEST_NO_MODERAT']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'publish', false)) { echo 'active'; } ?>">
                    <input type="radio" name="publish" <?php if(cmsCore::getArrVal($cfg, 'publish', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'publish', false)) { echo 'active'; } ?>">
                    <input type="radio" name="publish" <?php if (!cmsCore::getArrVal($cfg, 'publish', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
            <div class="help-block"><?php echo $_LANG['AD_POST_QUEST_NO_MODERAT_HINT']; ?>.</div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALLOW_COMMENTS']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'is_comment', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_comment" <?php if(cmsCore::getArrVal($cfg, 'is_comment', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'is_comment', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_comment" <?php if (!cmsCore::getArrVal($cfg, 'is_comment', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
    </div>

    <div>
        <input type="hidden" name="opt" value="saveconfig" />
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&link=faq';" />
    </div>
</form>