<div class="float_bar">
    <?php if ($user_id) { ?><?php if ($forum['id']) { ?><a href="/forum/<?php echo $forum['id']; ?>/newthread.html"><strong><?php echo $_LANG['NEW_THREAD']; ?></strong></a> | <?php } ?><a href="/forum/my_activity.html"><?php echo $_LANG['MY_ACTIVITY']; ?></a> | <?php } ?><a href="/forum/latest_posts"><?php echo $_LANG['LATEST_POSTS']; ?></a> | <a href="/forum/latest_thread"><?php echo $_LANG['NEW_THREADS']; ?></a>
</div>

<h1 class="con_heading"><?php echo $pagetitle; ?><?php if ($cfg['is_rss']) { ?> <a href="/rss/forum/<?php if ($forum) { ?><?php echo $forum['id']; ?><?php } else { ?>all<?php } ?>/feed.rss" title="<?php echo $_LANG['RSS']; ?>"><img src="/images/markers/rssfeed.png" border="0" alt="<?php echo $_LANG['RSS']; ?>"/></a><?php } ?></h1>

<?php if ($forums) { ?>
<table class="forums_table" width="100%" cellspacing="0" cellpadding="8" border="0" bordercolor="#999999" >
    <?php $row = 1; ?>
    <?php foreach($forums as $forum) { ?>
        <?php if ($forum['cat_title'] != $last_cat_title) { ?>
            <tr>
              <td colspan="2" width="" class="darkBlue-LightBlue"><a href="/forum/<?php echo $forum['cat_seolink']; ?>"><?php echo $forum['cat_title']; ?></a></td>
              <td width="120" class="darkBlue-LightBlue"><?php echo $_LANG['FORUM_ACT']; ?></td>
              <td width="250" class="darkBlue-LightBlue"><?php echo $_LANG['LAST_POST']; ?></td>
            </tr>
        <?php } ?>
        <?php if ($row % 2) { $class = 'row11'; } else { $class = 'row2'; } ?>
        <tr>
            <td width="32" class="<?php echo $class; ?>" align="center" valign="top"><img src="<?php echo $forum['icon_url']; ?>" border="0" /></td>
            <td width="" class="<?php echo $class; ?>" align="left" valign="top">
                <div class="forum_link"><a href="/forum/<?php echo $forum['id']; ?>"><?php echo $forum['title']; ?></a></div>
                <div class="forum_desc"><?php echo $forum['description']; ?></div>
                <?php if ($forum['sub_forums']) { ?>
                    <div class="forum_subs"><span class="forum_subs_title"><?php echo $_LANG['SUBFORUMS']; ?>: </span>
                        <?php foreach($forum['sub_forums'] as $sub_forum) { ?>
                            <?php if ($comma) { ?>, <?php } ?>
                            <a href="/forum/<?php echo $sub_forum['id']; ?>" title="<?php echo $this->escape($sub_forum['description']); ?>"><?php echo $sub_forum['title']; ?></a>
                            <?php $comma = 1; ?>
                        <?php } ?>
                        <?php $comma = 0; ?>
                    </div>
                <?php } ?>
            </td>
            <td class="<?php echo $class; ?>" style="font-size:11px" valign="top">
                <?php if ($forum['thread_count']) { ?>
                    <strong><?php echo $_LANG['THREADS']; ?>:</strong> <?php echo $forum['thread_count']; ?>
                <?php } else { ?>
                    <?php echo $_LANG['NOT_THREADS']; ?>
                <?php } ?>
                <br/><strong><?php echo $_LANG['MESSAGES']; ?>:</strong> <?php echo $forum['post_count']; ?>

            </td>
            <td style="font-size:11px" class="<?php echo $class; ?>" valign="top">
                <?php if ($forum['last_msg_array']) { ?>
                    <strong><?php echo $_LANG['IN_THREAD']; ?>: <?php echo $forum['last_msg_array']['thread_link']; ?></strong><br/>
                    <?php echo $forum['last_msg_array']['fpubdate']; ?> <?php echo $_LANG['FROM']; ?> <?php echo $forum['last_msg_array']['user_link']; ?>
                <?php } else { ?>
                    <?php echo $_LANG['NOT_POSTS']; ?>
                <?php } ?>

            </td>
        </tr>
        <?php $last_cat_title = $forum['cat_title']; $row++; ?>
    <?php } ?>
</table>
<?php } ?>