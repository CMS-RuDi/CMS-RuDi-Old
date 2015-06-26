<?php if (!empty($items)) { ?>
    <?php foreach ($items as $item) { ?>
        <a style="width:20px;height:20px;display:block; float:left; padding:2px" href="javascript:selectIcon('<?php echo $item['file']; ?>')"><img alt="<?php echo $item['file']; ?>" src="<?php echo $item['src']; ?>" border="0" /></a>
    <?php } ?>
<?php } else { ?>
    <p><?php echo $_LANG['AD_EMPTY_FOLDER']; ?></p>
<?php } ?>

<div align="right" style="clear:both">[<a href="javascript:selectIcon('')"><?php echo $_LANG['AD_NO_ICON']; ?></a>] [<a href="javascript:hideIcons()"><?php echo $_LANG['CLOSE']; ?></a>]</div>