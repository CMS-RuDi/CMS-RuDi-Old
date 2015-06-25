<?php if ($opt == 'add_album') { ?>
    <h3><?php echo $_LANG['AD_ALBUM_ADD']; ?></h3>
<?php } else { ?>
    <h3><?php echo $_LANG['AD_ALBUM_EDIT'] .' "'. $mod['title'] .'"'; ?></h3>
<?php } ?>

<form id="addform" name="addform" method="post" action="index.php?view=components&do=config&link=photos">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALBUM_TITLE']; ?>:</label>
            <input type="text" class="form-control" name="title" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'title', '')); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALBUM_PARENT']; ?>:</label>
            <select id="parent_id" class="form-control" name="parent_id" size="8">
                <option value="<?php echo $rootid; ?>" <?php if (cmsCore::getArrVal($mod, 'parent_id', $rootid) == $rootid) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_ALBUM_ROOT']; ?></option>
                <?php echo $photo_albums_opt; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALBUM_POST']; ?></label>
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
            <label><?php echo $_LANG['AD_SHOW_DATES_COMMENTS']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'showdate', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showdate" <?php if(cmsCore::getArrVal($mod, 'showdate', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'showdate', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showdate" <?php if (!cmsCore::getArrVal($mod, 'showdate', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_TAGS']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'showtags', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showtags" <?php if(cmsCore::getArrVal($mod, 'showtags', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'showtags', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showtags" <?php if (!cmsCore::getArrVal($mod, 'showtags', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_CODE_FORUM']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'bbcode', false)) { echo 'active'; } ?>">
                    <input type="radio" name="bbcode" <?php if(cmsCore::getArrVal($mod, 'bbcode', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'bbcode', false)) { echo 'active'; } ?>">
                    <input type="radio" name="bbcode" <?php if (!cmsCore::getArrVal($mod, 'bbcode', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_COMMENTS_ALBUM']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'is_comments', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_comments" <?php if(cmsCore::getArrVal($mod, 'is_comments', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'is_comments', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_comments" <?php if (!cmsCore::getArrVal($mod, 'is_comments', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SORT_PHOTOS']; ?>:</label>
            <select id="orderby" class="form-control" name="orderby">
                <option value="title" <?php if (cmsCore::getArrVal($mod, 'orderby') == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ALPHABET']; ?></option>
                <option value="pubdate" <?php if (cmsCore::getArrVal($mod, 'orderby') == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR']; ?></option>
                <option value="rating" <?php if (cmsCore::getArrVal($mod, 'orderby') == 'rating') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_RATING']; ?></option>
                <option value="hits" <?php if (cmsCore::getArrVal($mod, 'orderby') == 'hits') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_VIEWS']; ?></option>
            </select>
            <select id="orderto" class="form-control" name="orderto">
                <option value="desc" <?php if (cmsCore::getArrVal($mod, 'orderto') == 'desc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DECREMENT']; ?></option>
                <option value="asc" <?php if (cmsCore::getArrVal($mod, 'orderto') == 'asc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_INCREMENT']; ?></option>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_OUTPUT_PHOTOS']; ?>:</label>
            <select id="showtype" class="form-control" name="showtype">
                <option value="thumb" <?php if (cmsCore::getArrVal($mod, 'showtype') == 'thumb') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_GALLERY']; ?></option>
                <option value="lightbox" <?php if (cmsCore::getArrVal($mod, 'showtype') == 'lightbox') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_GALLERY_LIGHTBOX']; ?></option>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_NUMBER_COLUMS_PHOTOS']; ?> (<?php echo $_LANG['AD_PIECES']; ?>):</label>
            <input type="number" class="form-control" name="maxcols" min="0" value="<?php echo cmsCore::getArrVal($mod, 'maxcols', ''); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ADD_PHOTOS_USERS']; ?>:</label>
            <select class="form-control" name="public">
                <option value="0" <?php if (cmsCore::getArrVal($mod, 'public') == '0') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PROCHBITED']; ?></option>
                <option value="1" <?php if (cmsCore::getArrVal($mod, 'public') == '1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_FROM_PREMODERATION']; ?></option>
                <option value="2" <?php if (cmsCore::getArrVal($mod, 'public') == '2') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_WITHOUT_PREMODERATION']; ?></option>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_UPLOAD_MAX']; ?> (<?php echo $_LANG['AD_PIECES']; ?>):</label>
            <input type="number" class="form-control" name="uplimit" min="0" value="<?php echo cmsCore::getArrVal($mod, 'uplimit', ''); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_FORM_SORTING']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'orderform', false)) { echo 'active'; } ?>">
                    <input type="radio" name="orderform" <?php if(cmsCore::getArrVal($mod, 'orderform', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['SHOW']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'orderform', false)) { echo 'active'; } ?>">
                    <input type="radio" name="orderform" <?php if (!cmsCore::getArrVal($mod, 'orderform', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['HIDE']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALBUM_NAVIGATTING']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'nav', false)) { echo 'active'; } ?>">
                    <input type="radio" name="nav" <?php if(cmsCore::getArrVal($mod, 'nav', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'nav', false)) { echo 'active'; } ?>">
                    <input type="radio" name="nav" <?php if (!cmsCore::getArrVal($mod, 'nav', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_CSS_PREFIX']; ?>:</label>
            <input type="text" class="form-control" name="cssprefix" size="10" value="<?php echo cmsCore::getArrVal($mod, 'cssprefix', ''); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_PHOTOS_ON_PAGE']; ?> (<?php echo $_LANG['AD_PIECES']; ?>):</label>
            <input type="number" class="form-control" name="perpage" min="0" value="<?php echo cmsCore::getArrVal($mod, 'perpage', ''); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_WIDTH_SMALL_COPY']; ?> (<?php echo $_LANG['AD_PX']; ?>):</label>
            <input type="number" class="form-control" name="thumb1" min="0" value="<?php echo cmsCore::getArrVal($mod, 'thumb1', ''); ?>" />
            <label><?php echo $_LANG['AD_PHOTOS_SQUARE']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'active'; } ?>">
                    <input type="radio" name="thumbsqr" <?php if(cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'active'; } ?>">
                    <input type="radio" name="thumbsqr" <?php if (!cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_WIDTH_MIDDLE_COPY']; ?> (<?php echo $_LANG['AD_PX']; ?>):</label>
            <input type="number" class="form-control" name="thumb2" min="0" value="<?php echo cmsCore::getArrVal($mod, 'thumb2', ''); ?>" />
        </div>
        
        <?php if ($opt == 'edit_album') { ?>
            <div class="form-group">
                <label><?php echo $_LANG['AD_MINI_SKETCH']; ?>:</label>
            
                <div style="text-align:center;">
                <?php if ($icon_exist) { ?>
                    <img id="marker_demo" src="/images/photos/small/<?php echo $mod['iconurl']; ?>">
                <?php  } else { ?>
                    <img id="marker_demo" src="/images/photos/no_image.png" style="display: none;">
                <?php  } ?>
                </div>
                
                <?php if ($photo_files_opt) { ?>
                    <select name="iconurl" id="iconurl" style="width:285px" onchange="showMapMarker()">
                        <?php if (!$icon_exist) { ?>
                            <option value="" selected="selected"><?php echo $_LANG['AD_MINI_SKETCH_CHOOSE']; ?></option>
                        <?php } ?>
                        <?php echo $photo_files_opt; ?>
                    </select>
                <?php  } else { ?>
                    <div><?php echo $_LANG['AD_ALBUM_NO_PHOTOS']; ?>.</div>
                <?php  } ?>
            </div>
        <?php } ?>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALBUM_DESCR']; ?>:</label>
            <textarea class="form-control" name="description" rows="4"><?php echo cmsCore::getArrVal($mod, 'description', ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_PAGETITLE']; ?></label>
            <textarea class="form-control" name="pagetitle" rows="2"><?php echo cmsCore::getArrVal($mod, 'pagetitle', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['SEO_PAGETITLE_HINT']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_METAKEYS']; ?></label>
            <textarea class="form-control" name="meta_keys" rows="2"><?php echo cmsCore::getArrVal($mod, 'meta_keys', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_METADESCR']; ?></label>
            <textarea class="form-control" name="meta_desc" rows="2"><?php echo cmsCore::getArrVal($mod, 'meta_desc', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
        </div>
    </div>
    
    <div>
        <input type="hidden" name="opt" value="<?php if ($opt == 'add_album') { echo 'submit_album'; } else { echo 'update_album'; } ?>" />
        
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back2" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&link=photos';"/>
        <?php
            if ($opt == 'edit_album') {
                echo '<input type="hidden" name="item_id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>

<script type="text/javascript">
    function showMapMarker() {
        var file = $('select[name=iconurl]').val();
        if (file) {
            $('#marker_demo').attr('src', '/images/photos/small/'+file).fadeIn();
        } else {
            $('#marker_demo').hide();
        }
    }
</script>