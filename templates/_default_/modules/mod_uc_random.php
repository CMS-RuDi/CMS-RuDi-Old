<?php if ($is_uc) { ?>
    <?php foreach($items as $item) { ?>
        <div align="center" id="uc_random_img"><a href="/catalog/item<?php echo $item['id']; ?>.html"><img src="/images/catalog/small/<?php echo $item['imageurl']; ?>" border="0"/></a></div>
        
        <?php if ($cfg['showtitle']) { ?>
            <div style="margin-top:10px" id="uc_random_title" align="center"><a href="/catalog/item<?php echo $item['id']; ?>.html"><strong><?php echo $item['title']; ?></strong></a></div>
            
            <?php if ($item['viewtype'] == 'shop') { ?>
                <div style="margin-bottom:10px" align="center" id="uc_random_price"><?php echo $item['price']; ?> <?php echo $_LANG['CURRENCY']; ?></div>
            <?php } ?>
        <?php } ?>
            
        <?php if ($cfg['showcat']) { ?>
                <div align="center" id="uc_random_cat"><?php echo $_LANG['UC_RANDOM_RUBRIC']; ?>: <a href="/catalog/<?php echo $item['category_id']; ?>"><?php echo $item['category']; ?></a></div>
        <?php } ?>

    <?php } ?>
<?php } else { ?>
	<p><?php echo $_LANG['UC_RANDOM_NO_ITEMS']; ?></p>
<?php } ?>