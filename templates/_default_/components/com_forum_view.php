<table class="threads_table" width="100%" cellspacing="0" cellpadding="5" border="0">
    <tr>
        <td colspan="2" class="darkBlue-LightBlue"><?php echo $_LANG['THREADS']; ?></td>
        <td class="darkBlue-LightBlue"><?php echo $_LANG['AUTHOR']; ?></td>
        <td class="darkBlue-LightBlue"><?php echo $_LANG['FORUM_ACT']; ?></td>
        <td class="darkBlue-LightBlue"><?php echo $_LANG['LAST_POST']; ?></td>
    </tr>
<?php if ($threads) { ?>
    <?php $row = 1; ?>
    <?php foreach($threads as $thread) { ?>
        <?php if ($row % 2) { $class = 'row1'; } else { $class = 'row2'; } ?>
        <tr>
            <?php if ($thread['pinned']) { ?>
                <td width="30" class="<?php echo $class; ?>" align="center" valign="middle"><img alt="<?php echo $_LANG['ATTACHED_THREAD']; ?>" src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/forum/pinned.png" border="0" title="<?php echo $_LANG['ATTACHED_THREAD']; ?>" /></td>
            <?php } else { ?>
                <?php if ($thread['closed']) { ?>
                    <td width="30" class="<?php echo $class; ?>" align="center" valign="middle"><img alt="<?php echo $_LANG['THREAD_CLOSE']; ?>" src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/forum/closed.png" border="0" title="<?php echo $_LANG['THREAD_CLOSE']; ?>" /></td>
                <?php } else { ?>
                    <?php if ($thread['is_new']) { ?>
                        <td width="30" class="<?php echo $class; ?>" align="center" valign="middle"><img alt="<?php echo $_LANG['HAVE_NEW_MESS']; ?>" src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/forum/new.png" border="0" title="<?php echo $_LANG['HAVE_NEW_MESS']; ?>" /></td>
                    <?php } else { ?>
                        <td width="30" class="<?php echo $class; ?>" align="center" valign="middle"><img alt="<?php echo $_LANG['NOT_NEW_MESS']; ?>" src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/forum/old.png" border="0" title="<?php echo $_LANG['NOT_NEW_MESS']; ?>" /></td>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            <td width="" class="<?php echo $class; ?>" align="left">
                <div class="thread_link"><a href="/forum/thread<?php echo $thread['id']; ?>.html"><?php echo $thread['title']; ?></a>
                    <?php if ($thread['pages'] > 1) { ?>
                        <span class="thread_pagination" title="<?php echo $_LANG['PAGES']; ?>"> (
                            <?php for ($i=1;$i<=$thread['pages']+1;$i++) { ?>
                                <?php if ($i > 5 && $thread['pages'] > 6) { ?>
                                    ...<a href="/forum/thread<?php echo $thread['id']; ?>-<?php echo $thread['pages']; ?>.html" title="<?php echo $_LANG['LAST']; ?>"><?php echo $thread['pages']; ?></a>
                                    <?php break; ?>
                                <?php } else { ?>
                                    <a href="/forum/thread<?php echo $thread['id']; ?>-{$smarty.section.foo.index}.html" title="<?php echo $_LANG['PAGE']; ?> {$smarty.section.foo.index}">{$smarty.section.foo.index}</a>
                                    <?php if ($i < $thread['pages']) { ?>, <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        ) </span>
                    <?php } ?>
                </div>
                <?php if ($thread['description']) { ?>
                    <div class="thread_desc"><?php echo $thread['description']; ?></div>
                <?php } ?>
            </td>
            <td width="120" style="font-size:12px" class="<?php echo $class; ?>"><a href="<?php echo cmsUser::getProfileURL($thread['login']); ?>"><?php echo $thread['nickname']; ?></a></td>
            <td width="120" style="font-size:12px; color:#375E93" class="<?php echo $class; ?>">
                <strong><?php echo $_LANG['HITS']; ?>:</strong> <?php echo $thread['hits']; ?><br/>
                <strong><?php echo $_LANG['REPLIES']; ?>:</strong> <?php echo $thread['answers']; ?>
            </td>
            <td width="200" style="font-size:12px" class="<?php echo $class; ?>">
                <?php if ($thread['last_msg_array']) { ?>
                    <a href="/forum/thread<?php echo $thread['last_msg_array']['thread_id']; ?>-<?php echo $thread['last_msg_array']['lastpage']; ?>.html#<?php echo $thread['last_msg_array']['id']; ?>"><img class="last_post_img" title="<?php echo $_LANG['GO_LAST_POST']; ?>" alt="<?php echo $_LANG['GO_LAST_POST']; ?>" src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/anchor.png"></a>
                    <?php echo $_LANG['FROM']; ?> <?php echo $thread['last_msg_array']['user_link']; ?><br/>
                    <?php echo $thread['last_msg_array']['fpubdate']; ?>
                <?php } else { ?>
                    <?php echo $_LANG['NOT_POSTS']; ?>
                <?php } ?>
            </td>
        </tr>
        <?php $row++; ?>
    <?php } ?>

<?php } else { ?>
    <td colspan="7" align="center" valign="middle" class="row1">
        <p style="margin: 5px"><?php echo $_LANG['NOT_THREADS_IN_FORUM']; ?>.</p>
    </td>

<?php } ?>
</table>
<?php echo $pagination; ?>

<?php if ($show_panel) { ?>
<table class="threads_table" width="100%" cellspacing="0" cellpadding="5" border="0" style="margin: 10px 0 0 0; font-size: 12px">
    <tr>
        <td class="row1"><?php echo $_LANG['OPTIONS_VIEW']; ?></td>
        <?php if ($moderators) { ?>
            <td class="row1"><?php echo $_LANG['THIS_FORUM_MODERS']; ?></td>
        <?php } ?>
    </tr>
    <tr>
        <td>
            <form action="" method="post">
                <table cellspacing="1" cellpadding="5" border="0" style="color: #555">
                <tbody>
                    <tr valign="bottom">
                      <td>
                          <div><?php echo $_LANG['THREAD_ORDER']; ?></div>
                            <select name="order_by">
                              <option value="title" <?php if ($order_by == 'title') { ?>selected="selected"<?php } ?>><?php echo $_LANG['TITLE']; ?></option>
                              <option value="pubdate" <?php if ($order_by == 'pubdate') { ?>selected="selected"<?php } ?>><?php echo $_LANG['ORDER_DATE']; ?></option>
                              <option value="post_count" <?php if ($order_by == 'post_count') { ?>selected="selected"<?php } ?>><?php echo $_LANG['ANSWER_COUNT']; ?></option>
                              <option value="hits" <?php if ($order_by == 'hits') { ?>selected="selected"<?php } ?>><?php echo $_LANG['HITS_COUNT']; ?></option>
                            </select>
                      </td>
                      <td>
                        <div><?php echo $_LANG['ORDER_TO']; ?></div>
                        <select name="order_to">
                          <option value="asc" <?php if ($order_to == 'asc') { ?>selected="selected"<?php } ?>><?php echo $_LANG['ORDER_ASC']; ?></option>
                          <option value="desc" <?php if ($order_to == 'desc') { ?>selected="selected"<?php } ?>><?php echo $_LANG['ORDER_DESC']; ?></option>
                        </select>
                      </td>
                      <td>
                        <div><?php echo $_LANG['SHOW']; ?></div>
                        <select name="daysprune">
                          <option value="1" <?php if ($daysprune == 1) { ?>selected="selected"<?php } ?>><?php echo $_LANG['SHOW_DAY']; ?></option>
                          <option value="7" <?php if ($daysprune == 7) { ?>selected="selected"<?php } ?>><?php echo $_LANG['SHOW_W']; ?></option>
                          <option value="30" <?php if ($daysprune == 30) { ?>selected="selected"<?php } ?>><?php echo $_LANG['SHOW_MONTH']; ?></option>
                          <option value="365" <?php if ($daysprune == 365) { ?>selected="selected"<?php } ?>><?php echo $_LANG['SHOW_YEAR']; ?></option>
                          <option value="all" <?php if (!$daysprune) { ?>selected="selected"<?php } ?>><?php echo $_LANG['SHOW_ALL']; ?></option>
                        </select>
                      </td>
                      <td>
                        <div></div>
                        <input type="submit" value="<?php echo $_LANG['SHOW_THREADS']; ?>">
                      </td>
                    </tr>
                </tbody>
            </table>
            </form>
        </td>
        <?php if ($moderators) { ?>
            <td style="vertical-align: top">
            <?php foreach($moderators as $moderator) { ?>
                <?php if ($q) { ?>, <?php } ?><a href="<?php echo cmsUser::getProfileURL($moderator['login']); ?>"><?php echo $moderator['nickname']; ?></a>
                <?php $q = 1; ?>
            <?php } ?>
        </td>
        <?php } ?>
    </tr>
</table>
<?php } ?>