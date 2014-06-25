<table cellspacing="2" cellpadding="3" align="right">
    <tr>
        <?php if (!$thread['closed']) { ?>
        <td width="16">
            <img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/add.png"/>
        </td>
        <td>
            <a href="/forum/reply<?php echo $thread['id']; ?>.html"><strong><?php echo $_LANG['NEW_MESSAGE']; ?></strong></a>
        </td>
        <?php if (!$is_subscribed) { ?>
            <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/subscribe.png"/></td>
            <td><a href="/forum/subscribe<?php echo $thread['id']; ?>.html"><?php echo $_LANG['SUBSCRIBE_THEME']; ?></a></td>
        <?php } else { ?>
            <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/unsubscribe.png"/></td>
            <td><a href="/forum/unsubscribe<?php echo $thread['id']; ?>.html"><?php echo $_LANG['UNSUBSCRIBE']; ?></a></td>
        <?php } ?>
        <?php } else { ?>
            <td><strong><?php echo $_LANG['THREAD_CLOSE']; ?></td>
        <?php } ?>

        <?php if ($is_admin || $is_moder) { ?>
            <td width="16" class="closethread" <?php if ($thread['closed']) { ?>style="display: none"<?php } ?>>
                <img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/forum/toolbar/lock_open.png"/>
            </td>
            <td class="closethread" <?php if ($thread['closed']) { ?>style="display: none"<?php } ?>>
                <a class="ajaxlink" href="javascript:" onclick="forum.ocThread(<?php echo $thread['id']; ?>, 1);"><?php echo $_LANG['CLOSE']; ?></a>
            </td>
            <td width="16" class="openthread" <?php if (!$thread['closed']) { ?>style="display: none"<?php } ?>>
                <img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/forum/toolbar/lock.png"/>
            </td>
            <td class="openthread" <?php if (!$thread['closed']) { ?>style="display: none"<?php } ?>>
                <a class="ajaxlink" href="javascript:" onclick="forum.ocThread(<?php echo $thread['id']; ?>, 0);"><?php echo $_LANG['OPEN']; ?></a>
            </td>

            <td width="16" class="pinthread" <?php if ($thread['pinned']) { ?>style="display: none"<?php } ?>>
                <img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/forum/toolbar/pinthread.png"/>
            </td>
            <td class="pinthread" <?php if ($thread['pinned']) { ?>style="display: none"<?php } ?>>
                <a class="ajaxlink" href="javascript:" onclick="forum.pinThread(<?php echo $thread['id']; ?>, 1);"><?php echo $_LANG['PIN']; ?></a>
            </td>
            <td width="16" class="unpinthread" <?php if (!$thread['pinned']) { ?>style="display: none"<?php } ?>>
                <img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/forum/toolbar/unpinthread.png"/>
            </td>
            <td class="unpinthread" <?php if (!$thread['pinned']) { ?>style="display: none"<?php } ?>>
                <a class="ajaxlink" href="javascript:" onclick="forum.pinThread(<?php echo $thread['id']; ?>, 0);"><?php echo $_LANG['UNPIN']; ?></a>
            </td>

            <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/move.png"/></td>
            <td><a class="ajaxlink" href="javascript:" onclick="forum.moveThread(<?php echo $thread['id']; ?>);"><?php echo $_LANG['MOVE']; ?></a></td>
        <?php } ?>
        <?php if ($is_admin || $is_moder || $thread['is_mythread']) { ?>
            <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/edit.png"/></td>
            <td><a class="ajaxlink" href="javascript:" onclick="forum.renameThread(<?php echo $thread['id']; ?>);"><?php echo $_LANG['RENAME']; ?></a></td>
        <?php } ?>
        <?php if ($is_admin || $is_moder) { ?>
            <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/delete.png"/></td>
            <td><a class="ajaxlink" href="javascript:" onclick="forum.deleteThread(<?php echo $thread['id']; ?>, '<?php echo cmsUser::getCsrfToken(); ?>');"><?php echo $_LANG['DELETE']; ?></a></td>
        <?php } ?>
        <td width="16"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/forum/toolbar/back.png"/></td>
        <td><a href="/forum/<?php echo $forum['id']; ?>"><?php echo $_LANG['BACKB']; ?></a></td>
    </tr>
</table>