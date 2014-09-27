<?php if ($actions) { ?>
<div class="actions_list" id="actions_list">

<p><strong><?php if ($user_id) { ?><?php echo $_LANG['ACTIONS_USERS']; ?> "<a href="<?php echo $user['user_url']; ?>"><?php echo $user['user_nickname']; ?></a>"<?php } else { ?><?php echo $_LANG['ALL_ACTIONS_FR']; ?><?php } ?>, <?php echo $_LANG['SHOWN_LAST']; ?> <?php echo $cfg['perpage_tab']; ?>.</strong></p>

        <?php foreach($actions as $action) { ?>
            <?php if ($action['item_date']) { ?>
                <h3><?php echo $action['item_date']; ?></h3>
            <?php } ?>
            <div class="action_entry act_<?php echo $action['name']; ?>">
                <div class="action_date<?php if ($action['is_new']) { ?> is_new<?php } ?>"><?php echo $action['pubdate']; ?> <?php echo $_LANG['BACK']; ?></div>
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
<?php } else { ?>
    <p><?php echo $_LANG['ACTIONS_NOT_FOUND']; ?>.</p>
<?php } ?>
<input name="user_id" type="hidden" value="" />