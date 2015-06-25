<form action="index.php?view=components&do=config&id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" name="addform" id="addform">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />

    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_AWARD_TITLE']; ?></label>
            <input type="text" class="form-control" name="title" size="45" value="<?php echo cmsCore::getArrVal($mod, 'title', '');?>"/>
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_AWARD_DESCRIPTION']; ?></label>
            <textarea class="form-control" name="description" size="45"><?php echo cmsCore::getArrVal($mod, 'description', ''); ?></textarea>
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_ENABLE_AWARD_CONFIRM']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                <label class="btn btn-default <?php if (cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if (cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
            <div class="help-block"><?php echo $_LANG['AD_DISALLOW_TEXT']; ?></div>
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_AWARD_IMAGE']; ?></label>
            <div>
            <?php
                $imageurl = cmsCore::getArrVal($mod, 'imageurl', '');
                foreach($awards_img as $img) {
            ?>
                <div style="float:left;margin:4px;">
                    <label>
                        <img src="/images/users/awards/<?php echo $img; ?>" /><br/>
                        <input type="radio" name="imageurl" value="<?php echo $img; ?>" <?php if ($imageurl == $img) { echo 'checked="checked"'; } ?> />
                    </label>
                </div>
            <?php } ?>
                <div style="clear:both;"></div>
            </div>
            <div class="help-block"><?php echo $_LANG['AD_AWARD_FOLDER']; ?></div>
        </div>

        <div class="form-group">
            <label><?php echo $_LANG['AD_AWARD_CONDITION_TITLE']; ?></label>

            <div class="input-group">
                <span class="input-group-addon fa fa-comment-o"></span>
                <input type="number" class="form-control" name="p_comment" min="0" value="<?php echo cmsCore::getArrVal($mod, 'p_comment', 0); ?>" />
                <span class="input-group-addon"><?php echo $_LANG['COMMENT10']; ?></span>
            </div>

            <div class="input-group">
                <span class="input-group-addon fa fa-comments-o"></span>
                <input type="number" class="form-control" name="p_forum" min="0" value="<?php echo cmsCore::getArrVal($mod, 'p_forum', 0); ?>" />
                <span class="input-group-addon"><?php echo $_LANG['AD_FORUM_MESSAGES']; ?></span>
            </div>

            <div class="input-group">
                <span class="input-group-addon fa fa-file-text-o"></span>
                <input type="number" class="form-control" name="p_content" min="0" value="<?php echo cmsCore::getArrVal($mod, 'p_content', 0); ?>" />
                <span class="input-group-addon"><?php echo $_LANG['AD_PUBLISHED_ARTICLES']; ?></span>
            </div>

            <div class="input-group">
                <span class="input-group-addon fa fa-pencil"></span>
                <input type="number" class="form-control" name="p_blog" min="0" value="<?php echo cmsCore::getArrVal($mod, 'p_blog', 0); ?>" />
                <span class="input-group-addon"><?php echo $_LANG['AD_BLOG_POSTS']; ?></span>
            </div>

            <div class="input-group">
                <span class="input-group-addon fa fa-heart-o"></span>
                <input type="number" class="form-control" name="p_karma" min="0" value="<?php echo cmsCore::getArrVal($mod, 'p_karma', 0); ?>" />
                <span class="input-group-addon"><?php echo $_LANG['AD_KARMA_POINTS']; ?></span>
            </div>

            <div class="input-group">
                <span class="input-group-addon fa fa-picture-o"></span>
                <input type="number" class="form-control" name="p_photo" min="0" value="<?php echo cmsCore::getArrVal($mod, 'p_photo', 0); ?>" />
                <span class="input-group-addon"><?php echo $_LANG['AD_PUBLIC_PHOTOS']; ?></span>
            </div>

            <div class="input-group">
                <span class="input-group-addon fa fa-lock"></span>
                <input type="number" class="form-control" name="p_privphoto" min="0" value="<?php echo cmsCore::getArrVal($mod, 'p_privphoto', 0); ?>" />
                <span class="input-group-addon"><?php echo $_LANG['AD_PRIVATE_PHOTOS']; ?></span>
            </div>

            <div class="help-block"><?php echo $_LANG['AD_AWARD']; ?> <?php echo $_LANG['AD_AWARD_CONDITION_TEXT']; ?></div>
        </div>
    </div>

    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back3" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>';" />

        <input type="hidden" name="opt" <?php if ($opt == 'add') { echo 'value="submit"'; } else { echo 'value="update"'; } ?> />
        <?php if ($opt == 'edit') { echo '<input name="item_id" type="hidden" value="'. $mod['id'] .'" />'; } ?>
    </div>
</form>

<style type="text/css">
    #p_input {
        border: solid 1px silver;
        text-align: center;
        margin-left: 4px;
        margin-right: 6px;
    }
    #p_input:hover {
        border: solid 1px gray;
        background-color: #EBEBEB;
        text-align: center;
        margin-left: 4px;
        margin-right: 6px;
    }
    .input-group-addon {
        width: 240px;
    }
    .input-group-addon.fa {
        width: 40px;
    }
</style>