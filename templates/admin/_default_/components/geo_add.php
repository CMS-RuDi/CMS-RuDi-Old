<?php if (!$sub_opt && !$item_id) { ?>
    <h3><?php echo $_LANG['AD_WHAT_ADD']; ?></h3>
    <ul style="font-size: 14px;">
        <li><a href="?view=components&do=config&link=geo&opt=add&sub_opt=country">
            <?php echo $_LANG['AD_COUNTRY']; ?></a>
        </li>
        <li><a href="?view=components&do=config&link=geo&opt=add&sub_opt=region">
            <?php echo $_LANG['AD_REGION']; ?></a>
        </li>
        <li><a href="?view=components&do=config&link=geo&opt=add&sub_opt=city">
            <?php echo $_LANG['AD_CITY']; ?></a>
        </li>
    </ul>
<?php } else { ?>
    <form action="index.php?view=components&amp;do=config&amp;link=geo" method="post" name="optform">
        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />

        <div style="width:400px;">
            <div class="form-group">
                <label><?php echo $_LANG['TITLE']; ?></label>
                <input type="text" class="form-control" name="name" value="<?php echo $this->escape(cmsCore::getArrVal($item, 'name', '')); ?>" />
            </div>

            <?php if ($sub_opt == 'country') { ?>
                <div class="form-group">
                    <label>alpha2</label>
                    <input type="text" class="form-control" name="alpha2" value="<?php echo $this->escape(cmsCore::getArrVal($item, 'alpha2', '')); ?>" />
                </div>

                <div class="form-group">
                    <label>alpha3</label>
                    <input type="text" class="form-control" name="alpha3" value="<?php echo $this->escape(cmsCore::getArrVal($item, 'alpha3', '')); ?>" />
                </div>

                <div class="form-group">
                    <label>alpha3</label>
                    <input type="text" class="form-control" name="iso" value="<?php echo $this->escape(cmsCore::getArrVal($item, 'iso', '')); ?>" />
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_ORDER']; ?></label>
                    <input type="text" class="form-control" name="ordering" value="<?php echo cmsCore::getArrVal($item, 'ordering', ''); ?>" />
                </div>
            <?php } else if ($sub_opt == 'region') { ?>
                <div class="form-group">
                    <label><?php echo $_LANG['AD_COUNTRY1']; ?></label>
                    <select class="form-control" name="country_id">
                        <?php echo $countries_opt; ?>
                    </select>
                </div>
            <?php } else { ?>
                <div class="form-group">
                    <label><?php echo $_LANG['AD_COUNTRY1']; ?></label>
                    <select class="form-control" name="country_id">
                        <?php echo $countries_opt; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_REGION']; ?></label>
                    <select class="form-control" name="region_id">
                        <?php echo $regions_opt; ?>
                    </select>
                </div>
            <?php } ?>
        </div>

        <div>
            <input type="hidden" name="opt" value="do_<?php echo $opt; ?>" />
            <input type="hidden" name="sub_opt" value="<?php echo $sub_opt; ?>" />
            <input type="hidden" name="item_id" value="<?php echo cmsCore::getArrVal($item, 'id', ''); ?>" />

            <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
            <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&amp;do=config&amp;link=geo';"/>
        </div>
    </form>
<?php }