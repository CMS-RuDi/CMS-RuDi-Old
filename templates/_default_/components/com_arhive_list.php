<div class="con_heading"><?php echo $pagetitle; ?></div>

<?php if ($items) { ?>
    <?php foreach($items as $item) { ?>
        <div class="arhive_date"><a href="/arhive/<?php echo $item['year']; ?>/<?php echo $item['month']; ?>/<?php echo $item['day']; ?>"><?php echo $item['fpubdate']; ?></a></div>
        <h2 class="arhive_title"><a href="<?php echo $item['url']; ?>"><?php echo $item['title']; ?></a> &rarr; <a href="<?php echo $item['category_url']; ?>"><?php echo $item['cat_title']; ?></a></h2>
        <div class="arhive_desc">
        <?php if ($item['showdesc'] && $item['description']) { ?>
            <?php if ($item['image']) { ?>
                <div class="con_image">
                    <img src="/images/photos/small/<?php echo $item['image']; ?>" border="0" alt="<?php echo $this->escape($item['title']); ?>" />
                </div>
            <?php } ?>
            <?php echo $item['description']; ?>
        <?php } ?>
        </div>
    <?php } ?>
<?php } else { ?>
    <p><?php echo $_LANG['ARHIVE_NO_MATERIALS']; ?></p>
<?php } ?>