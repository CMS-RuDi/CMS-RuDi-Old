<?php if ($opt == 'add') { ?>
<h3><?php echo $_LANG['CREATE_CLUB']; ?></h3>
<?php } else { ?>
<h3><?php echo $mod['title'] .' '. $ostatok; ?></h3>
<?php } ?>

<form id="addform" class="form-horizontal" role="form" name="addform" action="index.php?view=components&amp;do=config&amp;link=clubs" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div class="uitabs" style="width:600px;">
        <ul>
            <li><a href="#tab_ad_overall"><?php echo $_LANG['AD_OVERALL']; ?></a></li>
            <li><a href="#tab_club_desc"><?php echo $_LANG['CLUB_DESC']; ?></a></li>
            <li><a href="#tab_access"><?php echo $_LANG['AD_TAB_ACCESS']; ?></a></li>
        </ul>
        
        <div id="tab_ad_overall">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['CLUB_NAME'];?></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" name="title" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'title', '')); ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['UPLOAD_LOGO'];?></label>
                <div class="col-sm-7">
                    <?php if (cmsCore::getArrVal($mod, 'imageurl', false)) { ?>
                        <div style="margin-bottom:5px;text-align:center;"><img src="/images/clubs/small/<?php echo $mod['imageurl']; ?>" /></div>
                    <?php } ?>
                    <input type="file" class="form-control" name="picture" size="33" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['MAX_MEMBERS'];?></label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="maxsize" value="<?php echo cmsCore::getArrVal($mod, 'maxsize', ''); ?>"/>
                    <div class="help-block"><?php echo $_LANG['MAX_MEMBERS_TEXT']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CLUB_DATE'];?></label>
                <div class="col-sm-7">
                    <input type="text" id="pubdate" class="form-control" style="display:inline-block;width:auto;" name="pubdate" value="<?php if (!cmsCore::getArrVal($mod, 'pubdate', false)) { echo date('d.m.Y'); } else { echo date('d.m.Y', strtotime($mod['pubdate'])); } ?>" />
                    <input type="hidden" name="olddate" value="<?php echo date('d.m.Y', strtotime(cmsCore::getArrVal($mod, 'pubdate', 0)))?>"/>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_PUBLISH_CLUB'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                        <input type="radio" name="published" <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                        <input type="radio" name="published" <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                    <div style="clear:both;"></div>
                    <div class="help-block"><?php echo $_LANG['AD_PUBLISH_CLUB_HINT']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['CLUB_BLOG'];?></label>
                <div class="col-sm-7">
                    <select class="form-control" name="enabled_blogs">
                        <option value="-1" <?php if (cmsCore::getArrVal($mod, 'orig_enabled_blogs', false) == '-1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DEFAULT']; ?></option>
                        <option value="1" <?php if (cmsCore::getArrVal($mod, 'orig_enabled_blogs', false) == '1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_IS_ENABLED']; ?></option>
                        <option value="0" <?php if (cmsCore::getArrVal($mod, 'orig_enabled_blogs', false) == '0') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_IS_DISABLED']; ?></option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['CLUB_PHOTOALBUMS'];?></label>
                <div class="col-sm-7">
                    <select class="form-control" name="enabled_photos">
                        <option value="-1" <?php if (cmsCore::getArrVal($mod, 'orig_enabled_photos', false) == '-1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DEFAULT']; ?></option>
                        <option value="1" <?php if (cmsCore::getArrVal($mod, 'orig_enabled_photos', false) == '1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_IS_ENABLED']; ?></option>
                        <option value="0" <?php if (cmsCore::getArrVal($mod, 'orig_enabled_photos', false) == '0') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_IS_DISABLED']; ?></option>
                    </select>
                </div>
            </div>
        </div>
        
        <div id="tab_club_desc">
            <div class="form-group">
                <?php cmsCore::insertEditor('description', $mod['description'], '400', '100%'); ?>
            </div>
        </div>
        
        <div id="tab_access">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['CLUB_ADMIN'];?></label>
                <div class="col-sm-7">
                    <select class="form-control" name="admin_id">
                        <?php echo $users_opt; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['CLUB_TYPE'];?></label>
                <div class="col-sm-7">
                    <select class="form-control" name="clubtype">
                        <option value="public" <?php if (cmsCore::getArrVal($mod, 'clubtype', false) == 'public') { echo 'selected="selected"'; } ?>><?php echo $_LANG['PUBLIC']; ?></option>
                        <option value="private" <?php if (cmsCore::getArrVal($mod, 'clubtype', false) == 'private') { echo 'selected="selected"'; } ?>><?php echo $_LANG['PRIVATE']; ?></option>
                    </select>
                </div>
            </div>
            
            <?php if($opt == 'edit'){ ?>
                <p><?php echo $_LANG['AD_MEMBERS_EDIT_ON_SITE']; ?> <a target="_blank" href="/clubs/<?php echo $mod['id']; ?>/config.html#moders"><?php echo $_LANG['AD_EDIT_ON_SITE']; ?></a>.</p>
            <?php } ?>
        </div>
    </div>

    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back3" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';"/>
        <input type="hidden" name="opt" value="<?php if ($opt == 'add') { echo 'submit'; } else { echo 'update'; } ?>" />
        <?php
        if ($opt == 'edit') {
            echo '<input name="item_id" type="hidden" value="'. $mod['id'] .'" />';
        }
        ?>
    </div>
</form>