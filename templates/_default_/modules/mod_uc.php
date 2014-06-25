<?php if ($cfg['showtype'] == 'thumb') { ?>
    <?php foreach($items as $item) { ?>
        <div class="uc_latest_item">
            <table border="0" cellspacing="2" cellpadding="0" width="100%">
                <tr>
                    <td height="110" align="center" valign="middle">
                        <a href="/catalog/item<?php echo $item['id']; ?>.html">
                            <img alt="<?php echo $this->escape($item['title']); ?>" src="/images/catalog/small/<?php echo $item['imageurl']; ?>" border="0" />
                        </a>
                    </td>
                </tr>
                
                <tr>
                    <td align="center" valign="middle">
                        <a class="uc_latest_link" href="/catalog/item<?php echo $item['id']; ?>.html"><?php echo $this->truncate($item['title'], 30); ?></a>
                    </td>
                </tr>

                <?php if ($item['viewtype'] == 'shop') { ?>
                    <tr>
                        <td align="center" valign="middle">
                            <div id="uc_popular_price"><?php echo $item['price']; ?> <?php echo $_LANG['CURRENCY']; ?></div>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    <?php } ?>
    <div class="blog_desc"></div>
<?php } ?>

<?php if ($cfg['v'] == 'list') { ?>
    <table width="100%" cellspacing="0" cellpadding="4" class="uc_latest_list">
        <?php foreach($items as $item) { ?>
            <tr>
                <td width="" valign="top">
                    <a class="uc_latest_link" href="/catalog/item<?php echo $item['id']; ?>.html"><?php echo $this->truncate($item['title'], 30); ?></a>
                </td>
                <?php for ($i=0;$i<$cfg['showf'];$i++) { ?>
                    <td valign="top"><?php echo $item['fdata'][$i]; ?></td>
                <?php } ?>

                <td width="100" align="right" valign="top"><?php echo $item['key']; ?></td>

                <td align="right" width="65">
                    <?php if ($item['viewtype'] == 'shop') { ?>
                        <div id="uc_popular_price"><?php echo $item['price']; ?> <?php echo $_LANG['CURRENCY']; ?></div>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>

<?php if ($cfg['fulllink']) { ?>
    <div style="margin-top:5px; text-align:right; clear:both"><a style="text-decoration:underline" href="/catalog"><?php echo $_LANG['UC_MODULE_CATALOG']; ?></a> <?php echo $_LANG['UC_MODULE_ARR']; ?></div>
<?php } ?>