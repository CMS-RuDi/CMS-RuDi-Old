<?php foreach ($posts as $post) { ?>
    <div class="mod_latest_entry">
        <div class="mod_latest_image">
            <a href="<?php echo cmsUser::getProfileURL($post['login']); ?>" title="<?php echo $this->escape($post['author']); ?>"><img border="0" class="usr_img_small" src="<?php echo $post['author_avatar']; ?>" /></a>
            <?php if (!$post['fileurl']) { ?>
                <a href="<?php echo cmsUser::getProfileURL($post['login']); ?>" title="<?php echo $this->escape($post['author']); ?>">
                    <img class="usr_img_small img_64" src="<?php echo $post['author_avatar']; ?>" alt="<?php echo $this->escape($post['author']); ?>" />
                </a>
            <?php } else { ?>
                <a href="<?php echo $post['url']; ?>">
                    <img class="usr_img_small img_64" src="<?php echo $post['fileurl']; ?>" alt="<?php echo $this->escape($post['title']); ?>" />
                </a>
            <?php } ?>
        </div>

        <a class="mod_latest_blog_title" href="<?php echo $post['url']; ?>" title="<?php echo $this->escape($post['title']); ?>"><?php echo $this->truncate($post['title'], 70); ?></a>

        <div class="mod_latest_date">
            <?php echo $post['fpubdate']; ?> - <a href="<?php echo $post['blog_url']; ?>"><?php echo $post['blog_title']; ?></a> - <a href="<?php echo $post['url']; ?>#c" title="<?php echo $this->spellcount($post['comments_count'], $_LANG['COMMENT1'], $_LANG['COMMENT2'], $_LANG['COMMENT10']); ?>" class="mod_latest_comments"><?php echo $post['comments_count']; ?></a> - <span class="mod_latest_rating"><?php echo $this->rating($post['rating']); ?></span>
        </div>
    </div>
<?php } ?>

<?php if ($cfg['showrss']) { ?>
    <div class="mod_latest_rss">
        <a href="/rss/blogs/all/feed.rss"><?php echo $_LANG['RSS']; ?></a>
    </div>
<?php } ?>