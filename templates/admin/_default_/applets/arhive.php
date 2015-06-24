<form action="index.php?view=arhive&do=saveconfig" method="post" name="optform" target="_self" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:550px">
        <div class="form-group">
            <label><?php echo $_LANG['AD_SOURCE_MATERIALS']; ?></label>
            <select class="form-control" name="source">
                <option value="content" <?php if ($cfg_arhive['source'] == 'content') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_ARTICLE_SITE']; ?></option>
                <option value="arhive" <?php if ($cfg_arhive['source'] == 'arhive') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_ARTICLES_ARCHIVE']; ?></option>
                <option value="both" <?php if ($cfg_arhive['source'] == 'both') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_CATALOG_AND_ARCHIVE']; ?></option>
            </select>
        </div>
    </div>
    
    <div>
        <input type="hidden" name="opt" value="saveconfig" />
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=arhive';" />
    </div>
</form>