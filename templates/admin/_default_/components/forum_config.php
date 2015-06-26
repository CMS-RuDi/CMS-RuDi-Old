<form action="index.php?view=components&amp;do=config&amp;link=forum" method="post" name="addform" target="_self" id="form1" style="margin-top:10px">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div class="uitabs" style="width:600px;">
        <ul>
            <li><a href="#tab_reviev"><?php echo $_LANG['AD_REVIEV']; ?></a></li>
            <li><a href="#tab_pictures"><?php echo $_LANG['AD_PICTURES']; ?></a></li>
            <li><a href="#tab_inverstments"><?php echo $_LANG['AD_INVESTMENTS']; ?></a></li>
            <li><a href="#tab_limit"><?php echo $_LANG['AD_LIMIT']; ?></a></li>
            <li><a href="#tab_seo">SEO</a></li>
        </ul>
        
        <div id="tab_reviev">
            <fieldset>
                <legend><?php echo $_LANG['AD_FORUM_REVIEV']; ?></legend>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_TOPICS_PER_PAGE']; ?></label>
                    <input type="number" class="form-control" name="pp_forum" min="0" value="<?php echo $cfg['pp_forum']; ?>" />
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ICON_RSS']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'active'; } ?>">
                            <input type="radio" name="is_rss" <?php if (cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'active'; } ?>">
                            <input type="radio" name="is_rss" <?php if (!cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
            </fieldset>
            
            <fieldset>
                <legend><?php echo $_LANG['AD_TOPIC_REVIEV']; ?></legend>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_POSTS_PER_PAGE']; ?></label>
                    <input type="number" class="form-control" name="pp_thread" min="0" value="<?php echo $cfg['pp_thread']; ?>" />
                </div>
                
                <div class="form-group">
                    <label style="width:400px;"><?php echo $_LANG['AD_SHOW_PICCTURES']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'showimg', false)) { echo 'active'; } ?>">
                            <input type="radio" name="showimg" <?php if (cmsCore::getArrVal($cfg, 'showimg', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'showimg', false)) { echo 'active'; } ?>">
                            <input type="radio" name="showimg" <?php if (!cmsCore::getArrVal($cfg, 'showimg', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_FORM_QUICK_RESPONCE']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'fast_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="fast_on" <?php if (cmsCore::getArrVal($cfg, 'fast_on', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'fast_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="fast_on" <?php if (!cmsCore::getArrVal($cfg, 'fast_on', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_BBCODE_RENSPONCE']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'fast_bb', false)) { echo 'active'; } ?>">
                            <input type="radio" name="fast_bb" <?php if (cmsCore::getArrVal($cfg, 'fast_bb', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'fast_bb', false)) { echo 'active'; } ?>">
                            <input type="radio" name="fast_bb" <?php if (!cmsCore::getArrVal($cfg, 'fast_bb', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
            </fieldset>
        </div>
        
        <div id="tab_pictures">
            <fieldset>
                <legend><?php echo $_LANG['AD_PICTURES_MESS']; ?></legend>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_PICTURES_INSERT']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="img_on" <?php if (cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="img_on" <?php if (!cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_PICTURES_MAX']; ?></label>
                    <input type="number" class="form-control" name="img_max" min="0" value="<?php echo $cfg['img_max']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_PICTURES_NUMBER']; ?></div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_PICTURES_WATERMARK']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                            <input type="radio" name="watermark" <?php if (cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                            <input type="radio" name="watermark" <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
            </fieldset>
        </div>
        
        <div id="tab_inverstments">
            <fieldset>
                <legend><?php echo $_LANG['AD_FILES_ATTACHMENTS']; ?></legend>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_FILES_ATTACH']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'fa_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="fa_on" <?php if (cmsCore::getArrVal($cfg, 'fa_on', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'fa_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="fa_on" <?php if (!cmsCore::getArrVal($cfg, 'fa_on', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_AVAILABLES_FOR_GROUPS']; ?></label>
                    <label style="padding-left: 50px;">
                        <input type="checkbox" id="is_access" name="is_access" onclick="checkGroupList()" value="1" <?php if (!$cfg['group_access']) { ?>checked="checked"<?php } ?> /> <?php echo $_LANG['AD_ALL_GROUPS']; ?>
                    </label>
                    <div class="help-block"><?php echo $_LANG['AD_AVAILABLE_GROUPS']; ?></div>
                    <div class="help-block"><?php echo $_LANG['AD_ALL_GROUPS_HINT']; ?></div>
                    
                    <label><?php echo $_LANG['AD_ALL_GROUPS_ONLY']; ?></label>
                    <select id="showin" class="form-control" name="allow_group[]" size="6" multiple="multiple" <?php if (!$cfg['group_access']) { ?>disabled="disabled"<?php } ?>>
                        <?php
                        if ($groups) {
                            foreach($groups as $group) {
                                if ($group['alias'] != 'guest' && !$group['is_admin']) {
                                    echo '<option value="'. $group['id'] .'"';
                                    if ($cfg['group_access']) {
                                        if (in_array($group['id'], $cfg['group_access'])) {
                                            echo 'selected="selected"';
                                        }
                                    }

                                    echo '>';
                                    echo $group['title'] .'</option>';
                                }
                            }
                        } ?>
                    </select>
                    <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL']; ?></div>
                    <script type="text/javascript">
                        function checkGroupList(){
                            if ($('input#is_access').prop('checked')) {
                                $('select#showin').prop('disabled', true);
                            } else {
                                $('select#showin').prop('disabled', false);
                            }
                        }
                    </script>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_FILES_MAX']; ?></label>
                    <input type="number" class="form-control" name="fa_max" min="0" value="<?php echo $cfg['fa_max']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_FILES_MAX_HINT']; ?></div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ALLOWED_EXTENSIONS']; ?></label>
                    <textarea id="fa_ext" class="form-control" name="fa_ext" cols="35" rows="3"><?php echo $cfg['fa_ext']; ?></textarea>
                    <div class="help-block"><?php echo $_LANG['AD_ALLOWED_EXTENSIONS_HINT']; ?></div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_MAX_FILE_SIZE']; ?></label>
                    <input type="number" class="form-control" name="fa_size" min="0" value="<?php echo $cfg['fa_size']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_FILES_MAX_HINT']; ?></div>
                </div>
            </fieldset>
        </div>
        
        <div id="tab_limit">
            <div class="form-group">
                <label><?php echo $_LANG['AD_EDIT_DELIT']; ?></label>
                <select class="form-control" name="edit_minutes">
                    <option value="0" <?php if (!$cfg['edit_minutes']) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_NOT_PROHIBIT']; ?></option>
                    <option value="-1" <?php if ($cfg['edit_minutes'] == -1) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PROHIBIT']; ?></option>
                    <option value="1" <?php if ($cfg['edit_minutes'] == 1) { echo 'selected="selected"'; } ?>>1 <?php echo $_LANG['MINUTU1']; ?></option>
                    <option value="5" <?php if ($cfg['edit_minutes'] == 5) { echo 'selected="selected"'; } ?>>5 <?php echo $_LANG['MINUTE10']; ?></option>
                    <option value="10" <?php if ($cfg['edit_minutes'] == 10) { echo 'selected="selected"'; } ?>>10 <?php echo $_LANG['MINUTE10']; ?></option>
                    <option value="15" <?php if ($cfg['edit_minutes'] == 15) { echo 'selected="selected"'; } ?>>15 <?php echo $_LANG['MINUTE10']; ?></option>
                    <option value="30" <?php if ($cfg['edit_minutes'] == 30) { echo 'selected="selected"'; } ?>>30 <?php echo $_LANG['MINUTE10']; ?></option>
                    <option value="60" <?php if ($cfg['edit_minutes'] == 60) { echo 'selected="selected"'; } ?>>1 <?php echo $_LANG['HOUR1']; ?></option>
                </select>
                <div class="help-block"><?php echo $_LANG['AD_EDIT_DELIT_TIME']; ?></div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_FORUM_MIN_KARMA_ADD']; ?></label>
                <input type="number" class="form-control" name="min_karma_add" value="<?php echo $cfg['min_karma_add']; ?>" size="5" min="0" />
            </div>
        </div>
        
        <div id="tab_seo">
            <div class="form-group">
                <label><?php echo $_LANG['AD_ROOT_METAKEYS']; ?></label>
                <textarea class="form-control" name="meta_keys" rows="2"><?php echo $cfg['meta_keys']; ?></textarea>
                <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_ROOT_METADESC']; ?></label>
                <textarea class="form-control" name="meta_desc" rows="4"><?php echo $cfg['meta_desc'] ?></textarea>
                <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
            </div>
        </div>
    </div>

    <div>
        <input type="hidden" name="opt" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&link=forum';"/>
    </div>
</form>