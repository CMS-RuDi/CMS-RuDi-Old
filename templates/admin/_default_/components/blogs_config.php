<h3><?php echo $_LANG['AD_SETTINGS'] .': '. $com['title']; ?></h3>

<form action="index.php?view=components&do=config&id=<?php echo $id; ?>" method="post" name="optform" target="_self" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="uitabs">
            <ul id="tabs">
                <li><a href="#tab_blog_view"><span><?php echo $_LANG['AD_BLOG_VIEW']; ?></span></a></li>
                <li><a href="#tab_blog_photo_opt"><span><?php echo $_LANG['AD_PHOTO_OPTIONS']; ?></span></a></li>
                <li><a href="#tab_blog_edit_setup"><span><?php echo $_LANG['AD_EDIT_SETUP']; ?></span></a></li>
                <li><a href="#tab_blog_limit"><span><?php echo $_LANG['AD_LIMIT']; ?></span></a></li>
                <li><a href="#tab_blog_seo"><span>SEO</span></a></li>
            </ul>
            </ul>
            
            <div id="tab_blog_view">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_BLOG_POSTS_QUANTITY']; ?> (<?php echo $_LANG['AD_PIECES']; ?>)</label>
                    <input type="number" class="form-control" name="perpage" min="0" value="<?php echo cmsCore::getArrVal($cfg, 'perpage', 10); ?>" />
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_BLOGS_QUANTITY']; ?> (<?php echo $_LANG['AD_PIECES']; ?>)</label>
                    <input type="number" class="form-control" name="perpage_blog" min="0" value="<?php echo cmsCore::getArrVal($cfg, 'perpage_blog', 15); ?>" />
                </div>
            </div>
            
            <div id="tab_blog_photo_opt">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ENABLE_PHOTO_LOAD']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="img_on" <?php if (cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="img_on" <?php if (!cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_ENABLE_WATERMARK']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                            <input type="radio" name="watermark" <?php if (cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                            <input type="radio" name="watermark" <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                    <div class="help-block"><?php echo $_LANG['AD_IF_ENABLE_WATERMARK']; ?>"<a href="/images/watermark.png" target="_blank">/images/watermark.png</a>"</div>
                </div>
            </div>
            
            <div id="tab_blog_edit_setup">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_UPDATE_CALENDAR_DATA']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'update_date', false)) { echo 'active'; } ?>">
                            <input type="radio" name="update_date" <?php if (cmsCore::getArrVal($cfg, 'update_date', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'update_date', false)) { echo 'active'; } ?>">
                            <input type="radio" name="update_date" <?php if (!cmsCore::getArrVal($cfg, 'update_date', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                    <div class="help-block"><?php echo $_LANG['AD_IF_ENABLE_TODAY']; ?></div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_UPDATE_BLOG_LINK']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'update_seo_link_blog', false)) { echo 'active'; } ?>">
                            <input type="radio" name="update_seo_link_blog" <?php if (cmsCore::getArrVal($cfg, 'update_seo_link_blog', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'update_seo_link_blog', false)) { echo 'active'; } ?>">
                            <input type="radio" name="update_seo_link_blog" <?php if (!cmsCore::getArrVal($cfg, 'update_seo_link_blog', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                    <div class="help-block"><?php echo $_LANG['AD_IF_ENABLE_LINK']; ?></div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_UPDATE_TITLE_LINK']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'update_seo_link', false)) { echo 'active'; } ?>">
                            <input type="radio" name="update_seo_link" <?php if (cmsCore::getArrVal($cfg, 'update_seo_link', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'update_seo_link', false)) { echo 'active'; } ?>">
                            <input type="radio" name="update_seo_link" <?php if (!cmsCore::getArrVal($cfg, 'update_seo_link', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                    <div class="help-block"><?php echo $_LANG['AD_IF_ENABLE_TITLE']; ?></div>
                </div>
            </div>
            
            <div id="tab_blog_limit">
                <fieldset>
                    <legend><?php echo $_LANG['AD_KARMA_LIMIT']; ?></legend>

                    <div class="form-group">
                        <label><?php echo $_LANG['AD_USE_LIMIT']; ?></label>
                        <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                            <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'min_karma', false)) { echo 'active'; } ?>">
                                <input type="radio" name="min_karma" <?php if (cmsCore::getArrVal($cfg, 'min_karma', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                            </label>
                            <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'min_karma', false)) { echo 'active'; } ?>">
                                <input type="radio" name="min_karma" <?php if (!cmsCore::getArrVal($cfg, 'min_karma', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                            </label>
                        </div>
                        <div class="help-block"><?php echo $_LANG['AD_IF_DISABLE_KARMA_LIMIT']; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $_LANG['AD_CREATE_PERSONAL_BLOG']; ?></label>
                        <input type="number" class="form-control" name="min_karma_private" min="0" value="<?php echo cmsCore::getArrVal($cfg, 'min_karma_private', 0); ?>" size="5" />
                        <div class="help-block"><?php echo $_LANG['AD_HOW_MANY_KARMA_P']; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $_LANG['AD_CREATE_COLLECTIVE_BLOG']; ?></label>
                        <input type="number" class="form-control" name="min_karma_public" min="0" value="<?php echo cmsCore::getArrVal($cfg, 'min_karma_public', 0); ?>" size="5" />
                        <div class="help-block"><?php echo $_LANG['AD_HOW_MANY_KARMA_C']; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $_LANG['AD_RATING_MIN']; ?></label>
                        <input type="number" class="form-control" name="list_min_rating" min="0" value="<?php echo cmsCore::getArrVal($cfg, 'list_min_rating', 0); ?>" size="5" />
                        <div class="help-block"><?php echo $_LANG['AD_POST_LIST']; ?></div>
                    </div>
                </fieldset>
            </div>
            
            <div id="tab_blog_seo">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ROOT_METAKEYS']; ?></label>
                    <textarea class="form-control" name="meta_keys" rows="2"><?php echo cmsCore::getArrVal($cfg, 'meta_keys', ''); ?></textarea>
                    <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ROOT_METADESC']; ?></label>
                    <textarea class="form-control" name="meta_desc" rows="2"><?php echo cmsCore::getArrVal($cfg, 'meta_desc', ''); ?></textarea>
                    <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_USER_SEO_ACCESS']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'active'; } ?>">
                            <input type="radio" name="seo_user_access" <?php if (cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'active'; } ?>">
                            <input type="radio" name="seo_user_access" <?php if (!cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div style="margin-top: 10px;">
        <input name="opt" type="hidden" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';"/>
    </div>
</form>