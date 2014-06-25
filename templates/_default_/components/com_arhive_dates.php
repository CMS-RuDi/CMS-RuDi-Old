<div class="con_heading"><?php echo $pagetitle; ?></div>

<?php if ($items) { ?>
    <ul class="arhive_list">
        <?php foreach($items as $item) { ?>
            <li>
                <a href="/arhive/<?php echo $item['year']; ?>/<?php echo $item['month']; ?>"><?php echo $item['fmonth']; ?></a><?php if ($do == 'view') { ?>, <a href="/arhive/<?php echo $item['year']; ?>"><?php echo $item['year']; ?></a><?php } ?> <span>(<?php echo $this->spellcount($item['num'], $_LANG['ARTICLE1'], $_LANG['ARTICLE2'], $_LANG['ARTICLE10']); ?>)</span>
            </li>
        <?php } ?>
    </ul>
<?php } else { ?>
    <p><?php echo $_LANG['ARHIVE_NO_MATERIALS']; ?></p>
<?php } ?>