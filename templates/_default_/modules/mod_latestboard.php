<?php if ($items) { ?>
<ul class="new_board_items">
    <?php foreach ($items as $item) { ?>
        <li <?php if ($item['is_vip']) { echo 'class="vip"'; } ?>>
            <a href="/board/read<?php echo $item['id']; ?>.html"><?php echo $item['title']; ?></a> &mdash; <?php echo $item['fpubdate']; if ($cfg['showcity']) { ?> - <span class="board_city"><?php echo $item['city']; ?></span><?php } ?>
        </li>
    <?php } ?>
</ul>
<?php } else { ?>
<p><?php echo $_LANG['LATESTBOARD_NOT_ADV']; ?></p>
<?php } ?>