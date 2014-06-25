<?php if ($items_count) { ?>
    <?php if ($cfg['showtype'] == 'list') { ?>
        <?php foreach ($items as $item) { ?>
            <div class="<?php echo $this->cycle('cartrow1,cartrow2'); ?>">
                <div class="cart_item">
                    <a href="/catalog/item<?php echo $item['id']; ?>.html"><?php echo $this->truncate($item['title'], 30); ?></a>
                </div>
                <div class="cart_price">
                    <?php if ($item['itemscount'] == 1) { echo $item['totalcost']; } else { echo $item['itemscount'] .' x '. $item['price'] .' = '. $item['totalcost']; } ?>
                    <?php echo $_LANG['CURRENCY']; ?>
                </div>
            </div>
        <?php } ?>
        <div align="right" class="cart_total">
            <a href="/catalog/viewcart.html" title="<?php echo $_LANG['CART_GOTO_CART']; ?>"><strong><?php echo $_LANG['CART_SUMM']; ?>:</strong> <?php echo $total_summ; ?> <?php echo $_LANG['CURRENCY']; ?>.</a>
        </div>
    <?php } else { ?>
        <div class="cart_count">
            <strong><?php echo $_LANG['CART_ITEMS']; ?>:</strong> <a href="/catalog/viewcart.html" title="<?php echo $_LANG['CART_GOTO_CART']; ?>"><?php echo $items_count; ?> <?php echo $_LANG['CART_QTY']; ?></a>
        </div>
        <?php if ($cfg['showtype'] == 'qtyprice') { ?>
            <div class="cart_total"><strong><?php echo $_LANG['CART_TOTAL']; ?>:</strong> <?php echo $total_summ; ?> <?php echo $_LANG['CURRENCY']; ?>.</div>
        <?php } ?>
    <?php } ?>
<?php } else { ?>
    <p style="clear:both"><strong><?php echo $_LANG['CART_NOT_ITEMS']; ?></strong></p>
<?php } ?>