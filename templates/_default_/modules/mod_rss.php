<table width="100%" cellpadding="4" cellspacing="0">
<?php $col = 1; ?>
<?php foreach ($rs['items'] as $item) { ?>
<?php if ($col == 1) { echo '<tr>'; } ?>
    <?php if ($cfg['showicon']) { ?>
        <td width="16" valign="top">
            <img src="/images/icons/rssitem.gif" />
        </td>
    <?php } ?>
    <td valign="top">
        <div><a target="_blank" href="<?php echo $item['link']; ?>"><?php echo $item['title']; ?></a></div>
        <?php if ($cfg['showdesc']) { ?>
            <div><?php echo $item['description']; ?></div>
        <?php } ?>
    </td>
<?php if ($col == $cfg['cols']) { echo '</tr>'; $col=1; } else { $col++; } ?>
<?php } ?>
</table>