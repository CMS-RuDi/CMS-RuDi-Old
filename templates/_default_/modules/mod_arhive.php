<ul class="arhive_list">
    <?php foreach ($arhives as $id => $item) { ?>
        <li>
            <a href="/arhive/<?php echo $item['year']; ?>/<?php echo $item['month']; ?>"><?php echo $item['fmonth']; ?></a><?php echo ($date['year'] == 'all' ? ', <a href="/arhive/'. $item['year'] .'">'. $item['year'] .'</a>' : '') ?> <span>(<?php echo $this->spellcount($item['num'], $_LANG['ARTICLE1'], $_LANG['ARTICLE2'], $_LANG['ARTICLE10']); ?>)</span>
        </li>
    <?php } ?>
</ul>