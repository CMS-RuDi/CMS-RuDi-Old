<input type="hidden" name="target_id" value="<?php echo $target_id; ?>" />
<input type="hidden" name="component" value="<?php echo $component; ?>" />

<?php if ($total) { ?>

    <?php foreach($records as $record) { ?>
        <div class="usr_wall_entry" id="wall_entry_<?php echo $record['id']; ?>">
            <div class="usr_wall_title"><a href="<?php echo cmsUser::getProfileURL($record['author_login']); ?>"><?php echo $record['author']; ?></a>, <?php echo $record['fpubdate']; ?><?php if ($record['is_today']) { ?> <?php echo $_LANG['BACK']; ?><?php } ?>:</div>
            <?php if ($my_profile || $record['author_id'] == $user_id || $is_admin) { ?>
                <div class="usr_wall_delete"><a class="ajaxlink" href="javascript:void(0)" onclick="deleteWallRecord('<?php echo $component; ?>', '<?php echo $target_id; ?>', '<?php echo $record['id']; ?>', '<?php echo cmsUser::getCsrfToken(); ?>');return false;"><?php echo $_LANG['DELETE']; ?></a></div>
            <?php } ?>

            <table style="width:100%; margin-bottom:2px;" cellspacing="0" cellpadding="0">
            <tr>
                <td width="70" valign="top" align="center" style="text-align:center">
                    <div class="usr_wall_avatar">
                        <a href="<?php echo cmsUser::getProfileURL($record['author_login']); ?>"><img border="0" class="usr_img_small" src="<?php echo $record['avatar']; ?>" /></a>
                    </div>
                </td>
                <td width="" valign="top" class="usr_wall_text"><?php echo $record['content']; ?></td>
            </tr>
            </table>
        </div>
    <?php } ?>

	<?php echo $pagebar; ?>

<?php } else { ?>
    <p><?php echo $_LANG['NOT_POSTS_ON_WALL_TEXT']; ?></p>
<?php } ?>