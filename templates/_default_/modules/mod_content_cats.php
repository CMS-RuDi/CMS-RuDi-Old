<?php if ($subcats_list) { ?>
<ul class="mod_cat_list">
    <?php $last_level = 1; ?>
    <?php foreach ($subcats_list as $cat) { ?>
        <?php
            if ($cat['NSLevel'] == $last_level) { echo '</li>'; }
            
            $tail = $last_level - $cat['NSLevel'];
            
            for ($i=0; $i<$tail; $i++){
                echo '</li></ul>';
            }
            
            if ($cat['NSLevel'] <= 1) {
                echo '<li>';
            }
            
            if ($cat['NSLevel'] <= 1) {
        ?>
            <a class="folder" href="<?php echo $cat['url']; ?>"><?php if ($cat['seolink'] == $current_seolink) { echo '<strong>'. $cat['title'] .' ('. $cat['content_count'] .')</strong>'; } else { echo $cat['title'] .' ('. $cat['content_count'] .')'; } ?></a>
        <?php } else { ?>
            <?php if ($cat['NSLevel'] > $last_level) { ?>
                <a href="javascript:" class="cat_plus" style="<?php if ($cfg['expand_all']){ echo 'display:none'; } ?>" title="<?php echo $_LANG['EXPAND']; ?>"></a>
                <a href="javascript:" class="cat_minus" style="<?php if ($cfg['expand_all']){ echo 'display:none'; } ?>" title="<?php echo $_LANG['TURN']; ?>"></a>
            	<ul>
            <?php } ?>
                <li>
                    <a class="folder" href="<?php echo $cat['url']; ?>"><?php if ($cat['seolink'] == $current_seolink) { echo '<strong>'. $cat['title'] .' ('. $cat['content_count'] .')</strong>'; } else { echo $cat['title'] .' ('. $cat['content_count'] .')'; } ?></a>
        <?php }
            $last_level = $cat['NSLevel'];
        ?>
    <?php }
        for ($i=0; $i<$last_level; $i++){
            echo '</li></ul>';
        }
    ?>
</ul>

<script type="text/javascript">    
    <?php if ($cfg['expand_all']) { ?>
    $('ul.mod_cat_list li > ul').hide();
    <?php } ?>
        
    $('.cat_plus').click(function(){
        $(this).fadeOut();
        $(this).parent('li').find('.cat_minus').eq(0).show();
        $(this).parent('li').find('ul').eq(0).fadeIn();
    });

    $('.cat_minus').click(function(){
        $(this).fadeOut();
        $(this).parent('li').find('.cat_plus').eq(0).show();
        $(this).parent('li').find('ul').hide();
        $(this).parent('li').find('ul').find('.cat_minus').hide();
        $(this).parent('li').find('ul').find('.cat_plus').show();
    });
</script>
<?php } ?>