<form id="addform" class="form-horizontal" role="form" name="addform" enctype="multipart/form-data" method="post" action="index.php?view=components&amp;do=config&amp;link=board">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CAT_NAME'];?></label>
            <div class="col-sm-7">
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'title', '')); ?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CAT_PARENT'];?></label>
            <div class="col-sm-7">
                <select id="parent_id" class="form-control" name="parent_id">
                    <option value="<?php echo $rootid; ?>" <?php if (cmsCore::getArrVal($mod, 'parent_id', $rootid) == $rootid) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_CAT_ROOT'];?></option>
                    <?php echo $board_cats_opt; ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CAT_ICON'];?></label>
            <div class="col-sm-7">
                <?php if (cmsCore::getArrVal($mod, 'icon', false)) { ?>
                    <div style="text-align:center;"><img src="/upload/board/cat_icons/<?php echo $mod['icon']; ?>" border="0" /></div>
                <?php } ?>
                <input type="file" class="form-control" name="Filedata" />
                <div class="help-block"><?php echo $_LANG['AD_INFO_3'];?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_ATTACH_FORM'];?></label>
            <div class="col-sm-7">
                <select class="form-control" name="form_id">
                    <option value="" <?php if (!cmsCore::getArrVal($mod, 'form_id', false)) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_DONT_ATTACH']; ?></option>
                    <?php
                        foreach ($forms as $f) {
                            if ($f['id'] == cmsCore::getArrVal($mod, 'form_id', false)) { $selected='selected="selected"'; } else { $selected = ''; }
                            echo '<option value="'. $f['id'] .'" '. $selected .'>'. $f['title'] .'</option>';
                        }
                    ?>
                </select>
                <div class="help-block"><?php echo $_LANG['AD_FORM_FIELDS_EXIST'];?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_IF_PUBLIC_CAT'];?></label>
            <div class="col-sm-7 btn-group" data-toggle="buttons">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_IF_DATA_VIEW'];?></label>
            <div class="col-sm-7 btn-group" data-toggle="buttons">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'showdate', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showdate" <?php if(cmsCore::getArrVal($mod, 'showdate', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'showdate', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showdate" <?php if (!cmsCore::getArrVal($mod, 'showdate', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SORT_AD'];?></label>
            <div class="col-sm-7">
                <select class="form-control" name="orderby">
                    <option value="title" <?php if (cmsCore::getArrVal($mod, 'orderby', false) == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ALPHABET']; ?></option>
                    <option value="pubdate" <?php if (cmsCore::getArrVal($mod, 'orderby', false) == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR']; ?></option>
                    <option value="hits" <?php if (cmsCore::getArrVal($mod, 'orderby', false) == 'hits') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_VIEWS']; ?></option>
                    <option value="obtype" <?php if (cmsCore::getArrVal($mod, 'orderby', false) == 'obtype') { echo 'selected="selected"'; } ?>><?php echo $_LANG['ORDERBY_TYPE']; ?></option>
                    <option value="user_id" <?php if (cmsCore::getArrVal($mod, 'orderby', false) == 'user_id') { echo 'selected="selected"'; } ?>><?php echo $_LANG['ORDERBY_AVTOR']; ?></option>
                </select>
                <select class="form-control" name="orderto">
                    <option value="desc" <?php if (cmsCore::getArrVal($mod, 'orderto', false) == 'desc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DECREMENT']; ?></option>
                    <option value="asc" <?php if (cmsCore::getArrVal($mod, 'orderto', false) == 'asc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_INCREMENT']; ?></option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SORT_FORM'];?></label>
            <div class="col-sm-7">
                <div class="checkbox">
                    <label><input type="radio" name="orderform" value="1" <?php if (cmsCore::getArrVal($mod, 'orderform', false)) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['SHOW']; ?></label>
                    <label><input type="radio" name="orderform" value="0"  <?php if (!cmsCore::getArrVal($mod, 'orderform', false)) { echo 'checked="checked"'; } ?> /><?php echo $_LANG['HIDE']; ?></label>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_HOW_MANY_COLUMNS_VIEW'];?> (<?php echo $_LANG['AD_PIECES']; ?>)</label>
            <div class="col-sm-7">
                <input type="number" class="form-control" name="maxcols" size="5" min="0" value="<?php echo cmsCore::getArrVal($mod, 'maxcols', ''); ?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_USERS_AD_ADD'];?></label>
            <div class="col-sm-7">
                <select class="form-control" name="public">
                    <option value="0" <?php if (cmsCore::getArrVal($mod, 'public', false) == '0') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TABOO']; ?></option>
                    <option value="1" <?php if (cmsCore::getArrVal($mod, 'public', false) == '1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PREMODERATION']; ?></option>
                    <option value="2" <?php if (cmsCore::getArrVal($mod, 'public', false) == '2') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_WITHOUT_MODERATION']; ?></option>
                    <option value="-1" <?php if (cmsCore::getArrVal($mod, 'public', false) == '-1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_DEFAULT']; ?></option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_MAX_AD'];?> (<?php echo $_LANG['AD_PIECES']; ?>)</label>
            <div class="col-sm-7">
                <input type="number" class="form-control" name="uplimit" size="5" min="0" value="<?php echo cmsCore::getArrVal($mod, 'uplimit', ''); ?>"/>
                <div class="help-block"><?php echo $_LANG['AD_ONE_USER_ONE_DAY']; ?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_HOW_MANY_AD_TO_PAGE'];?> (<?php echo $_LANG['AD_PIECES']; ?>)</label>
            <div class="col-sm-7">
                <input type="number" class="form-control" name="perpage" size="5" min="0" value="<?php echo cmsCore::getArrVal($mod, 'perpage', ''); ?>"/>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_PHOTO_TO_AD'];?></label>
            <div class="col-sm-7 btn-group" data-toggle="buttons">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'is_photos', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_photos" <?php if(cmsCore::getArrVal($mod, 'is_photos', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'is_photos', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_photos" <?php if (!cmsCore::getArrVal($mod, 'is_photos', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_MINI_PHOTO_WIDTH'];?></label>
            <div class="col-sm-7">
                <input type="number" class="form-control" name="thumb1" size="5" min="0" value="<?php echo cmsCore::getArrVal($mod, 'thumb1', ''); ?>"/>
                <div class="help-block"><?php echo $_LANG['AD_IN_PIXELS']; ?></div>
                <div>
                    <label><?php echo $_LANG['AD_SQUARE']; ?></label>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'active'; } ?>">
                            <input type="radio" name="thumbsqr" <?php if(cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'active'; } ?>">
                            <input type="radio" name="thumbsqr" <?php if (!cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_MIDI_PHOTO_WIDTH'];?></label>
            <div class="col-sm-7">
                <input type="number" class="form-control" name="thumb2" size="5" min="0" value="<?php echo cmsCore::getArrVal($mod, 'thumb2', ''); ?>"/>
                <div class="help-block"><?php echo $_LANG['AD_IN_PIXELS']; ?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_TYPES_AD'];?></label>
            <div class="col-sm-7">
                <textarea class="form-control" name="obtypes" rows="6"><?php echo cmsCore::getArrVal($mod, 'obtypes', ''); ?></textarea>
                <div class="help-block"><?php echo $_LANG['AD_NEW_LINE_TYPES']; ?></div>
                <div class="help-block"><?php echo $_LANG['AD_PARENT_CAT_DEFAULT']; ?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['SEO_PAGETITLE'];?></label>
            <div class="col-sm-7">
                <textarea class="form-control" name="pagetitle" rows="2"><?php echo cmsCore::getArrVal($mod, 'pagetitle', ''); ?></textarea>
                <div class="help-block"><?php echo $_LANG['SEO_PAGETITLE_HINT']; ?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['SEO_METAKEYS'];?></label>
            <div class="col-sm-7">
                <textarea class="form-control" name="meta_keys" rows="2"><?php echo cmsCore::getArrVal($mod, 'meta_keys', ''); ?></textarea>
                <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['SEO_METADESCR'];?></label>
            <div class="col-sm-7">
                <textarea class="form-control" name="meta_desc" rows="4"><?php echo cmsCore::getArrVal($mod, 'meta_desc', ''); ?></textarea>
                <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CAT_DESCRIPTION'];?></label>
            <div class="col-sm-7">
                <textarea class="form-control" name="description" rows="6"><?php echo cmsCore::getArrVal($mod, 'description', ''); ?></textarea>
            </div>
        </div>
    </div>
    <div>
        <input name="opt" type="hidden" value="<?php if ($opt == 'add_cat') { echo 'submit_cat'; } else { echo 'update_cat'; } ?>" />
        
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back2" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';"/>
        <?php
            if ($opt == 'edit_cat') {
                echo '<input name="item_id" type="hidden" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>