<?php if ($is_img) { ?>
    <p align="center"><a href="/photos/photo<?php echo $item['id']; ?>.html"><img src="/images/photos/small/<?php echo $item['file']; ?>" border="0" /></a></p>
    <?php if ($cfg['showtitle']) { ?>
    <p align="center"><a href="/photos/photo<?php echo $item['id']; ?>.html"><?php echo $item['title']; ?></a></p>
    <?php } ?>
<?php } ?>