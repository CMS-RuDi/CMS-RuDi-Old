<form id="addform" name="addform" method="post" action="index.php?view=components&do=config&link=catalog">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['TITLE'];?>:</label>
            <input type="text" class="form-control" name="title" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'title', '')); ?>"/>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_CAT_BOARD'];?>:</label>
            <select class="form-control" name="cat_id">
                <option value="<?php echo $rootid; ?>" <?php if (cmsCore::getArrVal($mod, 'cat_id', $rootid) == $rootid) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_ALL_CAT'];?></option>
                <?php echo $uc_cats_opt; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_TYPE'];?></label>
            <select id="sign" class="form-control" name="sign" onchange="toggleDiscountLimit()">
                <option value="-1" <?php if (cmsCore::getArrVal($mod, 'sign') == -1) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PRODUCT_DISCOUNT']; ?>)</option>
                <option value="1" <?php if (cmsCore::getArrVal($mod, 'sign') == 1) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PRODUCT_ALLOWANCE']; ?>)</option>
                <option value="2" <?php if (cmsCore::getArrVal($mod, 'sign') == 2) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_ORDER_ALLOWANCE']; ?></option>
                <option value="3" <?php if (cmsCore::getArrVal($mod, 'sign') == 3) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_ORDER_DISCOUNT']; ?></option>
            </select>
        </div>
        
        <div class="if_limit form-group" <?php if (cmsCore::getArrVal($mod, 'sign') != 3) { echo 'style="display:none;"'; } ?>>
            <label><?php echo $_LANG['AD_MIN_COST'];?> (<?php echo $_LANG['CURRENCY'];?>)</label>
            <input type="number" id="value" class="form-control" name="if_limit" size="5" value="<?php echo cmsCore::getArrVal($mod, 'if_limit', 0); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_UNITS'];?>:</label>
            <select id="unit" class="form-control" name="unit" >
                <option value="%" <?php if (cmsCore::getArrVal($mod, 'unit') == '%') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PERCENT'];?></option>
                <option value="<?php echo $_LANG['CURRENCY'];?>" <?php if (cmsCore::getArrVal($mod, 'unit') == $_LANG['CURRENCY']) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_CURRENCY_NAME'];?></option>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_VALUE'];?>:</label>
            <input type="text" id="value" class="form-control" name="value" size="5" value="<?php echo cmsCore::getArrVal($mod, 'value', ''); ?>" />
        </div>
    </div>

    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back3" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';" />
        
        <input name="opt" type="hidden" id="do" <?php if ($opt == 'add_discount') { echo 'value="submit_discount"'; } else { echo 'value="update_discount"'; } ?> />
        <?php
        if ($opt == 'edit_discount') {
            echo '<input name="item_id" type="hidden" value="'. $mod['id'] .'" />';
        }
        ?>
    </div>
</form>