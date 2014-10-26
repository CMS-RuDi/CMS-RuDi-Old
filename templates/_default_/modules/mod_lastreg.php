<?php if ($cfg['view_type'] == 'table') { ?>
    <?php foreach ($users as $user) { ?>
        <div class="mod_new_user">
            <div class="mod_new_user_avatar"><a href="<?php echo cmsUser::getProfileURL($user['login']); ?>"><img border="0" class="usr_img_small" src="<?php echo $user['avatar']; ?>" /></a></div>
            <div class="mod_new_user_link"><a href="<?php echo cmsUser::getProfileURL($user['login']); ?>"><?php echo $user['nickname']; ?></a></div>
        </div>
    <?php } ?>
<?php } ?>

<?php if ($cfg['view_type'] == 'hr_table') {
    $col = 1;
?>
    <table cellspacing="5" border="0" width="100%">
        <?php foreach ($users as $user) { ?>
            <?php if ($col == 1) { echo '<tr>'; } ?>
                <td width="" class="new_user_avatar" align="center" valign="middle"><a href="<?php echo cmsUser::getProfileURL($user['login']); ?>" class="new_user_link" title="<?php echo $this->escape($user['nickname']); ?>"><img border="0" class="usr_img_small" src="<?php echo $user['avatar']; ?>" /></a><div class="mod_new_user_link"><a href="<?php echo cmsUser::getProfileURL($user['login']); ?>"><?php echo $user['nickname']; ?></a></div>
                </td>
            <?php if ($col == $cfg['maxcool']) { echo '</tr>'; $col = 1; } else { $col++; } ?>
        <?php } ?>
    </table>
<?php } ?>

<?php if ($cfg['view_type'] == 'list') {
    $now = 0;
    foreach ($users as $user) {
?>
        <a href="<?php echo cmsUser::getProfileURL($user['login']); ?>" class="new_user_link"><?php echo $user['nickname']; ?></a>
        <?php $now++; if ($now != $total){ echo ' ,'; } ?>
<?php } ?>
    <p><strong><?php echo $_LANG['LASTREG_TOTAL']; ?>:</strong> <?php echo $total_all; ?></p>
<?php } ?>