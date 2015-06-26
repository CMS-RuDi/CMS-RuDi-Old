<?php if ($opt == 'add_item') { ?>
    <h3><?php echo $_LANG['AD_ADD_QUESTION']; ?></h3>
<?php } else { ?>
    <h3><?php echo $_LANG['AD_VIEW_QUESTION']; ?></h3>
<?php } ?>

<form action="index.php?view=components&amp;do=config&amp;link=faq" method="post" enctype="multipart/form-data" name="addform" id="addform">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_CAT_QUESTION']; ?>:</label>
            <select class="form-control" name="category_id">
                <?php echo $faq_cats_opt; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ASKER']; ?>:</label>
            <select class="form-control" name="user_id">
                <option value="0" <?php if (!$mod['user_id']) { echo 'selected="selected"'; } ?>>-- <?php echo $_LANG['AD_ANONYMOUS']; ?> --</option>
                <?php echo $users_opt; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_POST_QUESTION']; ?>:</label>
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
            <label class="col-sm-5" style="padding-left:0;"><?php echo $_LANG['AD_DATE_QUESTION']; ?>:</label>
            <div class="col-sm-7">
                <input type="text" id="pubdate" class="form-control" style="display:inline-block;width:auto;" name="pubdate" value="<?php if (!cmsCore::getArrVal($mod, 'pubdate', false)) { echo date('d.m.Y'); } else { echo $mod['pubdate']; } ?>" />
                <input type="hidden" name="oldpubdate" value="<?php echo cmsCore::getArrVal($mod, 'pubdate', ''); ?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5" style="padding-left:0;"><?php echo $_LANG['AD_DATE_REPLY']; ?>:</label>
            <div class="col-sm-7">
                <input type="text" id="answerdate" class="form-control" style="display:inline-block;width:auto;" name="answerdate" value="<?php if (!cmsCore::getArrVal($mod, 'answerdate', false)) { echo date('d.m.Y'); } else { echo $mod['answerdate']; } ?>" />
                <input type="hidden" name="oldanswerdate" value="<?php echo cmsCore::getArrVal($mod, 'answerdate', ''); ?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_TEXT_QUESTION']; ?>:</label>
            <textarea id="quest" class="form-control" name="quest" rows="6"><?php echo cmsCore::getArrVal($mod, 'quest', ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ANSWER_QUESTION']; ?>:</label>
            <?php cmsCore::insertEditor('answer', $mod['answer'], '300', '605'); ?>
        </div>
    </div>

    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back2" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&link=faq';" />
        
        <input type="hidden" id="do" name="opt" value="<?php if ($opt == 'add_item') { echo 'submit_item'; } else { echo 'update_item'; } ?>" />
        <?php
            if ($opt == 'edit_item') {
                echo '<input type="hidden" name="item_id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>