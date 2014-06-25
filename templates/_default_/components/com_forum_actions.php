<div class="float_bar"><?php if ($user_id) { ?><a href="/forum/my_activity.html"><?php echo $_LANG['MY_ACTIVITY']; ?></a> | <?php } ?><?php if ($do == 'latest_posts') { ?><a href="/forum/latest_thread"><?php echo $_LANG['NEW_THREADS']; ?></a><?php } else { ?><a href="/forum/latest_posts"><?php echo $_LANG['LATEST_POSTS']; ?></a><?php } ?> | <a href="/forum"><?php echo $_LANG['FORUMS']; ?></a></div>

<h1 class="con_heading"><?php echo $pagetitle; ?> (<?php echo $total; ?>)</h1>
<?php if ($actions) { ?>
    <div class="actions_list">
        <?php foreach($actions as $action) { ?>
            <div class="action_entry act_<?php echo $action['name']; ?>">
                <div class="action_date<?php if ($action['is_new'] && $user_id != $action['user_id']) { ?> is_new<?php } ?>"><?php echo $action['pubdate']; ?> <?php echo $_LANG['BACK']; ?></div>
                <div class="action_title">
                    <a href="<?php echo $action['user_url']; ?>" class="action_user"><?php echo $action['user_nickname']; ?></a>
                    <?php if ($action['message']) { ?>
                        <?php echo $action['message']; ?><?php if ($action['description']) { ?>:<?php } ?>
                    <?php } else { ?>
                        <?php if ($action['description']) { ?>
                            &rarr; <?php echo $action['description']; ?>
                        <?php } ?>
                    <?php } ?>
                </div>
                <?php if ($action['message']) { ?>
                    <?php if ($action['description']) { ?>
                        <div class="action_details"><?php echo $action['description']; ?></div>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
    <?php echo $pagebar; ?>
<?php } ?>