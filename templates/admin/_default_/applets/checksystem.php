<form class="form-horizontal" role="form" action="/admin/index.php?view=checksystem&do=start_scan" method="post" name="CFGform" target="_self" style="margin-bottom:30px">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:750px;">
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SELECT_IMG']; ?></label>
            <div class="col-sm-7">
                <select id="image" class="form-control" name="image">
                    <?php foreach ($imageFiles as $if) { ?>
                        <option value="<?php echo $if; ?>"><?php echo $if; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        
        <div>
            <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['AD_START']; ?>" />
            <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();" />
        </div>
    </div>
</form>