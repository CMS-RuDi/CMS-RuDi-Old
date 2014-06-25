<table class="module" width="100%" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td class="moduletitle">
                <?php echo $_LANG['FRIEND_ON_SITE']; ?> (<?php echo $total; ?>)
            </td>
        </tr>
        <tr>
            <td class="modulebody">
            <?php if ($total) { ?>
                <?php if ($cfg['view_type'] == 'table') { ?>
                    <?php foreach($friends as $frien) { ?>
                        <div align="center"><a href="<?php echo cmsUser::getProfileURL($frien['login']); ?>"><img border="0" class="usr_img_small" src="<?php echo $frien['avatar']; ?>" /></a></div>
                        <div align="center"><?php echo $frien['user_link']; ?></div>
                    <?php } ?>
                <?php } ?>
                <?php if ($cfg['view_type'] == 'list') { ?>
                    <?php $now = 0; ?>
                    <?php foreach($friends as $frien) { ?>
                        <?php echo $frien['user_link']; ?>
                        <?php $now++; ?>
                        <?php if ($now != $total) { echo ', '; } ?>
                    <?php } ?>
                <?php } ?>
            <?php } else { ?>
                <div align="center"><?php echo $_LANG['FRIEND_NO_SITE']; ?></div>
            <?php } ?> 
            </td>
        </tr>
    </tbody>
</table>