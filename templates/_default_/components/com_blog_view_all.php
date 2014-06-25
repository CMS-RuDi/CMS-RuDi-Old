<h1 class="con_heading"><?php echo $_LANG['BLOGS']; ?></h1>

<div class="blog_type_menu">
    <?php if (!$ownertype) { ?>
        <span class="blog_type_active"><?php echo $_LANG['POSTS_RSS']; ?></span>
    <?php } else { ?>
        <a class="blog_type_link" href="/blogs"><?php echo $_LANG['POSTS_RSS']; ?></a>
    <?php } ?>

    <?php if ($ownertype == 'all') { ?>
        <span class="blog_type_active"><?php echo $_LANG['ALL_BLOGS']; ?> (<?php echo $total; ?>)</span>
    <?php } else { ?>
        <a class="blog_type_link" href="/blogs/all.html"><?php echo $_LANG['ALL_BLOGS']; ?></a>
    <?php } ?>

    <?php if ($ownertype == 'single') { ?>
        <span class="blog_type_active"><?php echo $_LANG['PERSONALS']; ?> <span class="blog_type_num">(<?php echo $total; ?>)</span></span>
    <?php } else { ?>
        <a class="blog_type_link" href="/blogs/single.html"><?php echo $_LANG['PERSONALS']; ?></a>
    <?php } ?>

    <?php if ($ownertype == 'multi') { ?>
        <span class="blog_type_active"><?php echo $_LANG['COLLECTIVES']; ?> <span class="blog_type_num">(<?php echo $total; ?>)</span></span>
    <?php } else { ?>
        <a class="blog_type_link" href="/blogs/multi.html"><?php echo $_LANG['COLLECTIVES']; ?></a>
    <?php } ?>
</div>
<?php if ($blogs) { ?>
<table width="100%" cellspacing="0" cellpadding="4" class="blog_full_list">
    <?php foreach($blogs as $blog) { ?>
    <tr>
        <td class="blog_title_td"><a class="blog_title" href="<?php echo $blog['url']; ?>"><?php echo $blog['title']; ?></a></td>
        <?php if ($blog['ownertype'] == 'single') { ?>
            <td width="220"><a class="blog_user" href="<?php echo cmsUser::getProfileURL($blog['login']); ?>"><?php echo $blog['nickname']; ?></a></td>
        <?php } else { ?>
            <td width="220">&nbsp;</td>
        <?php } ?>
        <td width="40"><span class="blog_posts"><?php echo $blog['records']; ?></span></td>
        <td width="40"><span class="blog_comm"><?php echo $blog['comments_count']; ?></span></td>
        <?php if ($cfg['rss_one']) { ?>
            <td width="16">
                <a class="blog_rss" href="/rss/blogs/<?php echo $blog['id']; ?>/feed.rss"></a>
            </td>
        <?php } ?>
        <td width="20" align="center" valign="middle"><?php echo $this->rating($blog['rating']); ?></td>
    </tr>
    <?php } ?>
</table>
    
<?php if ($cfg['rss_all']) { ?>
<div class="blogs_full_rss">
    <a href="/rss/blogs/all/feed.rss"><?php echo $_LANG['BLOGS_RSS']; ?></a>
</div>
<?php } ?>
<?php echo $pagination; ?>
<?php } else { ?>
    <p><?php echo $_LANG['NOT_ACTIVE_BLOGS']; ?></p>
<?php } ?>