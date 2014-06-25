<?php if ($users) { echo implode(', ', $users); } else { ?>
    <div><strong><?php echo $_LANG['WHOONLINE_USERS']; ?>:</strong> 0</div>
<?php } ?>

<div style="margin-top:10px"><strong><?php echo $_LANG['WHOONLINE_GUESTS']; ?>:</strong> <?php echo $guests; ?></div>

<?php if ($cfg['show_today']) { ?>
    <div style="margin-top:10px;margin-bottom:8px"><strong><?php echo $_LANG['WAS_TODAY']; ?>:</strong></div>
    <?php if ($today_users) { echo implode(', ', $today_users); } else { ?>
        <div><?php echo $_LANG['NOBODY_TODAY']; ?></div>
    <?php } ?>
<?php } ?>