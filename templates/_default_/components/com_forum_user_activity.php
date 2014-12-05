<div class="float_bar">
<?php if ($sub_do == 'threads') { ?>
    <a class="ajaxlink" href="javascript:" onclick="forum.getUserActivity('threads', '<?php echo $link; ?>', '1');"><strong><?php echo $_LANG['THREADS']; ?> (<?php echo $thread_count; ?>)</strong></a> | <a class="ajaxlink" href="javascript:" onclick="forum.getUserActivity('posts', '<?php echo $link; ?>', '1');"><?php echo $_LANG['MESSAGES1']; ?> (<?php echo $post_count; ?>)</a>
<?php } else { ?>
    <?php if ($thread_count) { ?><a class="ajaxlink" href="javascript:" onclick="forum.getUserActivity('threads', '<?php echo $link; ?>', '1');"><?php echo $_LANG['THREADS']; ?> (<?php echo $thread_count; ?>)</a> | <?php } ?><a class="ajaxlink" href="javascript:" onclick="forum.getUserActivity('posts', '<?php echo $link; ?>', '1');"><strong><?php echo $_LANG['MESSAGES1']; ?> (<?php echo $post_count; ?>)</strong></a>
<?php } ?>

<?php if (($is_admin || $is_moderator) && !$my_profile) { ?> | <a class="ajaxlink" href="javascript:" onclick="forum.clearAllPosts('<?php echo $user_id; ?>', '<?php echo cmsUser::getCsrfToken(); ?>');"><?php echo $_LANG['DELETE_ALL_USER_POSTS']; ?></a><?php } ?>
</div>

<h1 class="con_heading"><?php echo $pagetitle; ?></h1>

<?php if ($sub_do == 'threads') { ?>
    <?php include(PATH .'/templates/'. cmsCore::c('config')->template .'/components/com_forum_view.php'); ?>
<?php } else { ?>

    <?php if ($post_count) { ?>

    <table class="posts_table" width="100%" cellspacing="2" cellpadding="5" border="0" bordercolor="#999999">
        <?php $last_thread_id = ''; ?>
        <?php foreach($posts as $post) { ?>
            <?php if ($post['thread_id'] != $last_thread_id) { ?>
            <tr>
              <td colspan="2" class="darkBlue-LightBlue"><?php echo $_LANG['THREAD']; ?>: <a  href="/forum/thread<?php echo $post['thread_id']; ?>.html" ><?php echo $post['thread_title']; ?></a></td>
            </tr>
            <?php } ?>
            <?php $last_thread_id = $post['thread_id']; ?>
            <tr class="posts_table_tr">
                <td class="post_usercell" width="140" align="center" valign="top" height="150">
                    <div>
                        <a href="<?php echo cmsUser::getProfileURL($post['login']); ?>" title="<?php echo $_LANG['GOTO_PROFILE']; ?>"><?php echo $this->escape($post['nickname']); ?></a>
                    </div>
                    <div class="post_userrank">
                        <?php if ($post['userrank']['group']) { ?>
                            <span class="<?php echo $post['userrank']['class']; ?>"><?php echo $post['userrank']['group']; ?></span>
                        <?php } ?>
                        <?php if ($post['userrank']['rank']) { ?>
                            <span class="<?php echo $post['userrank']['class']; ?>"><?php echo $post['userrank']['rank']; ?></span>
                        <?php } ?>
                    </div>
                    <div class="post_userimg">
                        <a href="<?php echo cmsUser::getProfileURL($post['login']); ?>" title="<?php echo $_LANG['GOTO_PROFILE']; ?>"><img border="0" class="usr_img_small" src="<?php echo $post['avatar_url']; ?>" alt="<?php echo $this->escape($post['nickname']); ?>" /></a>
                        <?php if ($post['user_awards']) { ?>
                            <div class="post_userawards">
                                <?php foreach($post['user_awards'] as $award) { ?>
                                    <img src="/images/icons/award.gif" border="0" alt="<?php echo $this->escape($award['title']); ?>" title="<?php echo $this->escape($award['title']); ?>"/>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="post_usermsgcnt"><?php echo $_LANG['MESSAGES']; ?>: <?php echo $post['post_count']; ?></div>
                    <?php if ($post['city']) { ?>
                        <div class="post_usermsgcnt"><?php echo $post['city']; ?></div>
                    <?php } ?>
                    <div><?php echo $post['flogdate']; ?></div>
                </td>
                <td width="" class="post_msgcell" align="left" valign="top">

                    <div class="post_user_date">
                        <?php echo $post['fpubdate']; ?>, <?php echo $post['wday']; ?>
                    </div>

                    <div class="post_content"><?php echo $post['content_html']; ?></div>
                    <?php if ($post['attached_files'] && $cfg['fa_on']) { ?>
                        <div id="attached_files_<?php echo $post['id']; ?>">
                        <?php include(PATH .'/templates/'. cmsCore::c('config')->template .'/components/com_forum_attached_files.php'); ?>
                        </div>
                    <?php } ?>
                    <?php if ($post['edittimes']) { ?>
                        <div class="post_editdate"><?php echo $_LANG['EDITED']; ?>: <?php echo $this->spellcount($post['edittimes'], $_LANG['COUNT1'], $_LANG['COUNT2'], $_LANG['COUNT1']); ?> (<?php echo $_LANG['LAST_EDIT']; ?>: <?php echo $post['peditdate']; ?>)</div>
                    <?php } ?>
                    <?php if ($post['signature_html']) { ?>
                        <div class="post_signature"><?php echo $post['signature_html']; ?></div>
                    <?php } ?>
                </td>
            </tr>
            <?php $num++; ?>
        <?php } ?>
    </table>
    <?php echo $pagination; ?>

    <?php } else { ?>
        <p><?php echo $_LANG['NOT_POST_BY_USER']; ?></p>
    <?php } ?>

<?php } ?>