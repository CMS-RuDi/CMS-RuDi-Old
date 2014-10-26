<ul id="<?php echo $menu; ?>" class="menu">
    <?php if ($cfg['show_home']) { ?>
        <li <?php if ($menuid == 1) { ?>class="selected"<?php } ?>>
            <a href="/" <?php if ($menuid == 1) { ?>class="selected"<?php } ?>><span><?php echo $_LANG['PATH_HOME']; ?></span></a>
        </li>
    <?php } ?>

    <?php foreach($items as $item) { ?>
        <?php if ($item['NSLevel'] == $last_level) { ?></li><?php } ?>

        <?php $tail = $last_level - $item['NSLevel']; ?>

        <?php for ($i=0;$i<$tail;$i++) { echo '</li></ul></li>'; } ?>
        
        <?php if ($item['NSLevel'] > 1 && $item['NSLevel'] > $last_level) { ?><ul><?php } ?>

            <li class="<?php echo $item['css_class']; ?> <?php if (($menuid == $item['id'] || $current_uri == $item['link']) || ($currentmenu['NSLeft'] > $item['NSLeft'] && $currentmenu['NSRight'] < $item['NSRight'])) { ?>selected<?php } ?>">
                <a href="<?php echo $item['link']; ?>" target="<?php echo $item['target']; ?>" <?php if ($menuid == $item['id'] || $current_uri == $item['link']) { ?>class="selected"<?php } ?> title="<?php echo $this->escape($item['title']); ?>">
                    <span>
                        <?php if ($item['iconurl']) { ?><img src="/images/menuicons/<?php echo $item['iconurl']; ?>" alt="<?php echo $this->escape($item['title']); ?>" /><?php } ?>
                        <?php echo $item['title']; ?>
                    </span>
                </a>
            <?php $last_level = $item['NSLevel']; ?>
    <?php } ?>

    <?php for ($i=0;$i<$last_level;$i++) { echo '</li>'; } ?>
</ul>