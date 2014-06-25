<?php if ($latest) { ?>
    <div style="margin-bottom:8px"><strong><?php echo $_LANG['USERFILES_NEW_FILES']; ?></strong></div>

    <table width="100%" cellpadding="0" cellspacing="2" border="0" style="margin-bottom:10px">
        <?php foreach($latest as $file) { ?>
            <tr>
                <td><a href="/users/files/download<?php echo $file['id']; ?>.html"><?php echo $file['filename']; ?></a> - <?php echo $file['size']; ?> <?php echo $_LANG['SIZE_MB']; ?></td>
                <td width="35">
                    <a href="<?php echo cmsUser::getProfileURL($file['user_login']); ?>" title="<?php echo $this->escape($file['user_nickname']); ?>">
                        <img src="/images/icons/users.gif" border="0" />
                    </a>
                    <a href="/users/<?php echo $file['user_id']; ?>/files.html" title="<?php echo $_LANG['USERFILES_ALL_USER_FILES']; ?>">
                        <img src="/images/markers/folder.png" border="0" />
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>

<?php if ($popular) { ?>
    <div style="margin-bottom:8px"><strong><?php echo $_LANG['USERFILES_POPULAR_FILES']; ?></strong></div>

    <table width="100%" cellpadding="0" cellspacing="2" border="0" style="margin-bottom:10px">
        <?php foreach($popular as $file) { ?>
            <tr>
                <td><a href="/users/files/download<?php echo $file['id']; ?>.html"><?php echo $file['filename']; ?></a> - <?php echo $file['size']; ?> <?php echo $_LANG['SIZE_MB']; ?></td>
                <td width="35">
                    <a href="<?php echo cmsUser::getProfileURL($file['user_login']); ?>" title="<?php echo $this->escape($file['user_nickname']); ?>">
                        <img src="/images/icons/users.gif" border="0" />
                    </a>
                    <a href="/users/<?php echo $file['user_id']; ?>/files.html" title="<?php echo $_LANG['USERFILES_ALL_USER_FILES']; ?>">
                        <img src="/images/markers/folder.png" border="0" />
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>

<?php if ($cfg['sw_stats']) { ?>
    <div><strong><?php echo $_LANG['USERFILES_TOTAL_FILES']; ?>:</strong> <?php echo $stats['total_files']; ?></div>
    <div><strong><?php echo $_LANG['USERFILES_TOTAL_SIZE']; ?>:</strong> <?php echo $stats['total_size']; ?> <?php echo $_LANG['SIZE_MB']; ?></div>
<?php } ?>