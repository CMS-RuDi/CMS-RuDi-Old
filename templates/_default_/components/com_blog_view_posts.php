<div class="con_heading"><?php echo $pagetitle; ?></div>

<div class="blog_type_menu">
    <?php if (!$ownertype) { ?>
        <span class="blog_type_active"><?php echo $_LANG['POSTS_RSS']; ?> (<?php echo $total; ?>)</span>
    <?php } else { ?>
        <a class="blog_type_link" href="/blogs"><?php echo $_LANG['POSTS_RSS']; ?></a>
    <?php } ?>

    <?php if ($ownertype == 'all') { ?>
        <span class="blog_type_active"><?php echo $_LANG['ALL_BLOGS']; ?></span>
    <?php } else { ?>
        <a class="blog_type_link" href="/blogs/all.html"><?php echo $_LANG['ALL_BLOGS']; ?></a>
    <?php } ?>

    <?php if ($ownertype == 'single') { ?>
        <span class="blog_type_active"><?php echo $_LANG['PERSONALS']; ?></span>
    <?php } else { ?>
        <a class="blog_type_link" href="/blogs/single.html"><?php echo $_LANG['PERSONALS']; ?></a>
    <?php } ?>

    <?php if ($ownertype == 'multi') { ?>
        <span class="blog_type_active"><?php echo $_LANG['COLLECTIVES']; ?></span>
    <?php } else { ?>
        <a class="blog_type_link" href="/blogs/multi.html"><?php echo $_LANG['COLLECTIVES']; ?></a>
    <?php } ?>
</div>

<?php if ($posts) { ?>
    <div class="blog_entries">
        <?php foreach($posts as $post) { ?>
        <div class="blog_entry">
            <table width="100%" cellspacing="0" cellpadding="0" class="blog_records">
                <tr>
                    <td width="" class="blog_entry_title_td">
                        <div class="blog_entry_title">
                            <?php if ($post['blog_url']) { ?>
                            <a href="<?php echo $post['blog_url']; ?>" style="color:gray"><?php echo $post['blog_title']; ?></a> &rarr;
                            <?php } ?>
                            <a href="<?php echo $post['url']; ?>"><?php echo $post['title']; ?></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="blog_entry_text"><?php echo $post['content_html']; ?></div>
                        <div class="blog_comments">
                            <a class="blog_user" href="<?php echo cmsUser::getProfileURL($post['login']); ?>"><?php echo $post['author']; ?></a>
                            <span class="blog_entry_date"><?php if (!$post['published']) { ?><span style="color:#CC0000"><?php echo $_LANG['ON_MODERATE']; ?></span><?php } else { ?><?php echo $post['fpubdate']; ?><?php } ?></span>
                            <span class="post_karma"><?php echo $this->rating($post['rating']); ?></span>
                            <?php if ($post['comments_count'] > 0) { ?>
                            <a class="blog_comments_link" href="<?php echo $post['url']; ?>#c"><?php echo $this->spellcount($post['comments_count'], $_LANG['COMMENT'], $_LANG['COMMENT2'], $_LANG['COMMENT10']); ?></a>
                            <?php } else { ?>
                            <a class="blog_comments_link" href="<?php echo $post['url']; ?>#c"><?php echo $_LANG['NOT_COMMENTS']; ?></a>
                            <?php } ?>
                            <?php if ($post['tagline'] != false) { ?>
                            <span class="tagline"><?php echo $post['tagline']; ?></span>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <?php } ?>
    </div>

    <?php echo $pagination; ?>
<?php } else { ?>
    <p style="clear:both"><?php echo $_LANG['NOT_POSTS']; ?></p>
<?php } ?>