<form action="index.php?view=components&do=config&link=search" name="optform" method="post" target="_self">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:500px">
        <div class="form-group">
            <label><?php echo $_LANG['AD_RESULTS_PAGE']; ?>:</label>
            <input type="number" class="form-control" name="perpage" min="0" value="<?php echo $cfg['perpage']; ?>" size="6" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SEARCH_PROVIDER']; ?>:</label>
            <select class="form-control" name="search_engine">
                <option value="" <?php if (!$cfg['search_engine']) {?>selected="selected"<?php } ?>><?php echo $_LANG['AD_NATIVE']; ?></option>
                <?php
                if ($provider_array) {
                    foreach($provider_array as $provider){
                ?>
                        <option value="<?php echo $provider; ?>" <?php if ($cfg['search_engine'] == $provider) {?>selected="selected"<?php } ?>><?php echo str_replace('_', ' ', $provider); ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>
        
        <?php
            if ($provider_config) {
                foreach ($provider_config as $key => $value) {
        ?>
                    <div class="form-group">
                        <label><?php echo cmsCore::getArrVal($_LANG, $key, $value); ?>:</label>
                        <input type="text" class="form-control" name="<?php echo $value; ?>" value="<?php echo $cfg[$cfg['search_engine']][$value]; ?>" />
                    </div>
        <?php
                }
            }
	?>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SEARCH_COMPONENTS']; ?>:</label>
            <?php
                foreach ($components as $component) {
                    $checked = '';
                    if (in_array($component['link'], $cfg['comp'])) {
                        $checked = 'checked="checked"';
                    }
                    echo '<div class="checkbox checkbox-primary"><input name="comp[]" id="scom_'. $component['link'] .'" type="checkbox" value="'. $component['link'] .'" '. $checked .'/> <label for="scom_'. $component['link'] .'">'. $component['title'] .'</label></div>';
                }
            ?>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SEARCH_CASH']; ?>:</label>
            <?php
                echo $records .' '. $_LANG['AD_PIECES'];
                if ($records) {
                    echo ' | <a href="?view=components&do=config&link=search&opt=dropcache">'. $_LANG['AD_CLEAN'] .'</a>';
                }
            ?>
        </div>
    </div>
    
    <div>
        <input type="hidden" name="opt" value="save" />
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';" />
    </div>
</form>