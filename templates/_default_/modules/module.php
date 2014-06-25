<div class="<?php echo $mod['css_prefix']; ?>module">
    <?php if ($mod['showtitle'] != 0) { ?>
        <div class="<?php echo $mod['css_prefix']; ?>moduletitle">
            <?php echo $mod['title']; ?>
            <?php if ($cfglink) { ?>
                <span class="fast_cfg_link">
                    <a href="javascript:moduleConfig(<?php echo $mod['id']; ?>)" title="<?php echo $_LANG['CONFIG_MODULE']; ?>">
                        <img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/settings.png"/>
                    </a>
                </span>
            <?php } ?>
        </div>
    <?php } ?>
    <div class="<?php echo $mod['css_prefix']; ?>modulebody"><?php echo $mod['body']; ?></div>
</div>