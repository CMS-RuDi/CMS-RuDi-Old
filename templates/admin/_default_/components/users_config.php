<form action="index.php?view=components&amp;do=config&amp;link=users" method="post" name="optform" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <div class="uitabs">
        <ul id="tabs">
            <li><a href="#basic"><span><?php echo $_LANG['AD_PROFILE_SETTINGS']; ?></span></a></li>
            <li><a href="#avatars"><span><?php echo $_LANG['AD_AVATARS']; ?></span></a></li>
            <li><a href="#proftabs"><span><?php echo $_LANG['AD_PROFILES_TAB']; ?></span></a></li>
            <li><a href="#forms"><span><?php echo $_LANG['AD_MORE_FIELDS']; ?></span></a></li>
            <li><a href="#photos"><span><?php echo $_LANG['AD_PROFILE_SETTINGS']; ?></span></a></li>
            <li><a href="#files"><span><?php echo $_LANG['AD_FILE_ARCHIVES']; ?></span></a></li>
        </ul>

        <div id="basic">
            <div style="width:550px;">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_VIEV_PROFILES']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if ($cfg['sw_guest']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_guest" <?php if($cfg['sw_guest']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$cfg['sw_guest']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_guest" <?php if (!$cfg['sw_guest']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_SEARCH_USERS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if ($cfg['sw_search'] == 1) { echo 'active'; } ?>">
                            <input type="radio" name="sw_search" <?php if ($cfg['sw_search'] == 1) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$cfg['sw_search']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_search" <?php if (!$cfg['sw_search']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['AD_YES_ONLY_VIEW']; ?>
                        </label>
                        <label class="btn btn-default <?php if ($cfg['sw_search'] == 2) { echo 'active'; } ?>">
                            <input type="radio" name="sw_search" <?php if ($cfg['sw_search'] == 2) { echo 'checked="checked"'; } ?> value="2" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_SHOW_MORE_COMMENTS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($cfg['sw_comm']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_comm" <?php if($cfg['sw_comm']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$cfg['sw_comm']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_comm" <?php if (!$cfg['sw_comm']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_SHOW_FORUM']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if ($cfg['sw_forum']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_forum" <?php if($cfg['sw_forum']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$cfg['sw_forum']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_forum" <?php if (!$cfg['sw_forum']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_USERS_WALL']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if ($cfg['sw_wall']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_wall" <?php if($cfg['sw_wall']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$cfg['sw_wall']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_wall" <?php if (!$cfg['sw_wall']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_PERSONAL_BLOGS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if ($cfg['sw_blogs']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_blogs" <?php if($cfg['sw_blogs']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$cfg['sw_blogs']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_blogs" <?php if (!$cfg['sw_blogs']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_SHOW_ADS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if ($cfg['sw_board']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_board" <?php if($cfg['sw_board']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$cfg['sw_board']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_board" <?php if (!$cfg['sw_board']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_PRIVATE_MESS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if ($cfg['sw_msg']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_msg" <?php if($cfg['sw_msg']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$cfg['sw_msg']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_msg" <?php if (!$cfg['sw_msg']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_NOTIFICATION_TEXT']; ?>:</label>
                    <div><?php echo '/languages/'. cmsCore::c('config')->lang .'/letters/newmessage.txt'; ?></div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_PEROD_KARMA']; ?>:</label>
                    <input type="number" id="int_1" class="form-control" name="karmatime" min="0" value="<?php echo $cfg['karmatime']; ?>"/>
                    <select id="int_2" class="form-control" name="karmaint">
                        <option value="MINUTE"  <?php if (mb_strstr($cfg['karmaint'], 'MINUTE')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['MINUTE10']; ?></option>
                        <option value="HOUR"  <?php if (mb_strstr($cfg['karmaint'], 'HOUR')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['HOUR10']; ?></option>
                        <option value="DAY" <?php if (mb_strstr($cfg['karmaint'], 'DAY')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['DAY10']; ?></option>
                        <option value="MONTH" <?php if (mb_strstr($cfg['karmaint'], 'MONTH')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['MONTH10']; ?></option>
                    </select>
                    <div class="help-block"><?php echo $_LANG['AD_PEROD_KARMA_HINT']; ?></div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_DELETE_INACTIVE']; ?> (<?php echo $_LANG['MONTH10']; ?>):</label>
                    <input type="number" class="form-control" name="deltime" min="0" value="<?php echo $cfg['deltime']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_DELETE_INACTIVE_HINT']; ?></div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_USERS_ON_PAGE']; ?>:</label>
                    <input type="number" class="form-control" name="users_perpage" min="0" value="<?php echo $cfg['users_perpage']; ?>" />
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_NUMBER_ON_WALL']; ?>:</label>
                    <input type="number" class="form-control" name="wall_perpage" min="0" value="<?php echo $cfg['wall_perpage']; ?>" />
                </div>
            </div>
        </div>

        <div id="avatars">
            <div style="width:550px;">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_WIDTH_SMALL_AVATAR']; ?> (<?php echo $_LANG['AD_PX']; ?>):</label>
                    <input type="number" class="form-control" name="smallw" min="0" value="<?php echo $cfg['smallw']; ?>" />
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_WIDTH_LARGE_AVATAR']; ?> (<?php echo $_LANG['AD_PX']; ?>):</label>
                    <input type="number" class="form-control" name="medw" min="0" value="<?php echo $cfg['medw']; ?>" />
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_HEIGHT_LARGE_AVATAR']; ?> (<?php echo $_LANG['AD_PX']; ?>):</label>
                    <input type="number" class="form-control" name="medh" min="0" value="<?php echo $cfg['medh']; ?>" />
                </div>
            </div>
        </div>

        <div id="proftabs">
            <div style="width:550px;">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_TAB_RIBBON']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if ($cfg['sw_feed']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_feed" <?php if($cfg['sw_feed']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$cfg['sw_feed']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_feed" <?php if (!$cfg['sw_feed']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_TAB_CLUBS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if ($cfg['sw_clubs']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_clubs" <?php if($cfg['sw_clubs']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$cfg['sw_clubs']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_clubs" <?php if (!$cfg['sw_clubs']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_TAB_AWARDS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if ($cfg['sw_awards']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_awards" <?php if($cfg['sw_awards']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$cfg['sw_awards']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_awards" <?php if (!$cfg['sw_awards']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div id="forms">
            <div style="width:550px;">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_FORMS_IN_PROFILES']; ?>:</label>
                    <select class="form-control" name="privforms[]" size="10" multiple="multiple">
                    <?php
                        if (!empty($forms)) {
                            foreach ($forms as $f) {
                                if (in_array($f['id'], $cfg['privforms'])) { $selected='selected="selected"'; } else { $selected = ''; }
                                echo '<option value="'. $f['id'] .'" '. $selected .'>'. $f['title'] .'</option>';
                            }
                        }
                    ?>
                    </select>
                    <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL']; ?>.</div>
                    <div class="help-block"><?php echo $_LANG['AD_FORMS_IN_PROFILES_EDIT']; ?> <a href="index.php?view=components&do=config&link=forms"><?php echo $_LANG['AD_FORM_DESIGNER']; ?></a>.</div>
                </div>
            </div>
        </div>

        <div id="photos">
            <div style="width:550px;">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_PHOTO_ALBUMS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if ($cfg['sw_photo']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_photo" <?php if($cfg['sw_photo']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$cfg['sw_photo']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_photo" <?php if (!$cfg['sw_photo']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_ENABLE_WATERMARK']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($cfg['watermark']) { echo 'active'; } ?>">
                            <input type="radio" name="watermark" <?php if($cfg['watermark']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$cfg['watermark']) { echo 'active'; } ?>">
                            <input type="radio" name="watermark" <?php if (!$cfg['watermark']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                    <div class="help-block"><?php echo $_LANG['AD_APPLY_WATERMARK_HINT']; ?> &quot;<a href="/images/watermark.png" target="_blank">/images/watermark.png</a>&quot;</div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_LOTS_PHOTOS']; ?> (<?php echo $_LANG['AD_PIECES']; ?>):</label>
                    <input type="number" class="form-control" name="photosize" min="0" value="<?php echo $cfg['photosize']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_LOTS_PHOTOS_HINT']; ?></div>
                </div>
            </div>
        </div>

        <div id="files">
            <div style="width:550px;">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_USER_FILES']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if ($cfg['sw_files']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_files" <?php if ($cfg['sw_files']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$cfg['sw_files']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_files" <?php if (!$cfg['sw_files']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_DISK_SPACE']; ?> (<?php echo $_LANG['SIZE_MB']; ?>):</label>
                    <input type="number" class="form-control" name="filessize" min="0" value="<?php echo $cfg['filessize']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_DISK_SPACE_HINT']; ?></div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_FILE_TIPES']; ?>:</label>
                    <input type="text" class="form-control" name="filestype" size="30" value="<?php echo $cfg['filestype']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_FILE_TIPES_HINT']; ?></div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <input type="hidden" name="opt" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';" />
    </div>
</form>