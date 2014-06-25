<?php if ($cfg['is_pag']) { ?>
    <script type="text/javascript">
        function conPage(page, module_id){
            $('div#module_ajax_'+module_id).css({opacity:0.4, filter:'alpha(opacity=40)'});
            $.post('/modules/mod_latest/ajax/latest.php', {'module_id': module_id, 'page':page}, function(data){
                $('div#module_ajax_'+module_id).html(data);
                $('div#module_ajax_'+module_id).css({opacity:1.0, filter:'alpha(opacity=100)'});
            });
        }
    </script>
<?php } ?>
<?php if (!$is_ajax) { ?><div id="module_ajax_<?php echo $module_id; ?>"><?php } ?>

<?php foreach($articles as $article) { ?>
    <div class="mod_latest_entry">
        <?php if ($article['image_small']) { ?>
            <div class="mod_latest_image">
                <img src="<?php echo $article['image_small']; ?>" border="0" width="32" height="32" alt="<?php echo $this->escape($article['title']); ?>"/>
            </div>
        <?php } ?>
        <a class="mod_latest_title" href="<?php echo $article['url']; ?>"><?php echo $article['title']; ?></a>
        <?php if ($cfg['showdate']) { ?>
            <div class="mod_latest_date">
                <?php echo $article['fpubdate']; ?> - <a href="<?php echo cmsUser::getProfileURL($article['user_login']); ?>"><?php echo $article['author']; ?></a><?php if ($cfg['showcom']) { ?> - <a href="<?php echo $article['url']; ?>" title="<?php echo $this->spellcount($article['comments'], $_LANG['COMMENT1'], $_LANG['COMMENT2'], $_LANG['COMMENT10']); ?>" class="mod_latest_comments"><?php echo $article['comments']; ?></a><?php } ?> - <span class="mod_latest_hits"><?php echo $article['hits']; ?></span>
            </div>
        <?php } ?>
        <?php if ($cfg['showdesc']) { ?>
            <div class="mod_latest_desc" style="overflow:hidden">
                <?php echo $this->truncate($this->strip_tags($article['description']),200); ?>
            </div>
        <?php } ?>
	</div>
<?php } ?>
<?php if ($cfg['showrss']) { ?>
    <div class="mod_latest_rss">
        <a href="/rss/content/<?php if ($cfg['cat_id']) { ?><?php echo $cfg['cat_id']; ?><?php } else { ?>all<?php } ?>/feed.rss"><?php echo $_LANG['LATEST_RSS']; ?></a>
    </div>
<?php } ?>
<?php if ($cfg['is_pag'] && $pagebar_module) { ?>
    <div class="mod_latest_pagebar"><?php echo $pagebar_module; ?></div>
<?php } ?>
<?php if (!$is_ajax) { ?></div><?php } ?>