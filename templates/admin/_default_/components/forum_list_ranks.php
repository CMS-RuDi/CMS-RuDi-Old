<form action="index.php?view=components&amp;do=config&amp;link=forum" method="post" name="addform" target="_self" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:500px;">
        <div class="form-group">
            <div class="col-sm-8">
                <label><?php echo $_LANG['AD_RANKS_FORUM_MODER']; ?></label>
            </div>
            <div class="col-sm-4 btn-group" data-toggle="buttons">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'modrank', false)) { echo 'active'; } ?>">
                    <input type="radio" name="modrank" <?php if(cmsCore::getArrVal($cfg, 'modrank', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'modrank', false)) { echo 'active'; } ?>">
                    <input type="radio" name="modrank" <?php if (!cmsCore::getArrVal($cfg, 'modrank', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div style="clear:both;"></div>
        
        <div class="form-group" style="margin-top:20px;">
            <div class="col-sm-8">
                <label><?php echo $_LANG['AD_RANKS']; ?></label>
            </div>
            <div class="col-sm-4">
                <label><?php echo $_LANG['AD_NUMBER_POSTS']; ?></label>
            </div>
        </div>
        
        <?php for ($r = 1; $r <= 10; $r++) { ?>
        <div class="form-group">
            <div class="col-sm-8">
                <input type="text" class="form-control" name="rank[<?php echo $r?>][title]" style="width:250px;" value="<?php echo $this->escape($cfg['ranks'][$r]['title']) ?>" />
            </div>
            <div class="col-sm-4">
                <input type="number" class="form-control" name="rank[<?php echo $r?>][msg]" min="0" value="<?php echo $this->escape($cfg['ranks'][$r]['msg']) ?>" />
            </div>
        </div>
        <?php } ?>
    </div>
    
    <div style="clear:both;"></div>
    
    <div style="margin-top:20px;">
        <input type="hidden" name="opt" value="saveranks" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&amp;do=config&amp;link=forum';"/>
    </div>
</form>