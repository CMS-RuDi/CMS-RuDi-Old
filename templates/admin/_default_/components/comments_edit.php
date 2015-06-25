<h3><?php echo $_LANG['AD_EDIT_COMENT']; ?></h3>

<form id="addform" class="form-horizontal" role="form" name="addform" method="post" action="index.php?view=components&do=config&link=comments">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <div style="width:650px;">
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_COMENT_AUTHOR'];?></label>
            <div class="col-sm-7">
                <p class="form-control">
                <?php if ($mod['user_id'] == 0) { ?>
                    <input name="guestname" type="text" id="title" size="30" value="<?php echo $mod['guestname']; ?>" />
                <?php } else { ?>
                    <?php echo $mod['nickname']; ?><a target="_blank" href="/admin/index.php?view=users&do=edit&id=<?php echo $mod['user_id']; ?>"><?php echo $mod['login']; ?></a>
                <?php } ?>
                </p>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CALENDAR_DATE'];?></label>
            <div class="col-sm-7">
                <input type="text" class="form-control" name="pubdate" size="30" value="<?php echo $mod['pubdate']; ?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_IF_COMENT_PUBLIC'];?></label>
            <div class="col-sm-7 btn-group" data-toggle="buttons">
                <label class="btn btn-default <?php if ($mod['published']) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if($mod['published']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!$mod['published']) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if (!$mod['published']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
                <div style="clear:both;"></div>
                <div class="help-block"><?php echo $_LANG['AD_PUBLISH_CLUB_HINT']; ?></div>
            </div>
        </div>
        
        <div class="form-group">
            <?php cmsCore::insertEditor('content', $mod['content'], '250', '100%'); ?>
        </div>
    </div>
    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE'];?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL'];?>" onclick="window.location.href='index.php?view=components';"/>
        
        <input type="hidden" name="opt" value="update" />
        <input type="hidden" name="item_id" value="<?php echo $mod['id']?>" />
    </div>
</form>