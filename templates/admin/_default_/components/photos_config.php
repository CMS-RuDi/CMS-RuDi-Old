<form action="index.php?view=components&amp;do=config&amp;link=photos" method="post" enctype="multipart/form-data" name="addform">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:500px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_LINKS_ORIGINAL']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'link', false)) { echo 'active'; } ?>">
                    <input type="radio" name="show_link" <?php if(cmsCore::getArrVal($cfg, 'link', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'link', false)) { echo 'active'; } ?>">
                    <input type="radio" name="show_link" <?php if (!cmsCore::getArrVal($cfg, 'link', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_RETAIN_BOOT']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'saveorig', false)) { echo 'active'; } ?>">
                    <input type="radio" name="saveorig" <?php if(cmsCore::getArrVal($cfg, 'saveorig', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'saveorig', false)) { echo 'active'; } ?>">
                    <input type="radio" name="saveorig" <?php if (!cmsCore::getArrVal($cfg, 'saveorig', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_NUMBER_COLUMS']; ?> (<?php echo $_LANG['AD_PIECES']; ?>):</label>
            <input type="number" id="maxcols" class="form-control" name="maxcols" min="0" value="<?php echo $cfg['maxcols']; ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALBUM_SORT']; ?>:</label>
            <select class="form-control" name="orderby">
                <option value="title" <?php if ($cfg['orderby'] == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ALPHABET']; ?></option>
                <option value="pubdate" <?php if ($cfg['orderby'] == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR']; ?></option>
            </select>
            <select class="form-control" name="orderto">
                <option value="desc" <?php if ($cfg['orderto'] == 'desc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DECREMENT']; ?></option>
                <option value="asc" <?php if ($cfg['orderto'] == 'asc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_INCREMENT']; ?></option>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_LINKS_LATEST']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'showlat', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showlat" <?php if(cmsCore::getArrVal($cfg, 'showlat', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'showlat', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showlat" <?php if (!cmsCore::getArrVal($cfg, 'showlat', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_NUMBER']; ?> (<?php echo $_LANG['AD_PIECES']; ?>):</label>
            <input type="number" class="form-control" name="best_latest_perpage" min="0" value="<?php echo $cfg['best_latest_perpage']; ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_NUMBER_COLUMN']; ?> (<?php echo $_LANG['AD_PIECES']; ?>):</label>
            <input type="number" class="form-control" name="best_latest_maxcols" min="0" value="<?php echo $cfg['best_latest_maxcols']; ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ENABLE_WATERMARK']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                    <input type="radio" name="watermark" <?php if(cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                    <input type="radio" name="watermark" <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
            <div class="help-block"><?php echo $_LANG['AD_WATERMARK_PHOTOS_HINT']; ?> "<a href="/images/watermark.png" target="_blank">/images/watermark.png</a>"</div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ROOT_METAKEYS']; ?></label>
            <textarea class="form-control" name="meta_keys" rows="2"><?php echo $cfg['meta_keys'] ?></textarea>
            <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ROOT_METADESC']; ?></label>
            <textarea class="form-control" name="meta_desc" rows="4"><?php echo $cfg['meta_desc'] ?></textarea>
            <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_USER_SEO_ACCESS']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'active'; } ?>">
                    <input type="radio" name="seo_user_access" <?php if(cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'active'; } ?>">
                    <input type="radio" name="seo_user_access" <?php if (!cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
    </div>
    
    <div>
        <input type="hidden" name="opt" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back3" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&link=photos';"/>
    </div>
</form>