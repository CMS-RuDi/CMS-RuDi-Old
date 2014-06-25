<?php if ($cat['view_type'] == 'shop' || $item['can_edit']) { ?>
<div id="shop_toollink_div">
    <?php if ($cat['view_type'] == 'shop') { ?>
        <?php echo $shopCartLink; ?>
    <?php } ?>
    <?php if ($item['can_edit']) { ?>
        <a href="/catalog/edit<?php echo $item['id']; ?>.html" class="uc_item_edit_link"><?php echo $_LANG['EDIT']; ?></a>
    <?php } ?>
</div>
<?php } ?>

<div class="con_heading"><?php echo $item['title']; ?></div>

<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:10px">
    <tr>
	<td align="left" valign="top" width="10" class="uc_detailimg">
            <div>
		<?php if (mb_strlen($item['imageurl']) > 4) { ?>
                    <a class="lightbox-enabled" title="<?php echo $this->escape($item['title']); ?>" rel="lightbox" href="/images/catalog/<?php echo $item['imageurl']; ?>" target="_blank">
                        <img alt="<?php echo $this->escape($item['title']); ?>" src="/images/catalog/medium/<?php echo $item['imageurl']; ?>" />
                    </a>
                <?php } else { ?>
                    <img src="/images/catalog/medium/nopic.jpg" border="0" />
                <?php } ?>
            </div>
        </td>
        
        <td class="uc_list_itemdesc" align="left" valign="top" class="uc_detaildesc">
            <ul class="uc_detaillist">
        	<li class="uc_detailfield"><strong><?php echo $_LANG['ADDED_BY']; ?>: </strong> <?php echo $getProfileLink; ?></li>
                <?php foreach($fields as $value) { ?>
                    <?php if ($value) { ?>
                        <li class="uc_detailfield">
                        <?php if (!mb_strstr($field, '/~l~/')) { ?>
                            <strong><?php echo $field; ?>: </strong>
                        <?php } ?>
                            <?php echo $value; ?>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
            
            <?php if ($cat['view_type'] == 'shop') { ?>
                <div id="shop_price">
                    <span><?php echo $_LANG['PRICE']; ?>:</span> <?php echo $item['price']; ?> <?php echo $_LANG['CURRENCY']; ?>
                </div>
                <div id="shop_ac_itemdiv">
                    <a href="/catalog/addcart<?php echo $item['id']; ?>.html" title="<?php echo $_LANG['ADD_TO_CART']; ?>" id="shop_ac_item_link">
                        <img src="/components/catalog/images/shop/addcart.jpg" alt="<?php echo $_LANG['ADD_TO_CART']; ?>"/>
                    </a>
                </div>
            <?php } ?>
                
            <?php if ($item['on_moderate']) { ?>
                <div id="shop_moder_form">
                    <p class="notice"><?php echo $_LANG['WAIT_MODERATION']; ?>:</p>
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <form action="/catalog/moderation/accept<?php echo $item['id']; ?>.html" method="POST">
                                    <input type="submit" name="accept" value="<?php echo $_LANG['MODERATION_ACCEPT']; ?>"/>
                                </form>
                            </td>
                            <td>
                                <form action="/catalog/edit<?php echo $item['id']; ?>.html" method="POST">
                                    <input type="submit" name="accept" value="<?php echo $_LANG['EDIT']; ?>"/>
                                </form>
                            </td>
                            <td>
                                <form action="/catalog/moderation/reject<?php echo $item['id']; ?>.html" method="POST">
                                    <input type="submit" name="accept" value="<?php echo $_LANG['MODERATION_REJECT']; ?>"/>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php } ?>
        </td>
    </tr>
</table>

<?php if ($cat['showtags'] && $tagline) { ?>
    <div class="uc_detailtags"><strong><?php echo $_LANG['TAGS']; ?>: </strong><?php echo $tagline; ?></div>
<?php } ?>

<?php if ($cat['is_ratings']) { ?>
    <?php echo $ratingForm; ?>
<?php } ?>