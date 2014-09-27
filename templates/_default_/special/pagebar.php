<div class="pagebar">
    <span class="pagebar_title"><strong><?php echo $_LANG['PAGES']; ?>: </strong></span>
    
    <?php if ($page > 1) { ?>
        <a href="<?php echo str_replace('%page%', 1, $href); ?>" class="pagebar_page"><?php echo $_LANG['FIRST']; ?></a>
        <a href="<?php echo str_replace('%page%', $page-1, $href); ?>" class="pagebar_page"><?php echo $_LANG['PREVIOUS']; ?></a>
    <?php } ?>
    
        <?php for ($i=$current; $i<$max_links-1; $i++) { ?>
            <?php if ($i == $page) { ?>
                <span class="pagebar_current"><?php echo $i; ?></span>
            <?php } else { ?>
                <a href="<?php echo str_replace('%page%', $i, $href); ?>" class="pagebar_page"><?php echo $i; ?></a>
            <?php } ?>
        <?php } ?>
    
    <?php if ($page >= 1 && $page != $total_pages) { ?>
        <a href="<?php echo str_replace('%page%', $page+1, $href); ?>" class="pagebar_page"><?php echo $_LANG['NEXT']; ?></a>
        <a href="<?php echo str_replace('%page%', $total_pages, $href); ?>" class="pagebar_page"><?php $_LANG['LAST']; ?></a>
    <?php } ?>
</div>