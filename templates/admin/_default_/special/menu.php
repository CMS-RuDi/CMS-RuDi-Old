<?php foreach ($items as $key => $item) { ?>
    <?php if (!empty($item['dropdown'])) { ?>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa <?php echo $item['class']; ?>"></i>
            <?php echo $item['title']; ?>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <?php foreach ($item['items'] as $k => $it) { ?>
            <li>
                <a href="<?php echo $it['link']; ?>" class="fa <?php echo $it['class']; ?>"<?php if (isset($it['target'])) {?> target="<?php echo $it['target']; ?>"<?php } ?>>
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
    <li>
        <a href="<?php echo $item['link']; ?>" class="fa <?php echo $item['class']; ?>"<?php if (isset($item['target'])) {?> target="<?php echo $item['target']; ?>"<?php } ?>>
            <?php if (!empty($item['icon'])) { ?>
            <img src="<?php echo $item['icon']; ?>" />
            <?php } ?>
            <?php echo $item['title']; ?>
        </a>
    </li>
    <?php } ?>
<?php } ?>