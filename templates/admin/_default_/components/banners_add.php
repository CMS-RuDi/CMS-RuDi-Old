<h3>
    <?php if ($opt == 'add') { ?>
        <?php echo $_LANG['AD_ADD_BANNER']; ?>
    <?php } else { ?>
        <?php echo $mod['title'] .' '. $ostatok; ?>
    <?php } ?>
</h3>

<?php if ($opt == 'edit') { ?>
    <div style="width:550px;text-align:center;">
        <?php echo $banner_html; ?>
    </div>
<?php } ?>

<form action="index.php?view=components&amp;do=config&amp;id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" name="addform" id="addform">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />

    <div style="width:550px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_BANNER_TITLE']; ?></label>
            <input type="text" class="form-control" name="title" size="45" value="<?php echo cmsCore::getArrVal($mod, 'title', ''); ?>" />
            <div class="help-block"><?php echo $_LANG['AD_BANNER_DISPLAYED']; ?></div>
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_BANNER_LINK']; ?></label>
            <input type="text" class="form-control" name="b_link" size="45" value="<?php echo cmsCore::getArrVal($mod, 'link', ''); ?>" />
            <div class="help-block"><?php echo $_LANG['AD_BANNER_REMINDER']; ?></div>
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_POSITION']; ?></label>
            <select class="form-control" name="position">
                <?php for($m=1;$m<=30;$m++){ ?>
                    <option value="banner<?php echo $m; ?>" <?php if(cmsCore::getArrVal($mod, 'position', '') == 'banner'. $m) { echo 'selected'; } ?>>banner<?php echo $m; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_BANNER_TYPE']; ?></label>
            <select class="form-control" name="typeimg">
                <option value="image" <?php if(cmsCore::getArrVal($mod, 'typeimg', '') == 'image') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BANNER_IMAGE']; ?></option>
                <option value="swf" <?php if(cmsCore::getArrVal($mod, 'typeimg', '') == 'swf') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BANNER_FLASH']; ?></option>
            </select>
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_BANNER_FILE']; ?></label>
            <?php if (cmsCore::getArrVal($mod, 'file', '')) {
                echo '<a href="/images/photos/'. $mod['file'] .'" title="'. $_LANG['AD_BANNER_VIEW_PHOTO'] .'">'. $mod['file'] .'</a>';
            } else { ?>
                <input name="picture" type="file" id="picture" size="30" />
            <?php } ?>
            <div class="help-block"><?php echo $_LANG['AD_BANNER_FILE_TYPES']; ?></div>
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_BANNER_MAX_HITS']; ?> (<?php echo $_LANG['AD_HITS_LIMIT']; ?>)</label>
            <input type="number" class="form-control" name="maxhits" min="0" value="<?php echo cmsCore::getArrVal($mod, 'maxhits', ''); ?>" /> 
            <div class="help-block"><?php echo $_LANG['AD_UNLIMITED_HITS']; ?></div>
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_BANNER_PUBLISH']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                <label class="btn btn-default <?php if (cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if (cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>

        <div class="help-block"><strong><?php echo $_LANG['AD_NOTE']; ?></strong> <?php echo $_LANG['AD_BANNER_NOTE']; ?></div>
    </div>

    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back3" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&amp;do=config&amp;id=<?php echo $id; ?>';"/>

        <input name="opt" type="hidden" id="opt" <?php if ($opt == 'add') { echo 'value="submit"'; } else { echo 'value="update"'; } ?> />
        <?php
        if ($opt == 'edit') {
            echo '<input name="item_id" type="hidden" value="'. $mod['id'] .'" />';
        }
        ?>
    </div>
</form>