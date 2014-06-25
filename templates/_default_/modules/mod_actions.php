<?php if (!empty($actions)) { ?>
    <div class="actions_list">
        <?php foreach ($actions as $aid => $action) { ?>
            <div class="action_entry act_<?php echo $action['name']; ?>">
                <div class="action_date<?php if ($action['is_new'] && $user_id != $action['user_id']) { echo ' is_new'; } ?>"><?php echo $action['pubdate'] .' '. $_LANG['BACK']; ?></div>
                <div class="action_title">
                    <a href="<?php echo $action['user_url']; ?>" class="action_user"><?php echo $action['user_nickname']; ?></a>
                    <?php
                        if (!empty($action['message'])) {
                            echo $action['message'] . (!empty($action['description']) ? ':' : '');
                        } else {
                            if (!empty($action['description'])) {
                                echo '&rarr; '. $action['description'];
                            }
                        }
                        if (!empty($action['message']) && !empty($action['description'])) {
                            echo '<div class="action_details">'. $action['description'] .'</div>';
                        }
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php if ($cfg['show_link']) { ?>
    <p>
        <a href="/actions" class="mod_act_all"><?php echo $_LANG['ALL_ACTIONS']; ?></a>
    </p>
    <?php } ?>
<?php } ?>