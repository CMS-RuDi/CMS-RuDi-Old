<?php if ($cfg['is_rss']) { ?>
<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td><h1 class="con_heading"><?php echo $title; ?></h1></td>
        <td valign="top" style="padding-left:6px">
            <div class="con_rss_icon">
                <a href="/rss/catalog/all/feed.rss" title="<?php echo $_LANG['RSS']; ?>"><img src="/images/markers/rssfeed.png" border="0" alt="<?php echo $_LANG['RSS']; ?>"/></a>
            </div>
        </td>
    </tr>
</table>
<?php } else { ?>
	<h1 class="con_heading"><?php echo $title; ?></h1>
<?php } ?>

<?php if ($cats_html) { ?>
    <?php echo $cats_html; ?>
<?php } else { ?>
    <?php echo $_LANG['NO_CAT_IN_CATALOG']; ?>
<?php } ?>