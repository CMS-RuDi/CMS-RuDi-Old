<?php
if ($actions) {
    include TEMPLATE_DIR .'/components/com_actions_friends.php';
    include TEMPLATE_DIR .'/components/com_actions_tab.php';
} else {
?>
    <p><?php echo $_LANG['FEED_DESC']; ?></p>
    <p><?php echo $_LANG['FEED_EMPTY_TEXT']; ?></p>
<?php } ?>