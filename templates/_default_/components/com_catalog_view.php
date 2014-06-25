<div id="shop_toollink_div">
    <a id="shop_searchlink" href="/catalog/<?php echo $cat['id']; ?>/search.html"><?php echo $_LANG['SEARCH_BY_CAT']; ?></a>
    <?php if ($cat['view_type'] == 'shop') { ?> <?php echo $shopcartlink; ?>	<?php } ?>
    <?php if ($is_can_add) { ?>
        <a id="shop_addlink" href="/catalog/<?php echo $cat['id']; ?>/add.html"><?php echo $_LANG['ADD_ITEM']; ?></a>
    <?php } ?>
</div>

<?php if ($cfg['is_rss']) { ?>
    <h1 class="con_heading"><?php echo $cat['title']; ?> <a href="/rss/catalog/<?php echo $cat['id']; ?>/feed.rss" title="<?php echo $_LANG['RSS']; ?>"><img src="/images/markers/rssfeed.png" border="0" alt="<?php echo $_LANG['RSS']; ?>"/></a></h1>
<?php } else { ?>
    <h1 class="con_heading"><?php echo $cat['title']; ?></h1>
<?php } ?>

<?php if ($cat['description']) { ?>
	<div class="con_description"><?php echo $cat['description']; ?></div>
<?php } ?>

<?php if ($subcats) { ?>
	<div class="uc_subcats"><?php echo $subcats; ?></div>
<?php } ?>

<?php if ($alphabet) { ?> <?php echo $alphabet; ?> <?php } ?>

<?php if ($cat['showsort']) { ?> <?php echo $orderform; ?> <?php } ?>

<?php if ($itemscount > 0) { ?>
    <?php if ($search_details) { ?> <?php echo $search_details; ?> <?php } ?>
    <?php foreach($items as $item) { ?>
        <?php if ($cat['view_type'] == 'list' || $cat.view_type=='shop') { ?>
            <div class="catalog_list_item">
                <table border="0" cellspacing="2" cellpadding="0" id="catalog_item_table">
                    <tr>
                        <td valign="top" align="center" id="catalog_list_itempic" width="110">
                            <?php if ($item['imageurl']) { ?>
                            <a class="lightbox-enabled" title="<?php echo $this->escape($item['title']); ?>" rel="lightbox" href="/images/catalog/<?php echo $item['imageurl']; ?>">
                                <img alt="<?php echo $this->escape($item['title']); ?>" src="/images/catalog/small/<?php echo $item['imageurl']; ?>" />
                            </a>
                            <?php } else { ?>
                            <a href="/catalog/item<?php echo $item['id']; ?>.html">
                                <img alt="<?php echo $this->escape($item['title']); ?>" src="/images/catalog/small/nopic.jpg" />
                            </a>
                            <?php } ?>
                            <?php if ($cat['view_type'] == 'shop') { ?>
                            <div id="shop_small_price">
                                <span><?php echo $item['price']; ?></span> <?php echo $_LANG['CURRENCY']; ?>
                            </div>
                            <?php } ?>
                        </td>
                        <td class="uc_list_itemdesc" align="left" valign="top">
                            <?php if ($item['can_edit']) { ?>
                            <div class="uc_item_edit">
                                <a href="/catalog/edit<?php echo $item['id']; ?>.html" class="uc_item_edit_link"><?php echo $_LANG['EDIT']; ?></a>
                            </div>
                            <?php } ?>
                            <div>
                                <a class="uc_itemlink" href="/catalog/item<?php echo $item['id']; ?>.html"><?php echo $item['title']; ?></a>
                                    <?php if ($item['is_new']) { ?>
                                        <span class="uc_new"><img src="/images/ratings/new.gif" /></span>
                                    <?php } ?>
                            </div>
                            <?php if ($cat['is_ratings']) { ?>
                                <div class="uc_rating"><?php echo $item['rating']; ?></div>
                            <?php } ?>
                            <div class="uc_itemfieldlist">
                                <?php foreach($item['fields'] as $value) { ?>
                                    <?php if ($value) { ?>
                                        <?php if (!mb_strstr($field, '/~l~/')) { ?>
                                            <div class="uc_itemfield"><strong><?php echo $field; ?></strong>: <?php echo $value; ?></div>
                                        <?php } else { ?>
                                            <?php echo $value; ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                            <?php if ($item['tagline'] && $cat['showtags']) { ?>
                                <div class="uc_tagline"><strong><?php echo $_LANG['TAGS']; ?>:</strong> <?php echo $item['tagline']; ?></div>
                            <?php } ?>
                            <?php if ($cat['view_type'] == 'list') { ?>
                                <?php if ($cat['showmore']) { ?>
                                <a href="/catalog/item<?php echo $item['id']; ?>.html"><?php echo $_LANG['DETAILS']; ?>...</a>
                                <?php } ?>
                            <?php } else { ?>
                                <div id="shop_list_buttons">
                                    <a href="/catalog/item<?php echo $item['id']; ?>.html" title="<?php echo $_LANG['DETAILS']; ?>">
                                        <img src="/components/catalog/images/shop/more.jpg" alt="<?php echo $_LANG['DETAILS']; ?>"/>
                                    </a>
                                    <a href="/catalog/addcart<?php echo $item['id']; ?>.html" title="<?php echo $_LANG['ADD_TO_CART']; ?>">
                                        <img src="/components/catalog/images/shop/addcart.jpg" alt="<?php echo $_LANG['ADD_TO_CART']; ?>"/>
                                    </a>
                                </div>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </div>
        <?php } ?>

        <?php if ($cat['view_type'] == 'thumb') { ?>
            <div class="uc_thumb_item">
                <table border="0" cellspacing="2" cellpadding="0" width="100%">
                    <tr>
                        <td height="110" align="center" valign="middle">
                            <a href="/catalog/item<?php echo $item['id']; ?>.html">
                                <?php if ($item['imageurl']) { ?>
                                    <img alt="<?php echo $this->escape($item['title']); ?>" src="/images/catalog/small/<?php echo $item['imageurl']; ?>" />
                                <?php } else { ?>
                                    <img alt="<?php echo $this->escape($item['title']); ?>" src="/images/catalog/small/nopic.jpg" />
                                <?php } ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="middle">
                            <a class="uc_thumb_itemlink" href="/catalog/item<?php echo $item['id']; ?>.html"><?php echo $item['title']; ?></a>
                        </td>
                    </tr>
                </table>
            </div>
        <?php } ?>
    <?php } ?>

    <?php echo $pagebar; ?>
<?php } ?>