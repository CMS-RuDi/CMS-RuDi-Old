<div>
<?php foreach ($tags as $tag) { ?>
    <a class="tag" title="<?php echo $this->spellcount($tag['num'], $_LANG['TAG_ITEM1'], $_LANG['TAG_ITEM2'], $_LANG['TAG_ITEM10']); ?>" href="/search/tag/<?php echo urlencode($tag['tag']); ?>" style="<?php if ($cfg['colors']) { echo 'color: '. $this->cycle($cfg['colors']) .';'; ?><?php } if ($tag['fontsize']) { echo 'font-size: '. $tag['fontsize'] .'px;'; } ?>"><?php echo icms_ucfirst($tag['tag']); ?></a>
<?php } ?>
</div>