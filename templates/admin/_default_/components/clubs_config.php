<form id="addform" name="addform" class="form-horizontal" role="form" action="index.php?view=components&do=config&link=clubs" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:650px;" class="uitabs">
        <ul>
            <li><a href="#basic"><span><?php echo $_LANG['AD_OVERALL']; ?></span></a></li>
            <li><a href="#limits"><span><?php echo $_LANG['AD_LISTS_LIMIT']; ?></span></a></li>
            <li><a href="#photos"><span><?php echo $_LANG['AD_FOTO']; ?></span></a></li>
            <li><a href="#restrict"><span><?php echo $_LANG['LIMITS']; ?></span></a></li>
            <li><a href="#tab_seo"><span><?php echo $_LANG['AD_SEO']; ?></span></a></li>
        </ul>

        <div id="basic">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['CLUB_BLOG'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'enabled_blogs', false)) { echo 'active'; } ?>">
                        <input type="radio" name="enabled_blogs" <?php if(cmsCore::getArrVal($cfg, 'enabled_blogs', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'enabled_blogs', false)) { echo 'active'; } ?>">
                        <input type="radio" name="enabled_blogs" <?php if (!cmsCore::getArrVal($cfg, 'enabled_blogs', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_LOGO_SMALL_WIDTH'];?> (<?php echo $_LANG['AD_PX']; ?>)</label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="thumb1" value="<?php echo $cfg['thumb1']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_LOGO_MEDIUM_WIDTH'];?> (<?php echo $_LANG['AD_PX']; ?>)</label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="thumb2" value="<?php echo $cfg['thumb2']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SQUARE_LOGO'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'thumbsqr', false)) { echo 'active'; } ?>">
                        <input type="radio" name="thumbsqr" <?php if(cmsCore::getArrVal($cfg, 'thumbsqr', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'thumbsqr', false)) { echo 'active'; } ?>">
                        <input type="radio" name="thumbsqr" <?php if (!cmsCore::getArrVal($cfg, 'thumbsqr', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_NOTIFICATION_IN'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'notify_in', false)) { echo 'active'; } ?>">
                        <input type="radio" name="notify_in" <?php if(cmsCore::getArrVal($cfg, 'notify_in', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'notify_in', false)) { echo 'active'; } ?>">
                        <input type="radio" name="notify_in" <?php if (!cmsCore::getArrVal($cfg, 'notify_in', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                    <div style="clear:both;"></div>
                    <div class="help-block"><?php echo $_LANG['AD_NOTIFICATION_IN_HINT']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_NOTIFICATION_OUT'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'notify_out', false)) { echo 'active'; } ?>">
                        <input type="radio" name="notify_out" <?php if(cmsCore::getArrVal($cfg, 'notify_out', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'notify_out', false)) { echo 'active'; } ?>">
                        <input type="radio" name="notify_out" <?php if (!cmsCore::getArrVal($cfg, 'notify_out', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                    <div style="clear:both;"></div>
                    <div class="help-block"><?php echo $_LANG['AD_NOTIFICATION_OUT_HINT']; ?></div>
                </div>
            </div>
        </div>
        
        <div id="limits">
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_CLUB_COUNT'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="perpage" value="<?php echo $cfg['perpage']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_MEMBER_COUNT_CLUB_PAGE'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="club_perpage" value="<?php echo $cfg['club_perpage']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_MEMBER_COUNT_PAGE'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="member_perpage" value="<?php echo $cfg['member_perpage']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_RECORDS_COUNT'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="wall_perpage" value="<?php echo $cfg['wall_perpage']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_POST_COUNT_CLUB_PAGE'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="club_posts_perpage" value="<?php echo $cfg['club_posts_perpage']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_POST_COUNT_PAGE'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="posts_perpage" value="<?php echo $cfg['posts_perpage']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_ALBUM_COUNT_CLUB_PAGE'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="club_album_perpage" value="<?php echo $cfg['club_album_perpage']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_ALBUM_COUNT_PAGE'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="photo_perpage" value="<?php echo $cfg['photo_perpage']; ?>" />
                </div>
            </div>
        </div>
        
        <div id="photos">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['CLUB_PHOTOALBUMS'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'enabled_photos', false)) { echo 'active'; } ?>">
                        <input type="radio" name="enabled_photos" <?php if(cmsCore::getArrVal($cfg, 'enabled_photos', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'enabled_photos', false)) { echo 'active'; } ?>">
                        <input type="radio" name="enabled_photos" <?php if (!cmsCore::getArrVal($cfg, 'enabled_photos', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_ENABLE_WATERMARK'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'photo_watermark', false)) { echo 'active'; } ?>">
                        <input type="radio" name="photo_watermark" <?php if(cmsCore::getArrVal($cfg, 'photo_watermark', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'photo_watermark', false)) { echo 'active'; } ?>">
                        <input type="radio" name="photo_watermark" <?php if (!cmsCore::getArrVal($cfg, 'photo_watermark', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                    <div style="clear:both;"></div>
                    <div class="help-block"><?php echo $_LANG['AD_ENABLE_WATERMARK_HINT']; ?> "<a href="/images/watermark.png" target="_blank">/images/watermark.png</a>"</div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_RETAIN_BOOT'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'is_saveorig', false)) { echo 'active'; } ?>">
                        <input type="radio" name="is_saveorig" <?php if(cmsCore::getArrVal($cfg, 'is_saveorig', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'is_saveorig', false)) { echo 'active'; } ?>">
                        <input type="radio" name="is_saveorig" <?php if (!cmsCore::getArrVal($cfg, 'is_saveorig', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_PHOTO_SMALL_WIDTH'];?> (<?php echo $_LANG['AD_PX']; ?>)</label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="photo_thumb_small" min="0" value="<?php echo $cfg['photo_thumb_small']; ?>">

                    <label><?php echo $_LANG['AD_SQUARE_PHOTO']; ?></label>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'photo_thumbsqr', false)) { echo 'active'; } ?>">
                            <input type="radio" name="photo_thumbsqr" <?php if(cmsCore::getArrVal($cfg, 'photo_thumbsqr', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'photo_thumbsqr', false)) { echo 'active'; } ?>">
                            <input type="radio" name="photo_thumbsqr" <?php if (!cmsCore::getArrVal($cfg, 'photo_thumbsqr', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_PHOTO_MEDIUM_WIDTH'];?> (<?php echo $_LANG['AD_PX']; ?>)</label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="photo_thumb_medium" min="0" value="<?php echo $cfg['photo_thumb_medium']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_PHOTO_MAXCOLS'];?></label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="photo_maxcols" min="0" value="<?php echo $cfg['photo_maxcols']; ?>" />
                </div>
            </div>
        </div>
        
        <div id="restrict">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CANCREATE'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'cancreate', false)) { echo 'active'; } ?>">
                        <input type="radio" name="cancreate" <?php if(cmsCore::getArrVal($cfg, 'cancreate', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'cancreate', false)) { echo 'active'; } ?>">
                        <input type="radio" name="cancreate" <?php if (!cmsCore::getArrVal($cfg, 'cancreate', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                    <div style="clear:both;"></div>
                    <div class="help-block"><?php echo $_LANG['AD_CANCREATE_HINT']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_EVERY_KARMA'];?></label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="every_karma" min="0" value="<?php echo $cfg['every_karma']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_EVERY_KARMA_HINT']; ?></div>
                </div>
            </div>
        
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CREATE_MIN_KARMA'];?></label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="create_min_karma" min="0" value="<?php echo $cfg['create_min_karma']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_CREATE_MIN_KARMA_HINT']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CREATE_MIN_RATING'];?></label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="create_min_rating" min="0" value="<?php echo $cfg['create_min_rating']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_CREATE_MIN_RATING_HINT']; ?></div>
                </div>
            </div>
        </div>
        
        <div id="tab_seo">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_ROOT_METAKEYS'];?></label>
                <div class="col-sm-7">
                    <textarea class="form-control" name="meta_keys" rows="2"><?php echo $cfg['meta_keys'] ?></textarea>
                    <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_ROOT_METADESC'];?></label>
                <div class="col-sm-7">
                    <textarea class="form-control" name="meta_desc" rows="2"><?php echo $cfg['meta_desc'] ?></textarea>
                    <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_USER_SEO_ACCESS'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'active'; } ?>">
                        <input type="radio" name="seo_user_access" <?php if(cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'active'; } ?>">
                        <input type="radio" name="seo_user_access" <?php if (!cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div>
        <input type="hidden" name="opt" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>'"/>
    </div>
</form>