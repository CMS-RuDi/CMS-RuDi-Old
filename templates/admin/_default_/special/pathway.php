<div class="pathway">
    <ol class="breadcrumb">
        <?php foreach($pathway as $key => $path){ ?>
            <li<?php if ($key == $count-1) { ?> class="active"<?php } ?>>
                <a href="<?php echo $path['link']; ?>" title="<?php echo $this->escape($path['title']); ?>"><?php echo $path['title']; ?></a>
            </li>
        <?php } ?>
    </ol>
</div>