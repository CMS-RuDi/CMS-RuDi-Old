<div class="con_rss_icon">
    <span class="blog_entry_date"><?php echo $blog['pubdate']; ?></span>
    <span class="post_karma"><?php echo $this->rating($blog['rating']); ?></span>
    <a href="/rss/<?php echo cmsCore::getInstance()->component; ?>/<?php echo $blog['id']; ?>/feed.rss" title="<?php echo $_LANG['RSS']; ?>">
        <?php echo $_LANG['RSS']; ?> <img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/rss.png" border="0" alt="<?php echo $_LANG['RSS']; ?>"/>
    </a>
</div>
<h1 class="con_heading"><?php echo $blog['title']; ?></h1>

<?php if (!$myblog) { ?>
    <?php if ($blog['ownertype'] == 'single') { ?>
        <table cellspacing="0" cellpadding="5" class="blog_desc">
            <tr>
                <td width=""><strong><?php echo $_LANG['BLOG_AVTOR']; ?>:</strong></td>
                <td width=""><?php echo $blog['author']; ?></td>
            </tr>
        </table>
<?php } else { ?>
        <table cellspacing="0" cellpadding="2" class="blog_desc">
            <tr>
                <td width=""><strong><?php echo $_LANG['BLOG_ADMIN']; ?>:</strong></td>
                <td width=""><?php echo $blog['author']; ?></td>
                <?php if ($blog['forall']) { ?>
                <td width=""><span class="blog_authorsall">(<?php echo $_LANG['BLOG_OPENED_FOR_ALL']; ?>)</span></td>
                <?php } ?>
            </tr>
        </table>
    <?php } ?>
<?php } ?>

<?php if ($myblog || $is_writer || $is_admin) { ?>
    <div class="blog_toolbar">
	<?php if ($myblog || $is_admin) { ?>
            <table cellspacing="0" cellpadding="2">
                <tr>
                    <?php if ($on_moderate) { ?>
                    <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/folder_table.png" border="0"/></td>
                    <td width=""><a class="blog_moderate_link" href="<?php echo $blog['moderate_link']; ?>"><?php echo $_LANG['MODERATING']; ?> (<?php echo $on_moderate; ?>)</a></td>
                    <?php } ?>
                    <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/edit.png" border="0"/></td>
                    <td width=""><a href="<?php echo $blog['add_post_link']; ?>"><?php echo $_LANG['NEW_POST']; ?></a></td>
                    <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/addcat.png" border="0"/></td>
                    <td width=""><a class="ajaxlink" href="javascript:void(0)" onclick="$('#opt_cat').toggle();"><?php echo $_LANG['CATS']; ?></a></td>
                <?php if ($is_config) { ?>
                    <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/settings.png" border="0"/></td>
                    <td width=""><a class="ajaxlink" href="javascript:void(0)" onclick="<?php echo cmsCore::getInstance()->component; ?>.editBlog(<?php echo $blog['id']; ?>);return false;"><?php echo $_LANG['CONFIG']; ?></a></td>
                <?php } ?>
                </tr>
            </table>
        
            <table cellspacing="0" cellpadding="5" id="opt_cat" style="display:none; background-color:#E0EAEF;position: absolute;right: 54px;top: 32px;">
                <tr>
                    <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/addcat.png" border="0"/></td>
                    <td width=""><a class="ajaxlink" href="javascript:void(0)" onclick="<?php echo cmsCore::getInstance()->component; ?>.addBlogCat(<?php echo $blog['id']; ?>);return false;"><?php echo $_LANG['NEW_CAT']; ?></a></td>
                </tr>
                <?php if ($cat_id > 0) { ?>
                    <tr>
                        <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/editcat.png" border="0"/></td>
                        <td width=""><a class="ajaxlink" href="javascript:void(0)" onclick="<?php echo cmsCore::getInstance()->component; ?>.editBlogCat(<?php echo $cat_id; ?>);return false;"><?php echo $_LANG['RENAME_CAT']; ?></a></td>
                    </tr>
                    <tr>
                        <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/deletecat.png" border="0"/></td>
                        <td width=""><a class="ajaxlink" href="javascript:void(0)" onclick="<?php echo cmsCore::getInstance()->component; ?>.deleteCat(<?php echo $cat_id; ?>, '<?php echo cmsUser::getCsrfToken(); ?>');return false;"><?php echo $_LANG['DEL_CAT']; ?></a></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else if ($is_writer) { ?>
            <table cellspacing="0" cellpadding="5">
                <tr>
                    <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/edit.png" border="0"/></td>
                    <td width=""><a href="<?php echo $blog['add_post_link']; ?>"><?php echo $_LANG['NEW_POST']; ?></a></td>
                </tr>
            </table>
	<?php } ?>
    </div>
<?php } ?>

<?php if ($blogcats) { ?>
<div class="blog_catlist">
    <div class="blog_cat">
        <table cellspacing="0" cellpadding="1">
            <tr>
                <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/folder.png" border="0" /></td>
                <?php if ($cat_id) { ?>
                    <td><a href="<?php echo $blog['blog_link']; ?>"><?php echo $_LANG['ALL_CATS']; ?></a> <span style="color:#666666">(<?php echo $all_total; ?>)</span></td>
                <?php } else { ?>
                    <td><?php echo $_LANG['ALL_CATS']; ?> <span style="color:#666666">(<?php echo $total; ?>)</span></td>
                <?php } ?>
            </tr>
        </table>
    </div>

    <?php foreach($blogcats as $cat) { ?>
        <div class="blog_cat">
            <table cellspacing="0" cellpadding="2">
                <tr>
                    <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/folder.png" border="0" /></td>
                    <?php if ($cat_id != $cat['id']) { ?>
                        <td><a href="<?php echo $blog['blog_link']; ?>/cat-<?php echo $cat['id']; ?>"><?php echo $cat['title']; ?></a> <span style="color:#666666">(<?php echo $cat['post_count']; ?>)</span></td>
                    <?php } else { ?>
                        <td><?php echo $cat['title']; ?> <span style="color:#666666">(<?php echo $cat['post_count']; ?>)</span></td>
                        <?php $cur_cat = $cat; ?>
                    <?php } ?>
                </tr>
            </table>
        </div>
    <?php } ?>
</div>
<?php if ($cur_cat['description']) { ?>
    <div class="usr_photos_notice"><?php echo nl2br($cur_cat['description']); ?></div>
<?php } ?>
<?php } ?>

<?php if ($posts) { ?>
    <div class="blog_entries">
        <?php foreach($posts as $post) { ?>
        <div class="blog_entry">
            <table width="100%" cellspacing="0" cellpadding="0" class="blog_records">
                <tr>
                    <td width="" class="blog_entry_title_td">
                        <div class="blog_entry_title"><a href="<?php echo $post['url']; ?>"><?php echo $post['title']; ?></a></div>
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
                            <?php if (!empty($post['tagline'])) { ?>
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