<div class="pathway">
    <?php foreach($pathway as $key => $path){ ?>
        <?php if(!isset($path['is_last'])){ ?>
            <a href="<?php echo $path['link']; ?>" class="pathwaylink"><?php echo $path['title']; ?></a>
        <?php } else { ?>
            <span class="pathwaylink"><?php echo $path['title']; ?></span>
        <?php } ?>

        <?php if ($key < $count-1) { echo ' ',$separator,' '; } ?>
    <?php } ?>
</div>