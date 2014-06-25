<ul class="uc_cat_list">
    <?php foreach($cats as $cat) { ?>
        <li class="uc_cat_item"><a href="/catalog/<?php echo $cat['id']; ?>"><?php echo $cat['title']; ?></a> (<?php echo $cat['content_count']; ?>)</li>
    <?php } ?>
</ul>