<form action="index.php?view=tickets&do=submit" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div class="panel panel-default" style="width:650px;">
        <div class="panel-body">
            <div class="form-group">
                <label><?php echo $_LANG['AD_TICKET_CAT']; ?></label>
                <select class="form-control" name="cat_id">
                    <?php foreach ($cats as $cat) { ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['title']; ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_TICKET_PRIORITY']; ?></label>
                <select class="form-control" name="priority">
                    <option value="0"><?php echo $_LANG['AD_TICKET_PRIORITY_0']; ?></option>
                    <option value="1"><?php echo $_LANG['AD_TICKET_PRIORITY_1']; ?></option>
                    <option value="2"><?php echo $_LANG['AD_TICKET_PRIORITY_2']; ?></option>
                    <option value="3"><?php echo $_LANG['AD_TICKET_PRIORITY_3']; ?></option>
                </select>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_TICKET_TITLE']; ?></label>
                <input type="text" class="form-control" name="title" value="" required="true" maxlength="256" />
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_TICKET_MSG']; ?></label>
                <textarea class="form-control" name="msg" style="height: 200px;"></textarea>
            </div>
        </div>
    </div>
    
    <div style="margin-top:5px">
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['AD_TICKET_SUBMIT']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=tickets';" />
    </div>
</form>