<div class="con_heading"><?php echo $_LANG['SELECTING_AVATAR']; ?></div>
<div class="con_text"><?php echo $_LANG['CLICK_ON_AVATAR_TEXT']; ?>:</div>

<table class="" style="margin-top:15px;margin-bottom:15px;" cellpadding="5" width="100%" border="0">
    <?php $col = 1; ?>
    <?php foreach($avatars as $avatar_id => $avatar) { ?>
        <?php if ($col == 1) { ?> <tr> <?php } ?>
            <?php $avatar_id = ($page-1)*$perpage + $avatar_id; ?>
            <td width="25%" valign="middle" align="center">
                <a href="/users/<?php echo $userid; ?>/select-avatar/<?php echo $avatar_id; ?>" title="<?php echo $_LANG['SELECT_AVATAR']; ?>">
                    <img src="<?php echo $avatars_dir; ?>/<?php echo $avatar; ?>" border="0" />
                </a>
            </td>
        <?php if ($col == 4) { $col = 1; echo '</tr>'; } else { $col++; } ?>
    <?php } ?>

    <?php if ($col > 1) { ?>
        <td colspan="<?php echo 5-$col; ?>">&nbsp;</td></tr>
    <?php } ?>
</table>

<?php echo $pagebar; ?>