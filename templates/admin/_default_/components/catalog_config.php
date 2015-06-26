<form action="index.php?view=components&do=config&link=catalog" method="post" name="addform" target="_self" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_SELLER_EMAIL']; ?></label>
            <input type="text" class="form-control" name="email" value="<?php echo cmsCore::getArrVal($cfg, 'email', ''); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_USER_NOTICE']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'notice', false)) { echo 'active'; } ?>">
                    <input type="radio" name="notice" <?php if (cmsCore::getArrVal($cfg, 'notice', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'notice', false)) { echo 'active'; } ?>">
                    <input type="radio" name="notice" <?php if (!cmsCore::getArrVal($cfg, 'notice', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_USERS_MODERATION']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'premod', false)) { echo 'active'; } ?>">
                    <input type="radio" name="premod" <?php if (cmsCore::getArrVal($cfg, 'premod', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'premod', false)) { echo 'active'; } ?>">
                    <input type="radio" name="premod" <?php if (!cmsCore::getArrVal($cfg, 'premod', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ABOUT_NEW_ITEM']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'premod_msg', false)) { echo 'active'; } ?>">
                    <input type="radio" name="premod_msg" <?php if(cmsCore::getArrVal($cfg, 'premod_msg', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'premod_msg', false)) { echo 'active'; } ?>">
                    <input type="radio" name="premod_msg" <?php if (!cmsCore::getArrVal($cfg, 'premod_msg', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label style="max-width:450px;"><?php echo $_LANG['AD_AUTOCOMENT']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'is_comments', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_comments" <?php if (cmsCore::getArrVal($cfg, 'is_comments', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'is_comments', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_comments" <?php if (!cmsCore::getArrVal($cfg, 'is_comments', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ENABLE_WATERMARK']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                    <input type="radio" name="watermark" <?php if (cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                    <input type="radio" name="watermark" <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
            <div class="help-block"><?php echo $_LANG['AD_IF_PUT_IMAGE']; ?> "<a href="/images/watermark.png" target="_blank">/images/watermark.png</a>"</div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_MEDIUM_SIZE']; ?></label>
            <input type="number" class="form-control" name="medium_size" value="<?php echo cmsCore::getArrVal($cfg, 'medium_size', ''); ?>"/>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SMALL_SIZE']; ?></label>
            <input type="number" class="form-control" name="small_size" value="<?php echo cmsCore::getArrVal($cfg, 'small_size', ''); ?>"/>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ROOT_METAKEYS']; ?></label>
            <textarea class="form-control" name="meta_keys" rows="2"><?php echo cmsCore::getArrVal($cfg, 'meta_keys', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ROOT_METADESC']; ?></label>
            <textarea class="form-control" name="meta_desc" rows="4"><?php echo cmsCore::getArrVal($cfg, 'meta_desc', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_VIEW_RSS_ICON']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_rss" <?php if (cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_rss" <?php if (!cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ABOUT_DELIVERY']; ?></label>
            <textarea class="form-control" style="height:150px;" name="delivery"><?php echo cmsCore::getArrVal($cfg, 'delivery', ''); ?></textarea>
        </div>
    </div>
    <div>
        <input name="opt" type="hidden" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';"/>
    </div>
</form>