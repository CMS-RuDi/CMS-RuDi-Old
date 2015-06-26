<h3><?php echo $_LANG['AD_EDIT_BLOG'] .' '. $ostatok; ?></h3>

<form action="index.php?view=components&do=config&link=blogs&opt=update_blog&item_id=<?php echo $mod['id']; ?>" method="post" name="optform" target="_self" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_BLOG_NAME']; ?>: </label>
            <input type="text" class="form-control" name="title" value="<?php echo $this->escape($mod['title']);?>" />
            <div class="help-block"><?php echo $_LANG['AD_CHANGE_URL']; ?></div>
        </div>
    </div>
    
    <div>
        <input name="opt" type="hidden" value="update_blog" />
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&link=blogs&opt=list_blogs';"/>
    </div>
</form>