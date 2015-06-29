<?php foreach ($items as $key => $item) { ?>
    <?php if (!empty($item['items'])) { ?>
    <li class="dropdown <?php if (isset($item['active'])) { ?>active<?php } ?>">
        <a class="dropdown-toggle <?php if (isset($item['active'])) { ?>active<?php } ?>" data-toggle="dropdown" href="#">
            <?php if (!empty($item['icon'])) { ?>
                <img src="<?php echo $item['icon']; ?>"<?php if (!isset($without_title)) { ?> class="main_menu_icon" <?php } ?>/>
            <?php } else { ?>
                <i class="fa <?php echo $item['class']; ?>"></i>
            <?php } ?>
            <?php if (!isset($without_title)) { ?>
                <?php echo $item['title']; ?>
            <?php } ?>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <?php foreach ($item['items'] as $k => $it) { ?>
            <li <?php if (isset($it['active'])) { ?>class="active"<?php } ?>>
                <a href="<?php echo $it['link']; ?>" class="fa <?php echo $it['class']; ?> <?php if (isset($it['active'])) { ?>active<?php } ?>"<?php if (isset($it['target'])) {?> target="<?php echo $it['target']; ?>"<?php } ?> title="<?php echo $this->escape($it['title']); ?>">
                    <?php if (!empty($it['icon'])) { ?>
                        <img src="<?php echo $it['icon']; ?>" class="main_menu_icon" />
                    <?php } ?>
                    <?php echo $it['title']; ?>
                </a>
            </li>
            <?php } ?>
        </ul>
    </li>
    <?php } else { ?>
    <li <?php if (isset($item['active'])) { ?>class="active"<?php } ?>>
        <a href="<?php echo isset($item['link']) ? $item['link'] : 'javascript:void(return false);'; ?>" class="fa <?php echo $item['class']; ?> <?php if (isset($item['active'])) { ?>active<?php } ?>"<?php if (isset($item['target'])) {?> target="<?php echo $item['target']; ?>"<?php } ?> title="<?php echo $this->escape($item['title']); ?>">
            <?php if (!empty($item['icon'])) { ?>
                <img src="<?php echo $item['icon']; ?>"<?php if (!isset($without_title)) { ?> class="main_menu_icon" <?php } ?>/>
            <?php } ?>
            <?php if (!isset($without_title)) { ?>
                <?php echo $item['title']; ?>
            <?php } ?>
        </a>
    </li>
    <?php } ?>
<?php } ?>