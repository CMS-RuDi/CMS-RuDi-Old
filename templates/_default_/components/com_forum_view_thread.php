<h1 class="con_heading" id="thread_title"><?php echo $thread['title']; ?></h1>

<div id="thread_description" <?php if (!$thread['description']) { ?>style="display: none"<?php } ?>><?php echo $thread['description']; ?></div>

<?php if ($user_id) { ?>
<table width="100%" cellspacing="0" cellpadding="5"  class="forum_toolbar"><tr>
    <td width="5">&nbsp;</td>
    <td class="forum_toollinks">
        <?php include(PATH .'/templates/'. cmsCore::c('config')->template .'/components/com_forum_toolbar.php'); ?>
    </td>
</tr></table>
<?php } ?>

<?php if ($thread_poll) { ?>
    <div id="thread_poll"><?php include(PATH .'/templates/'. cmsCore::c('config')->template .'/components/com_forum_thread_poll.php'); ?></div>
<?php } ?>

<table class="posts_table" width="100%" cellspacing="2" cellpadding="5" border="0" bordercolor="#999999">
    <?php foreach($posts as $post) { ?>
    <tr>
        <td colspan="2" class="darkBlue-LightBlue">
            <div class="post_date"><?php if ($post['pinned'] && $num > 1) { ?><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/forum/sticky.png" width="14px;" alt="<?php echo $_LANG['ATTACHED_MESSAGE']; ?>" title="<?php echo $_LANG['ATTACHED_MESSAGE']; ?>" />  <?php } ?><strong><a name="<?php echo $post['id']; ?>" href="/forum/thread<?php echo $thread['id']; ?>-<?php echo $page; ?>.html#<?php echo $post['id']; ?>">#<?php echo $num; ?></a></strong> - <?php echo $post['fpubdate']; ?>, <?php echo $post['wday']; ?></div>
            <?php if ($user_id && !$thread['closed']) { ?>
                <div class="msg_links">
                    <a href="javascript:" onclick="forum.addQuoteText(this);return false;" rel="<?php echo $this->escape($post['nickname']); ?>" class="ajaxlink" title="<?php echo $_LANG['ADD_SELECTED_QUOTE']; ?>"><?php echo $_LANG['ADD_QUOTE_TEXT']; ?></a> | <a href="/forum/thread<?php echo $thread['id']; ?>-quote<?php echo $post['id']; ?>.html" title="<?php echo $_LANG['REPLY_FULL_QUOTE']; ?>"><?php echo $_LANG['REPLY']; ?></a>
                    <?php if ($is_admin || $is_moder || $post['is_author_can_edit']) { ?>
                        | <a href="/forum/editpost<?php echo $post['id']; ?>-<?php echo $page; ?>.html"><?php echo $_LANG['EDIT']; ?></a>
                        <?php if ($num > 1) { ?>
                            <?php if ($is_admin || $is_moder) { ?>
                                | <a href="javascript:" onclick="forum.movePost('<?php echo $thread['id']; ?>','<?php echo $post['id']; ?>');return false;" class="ajaxlink" title="<?php echo $_LANG['MOVE_POST']; ?>"><?php echo $_LANG['MOVE']; ?></a>
                                <?php if (!$post['pinned']) { ?>
                                | <a href="/forum/pinpost<?php echo $thread['id']; ?>-<?php echo $post['id']; ?>.html"><?php echo $_LANG['PIN']; ?></a>
                                <?php } else { ?>
                                | <a href="/forum/unpinpost<?php echo $thread['id']; ?>-<?php echo $post['id']; ?>.html"><?php echo $_LANG['UNPIN']; ?></a>
                                <?php } ?>
                            <?php } ?>
                            | <a href="javascript:" class="ajaxlink" onclick="forum.deletePost(<?php echo $post['id']; ?>, '<?php echo cmsUser::getCsrfToken(); ?>', <?php echo $page; ?>);"><?php echo $_LANG['DELETE']; ?></a>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </td>
    </tr>
    <tr class="posts_table_tr">
        <td class="post_usercell" width="140" align="center" valign="top" height="150">
            <div>
                <a class="post_userlink" href="javascript:" onclick="addNickname(this);return false;" title="<?php echo $_LANG['ADD_NICKNAME']; ?>" rel="<?php echo $this->escape($post['nickname']); ?>" ><?php echo $this->escape($post['nickname']); ?></a>
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
        <?php if ($thread['closed'] || !$user_id || $post['is_author'] || $post['is_voted']) { ?>
            <div class="votes_links"><?php echo $this->rating($post['rating']); ?></div>
        <?php } else { ?>
            <div class="votes_links" id="votes<?php echo $post['id']; ?>">
                <table border="0" cellpadding="0" cellspacing="0"><tr>
                <td><?php echo $this->rating($post['rating']); ?></td>
                <td><a href="javascript:void(0);" onclick="forum.votePost(<?php echo $post['id']; ?>, -1);"><img border="0" alt="-" src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/comments/vote_down.gif" style="margin-left:8px"/></a></td>
                <td><a href="javascript:void(0);" onclick="forum.votePost(<?php echo $post['id']; ?>, 1);"><img border="0" alt="+" src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/comments/vote_up.gif" style="margin-left:2px"/></a></td>
                </tr></table>
            </div>
        <?php } ?>
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
<?php if ($page == $lastpage) { ?><a name="new"></a><?php } ?>

<?php if ($user_id) { ?>
<table width="100%" cellspacing="0" cellpadding="5"  class="forum_toolbar"><tr>
    <td><a href="#"><?php echo $_LANG['GOTO_BEGIN_PAGE']; ?></a></td>
    <td class="forum_toollinks">
        <?php include(PATH .'/templates/'. cmsCore::c('config')->template .'/components/com_forum_toolbar.php'); ?>
    </td>
</tr>
</table>
<?php } ?>

<div class="forum_navbar">
    <table width="100%"><tr>
        <td align="left">
            <table cellpadding="5" cellspacing="0" border="0" align="left" style="margin-left:auto;margin-right:auto"><tr>
                <?php if ($prev_thread) { ?>
                    <td align="right" width="">
                        <div>&larr; <a href="/forum/thread<?php echo $prev_thread['id']; ?>.html" title="<?php echo $_LANG['PREVIOUS_THREAD']; ?>"><?php echo $this->truncate($prev_thread['title'], 30); ?></a></div>
                    </td>
                <?php } ?>
                <?php if ($prev_thread && $next_thread) { ?><td>|</td><?php } ?>
                <?php if ($next_thread) { ?>
                    <td align="left" width="">
                        <div><a href="/forum/thread<?php echo $next_thread['id']; ?>.html" title="<?php echo $_LANG['NEXT_THREAD']; ?>"><?php echo $this->truncate($next_thread['title'], 30); ?></a> &rarr;</div>
                    </td>
                <?php } ?>
            </tr></table>
        </td>
        <td width="150" align="right"><?php echo $_LANG['GOTO_FORUM']; ?>: </td>
        <td width="220" align="right">
            <select name="goforum" id="goforum" style="width:220px; margin:0px" onchange="window.location.href = '/forum/' + $(this).val();">
            <?php foreach($forums as $item) { ?>
                <?php if ($item['cat_title'] != $last_cat_title) { ?>
                    <?php if ($last_cat_title) { ?></optgroup><?php } ?>
                    <optgroup label="<?php echo $this->escape($item['cat_title']); ?>">
                <?php } ?>
                <option value="<?php echo $item['id']; ?>" <?php if ($item['id'] == $forum['id']) { ?> selected="selected" <?php } ?>><?php echo $item['title']; ?></option>
                <?php if ($item['sub_forums']) { ?>
                    <?php foreach($item['sub_forums'] as $sub_forum) { ?>
                        <option value="<?php echo $sub_forum['id']; ?>" <?php if ($sub_forum['id'] == $forum.id) { ?> selected="selected" <?php } ?>>--- <?php echo $sub_forum['title']; ?></option>
                    <?php } ?>
                <?php } ?>
                <?php $last_cat_title = $item['cat_title']; ?>
            <?php } ?>
            </optgroup>
            </select>
        </td>
    </tr></table>
</div>

<div style="float: right;margin: 8px 0 0;"><?php echo $pagebar; ?></div>

<?php if ($cfg['fast_on'] && !$thread['closed']) { ?>
<div class="forum_fast">
    <div class="forum_fast_header"><?php echo $_LANG['FAST_ANSWER']; ?></div>
    <?php if ($user_id && $is_can_add_post) { ?>
        <?php if ($cfg['fast_bb']) { ?>
            <div class="usr_msg_bbcodebox">
                <?php echo $bb_toolbar; ?>
            </div>
            <?php echo $smilies; ?>
        <?php } ?>
        <div class="forum_fast_form">
            <form action="/forum/reply<?php echo $thread['id']; ?>.html" method="post" id="msgform">
                <input type="hidden" name="gosend" value="1" />
                <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
                <div class="cm_editor">
                    <textarea id="message" name="message" rows="7"></textarea>
                </div>
                <div class="forum_fast_submit" style="float:right;padding:5px;"><input type="button" value="<?php echo $_LANG['SEND']; ?>" onclick="$(this).prop('disabled', true);$('#msgform').submit();" /></div>
                <?php if ($is_admin || $is_moder || $thread['is_mythread']) { ?>
                    <div style="float:right;padding:8px;">
                        <label><input type="checkbox" name="fixed" value="1" /> <?php echo $_LANG['TOPIC_FIXED_LABEL']; ?></label>
                    </div>
                <?php } ?>
            </form>
        </div>
    <?php } else { ?>
        <div style="padding:5px"><?php echo $_LANG['FOR_WRITE_ON_FORUM']; ?>.</div>
    <?php } ?>
</div>
<?php } ?>

<?php if ($user_id) { ?>

<script type="text/javascript" language="JavaScript">
    $(document).ready(function(){
        $('.darkBlue-LightBlue .msg_links').css({opacity:0.4, filter:'alpha(opacity=40)'});
        $('.posts_table_tr').hover(
            function() {
                $(this).prev().find('.msg_links').css({opacity:1.0, filter:'alpha(opacity=100)'});
            },
            function() {
                $(this).prev().find('.msg_links').css({opacity:0.4, filter:'alpha(opacity=40)'});
            }
        );
        $('.msg_links').hover(
            function() {
                $(this).css({opacity:1.0, filter:'alpha(opacity=100)'});
            },
            function() {
                $(this).css({opacity:0.4, filter:'alpha(opacity=40)'});
            }
        );
    });
</script>

<?php } ?>