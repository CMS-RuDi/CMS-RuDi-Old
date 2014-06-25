<?php if ($category['is_can_add'] || $root_id == $category['id']) { ?>
<div class="float_bar">
    <table cellpadding="2" cellspacing="0">
        <tr>
            <td><img src="/components/board/images/add.gif" border="0"/></td>
            <td><a href="/board/<?php if ($root_id != $category['id']) { ?><?php echo $category['id']; ?>/<?php } ?>add.html"><?php echo $_LANG['ADD_ADV']; ?></a></td>
        </tr>
    </table>
</div>
<?php } ?>

<h1 class="con_heading"><?php echo $pagetitle; ?> <a href="/rss/board/<?php if ($root_id == $category['id']) { ?>all<?php } else { ?><?php echo $category['id']; ?><?php } ?>/feed.rss" title="<?php echo $_LANG['RSS']; ?>"><img src="/images/markers/rssfeed.png" border="0" alt="<?php echo $_LANG['RSS']; ?>"/></a></h1>

<?php if ($cats) { ?>
    <table class="board_categorylist" cellspacing="3" width="100%" border="0">
        <?php $col = 1; ?>
        <?php foreach($cats as $cat) { ?>
            <?php if ($col == 1) { ?> <tr> <?php } ?>
                <td width="30" valign="top">
                    <img class="bd_cat_main_icon" src="/upload/board/cat_icons/<?php echo $cat['icon']; ?>" border="0" />
                </td>
                <td valign="top" class="bd_cat_cell">
                    <div class="bd_cat_main_title"><a href="/board/<?php echo $cat['id']; ?>"><?php echo $cat['title']; ?></a> (<?php echo $cat['content_count']; ?>)</div>
                    <?php if ($cat['description']) { ?>
                        <div class="bd_cat_main_desc"><?php echo $cat['description']; ?></div>
                    <?php } ?>
                    <div class="bd_cat_main_obtypes"><?php echo $cat['ob_links']; ?></div>
                </td>
            <?php if ($col == $maxcols) { $col = 1; echo '</tr>'; } else { $col++; } ?>
        <?php } ?>

        <?php if ($col > 1) { ?>
            <td colspan="<?php echo (($maxcols + 1) - $col); ?>">&nbsp;</td></tr>
        <?php } ?>
    </table>
<?php } ?>
<?php if ($category['description']) { ?>
    <p class="usr_photos_notice"><?php echo $category['description']; ?></p>
<?php } ?>