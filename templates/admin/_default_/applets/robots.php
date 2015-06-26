<form action="" method="post">
    <div style="width:650px;">
        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
        
        <div class="form-group">
            <label><?php echo $_LANG['ROBOTS_TXT_DATA']; ?></label>
            <textarea name="robots" class="form-control" style="height: 400px;"><?php echo $robots; ?></textarea>
            <div class="help-block"><?php echo $_LANG['ROBOTS_TXT_INFO']; ?></div>
        </div>
        
        <input type="hidden" name="do" value="save" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();" />
    </div>
</form>