<h3><?php echo $_LANG['ADD_ITEM']; ?></h3>
<h4><?php echo $_LANG['AD_SELECT_CAT']; ?>:</h4>

<div style="padding:10px">
    <?php foreach ($cats as $cat) { ?>
        <div style="padding:2px;padding-left:18px;margin-left:<?php echo (($cat['NSLevel']-1)*15); ?>px;background:url(/admin/images/icons/hmenu/cats.png) no-repeat">
            <a href="/catalog/<?php echo $cat['id']; ?>/add.html"><?php echo $cat['title']; ?></a>
        </div>
    <?php } ?>
</div>