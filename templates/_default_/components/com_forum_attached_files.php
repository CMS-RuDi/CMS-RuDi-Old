<div class="fa_attach" id="fa_attach_<?php echo $post['id']; ?>">
<div class="fa_attach_title"><?php echo $_LANG['ATTACHED_FILE']; ?>:</div>
<?php $file_count = 0; ?>
<?php foreach($post['attached_files'] as $attached_file) { ?>
    <div class="fa_filebox" id="filebox<?php echo $attached_file['id']; ?>">
        <table class="fa_file"><tr>
                <?php if ($attached_file['is_img']) { ?>
                    <td><img src="/upload/forum/post<?php echo $post['id']; ?>/<?php echo $this->escape($attached_file['filename']); ?>" border="0" width="130" /></td>
                <?php } else { ?>
                    <td width="16"><?php echo $attached_file['icon']; ?></td>
                <?php } ?>
                <td>
                    <a class="fa_file_link" href="/forum/download<?php echo $attached_file['id']; ?>.html"><?php echo $attached_file['filename']; ?></a> | <span class="fa_file_desc"><?php echo $attached_file['filesize_kb']; ?> <?php echo $_LANG['KBITE']; ?> | <?php echo $_LANG['DOWNLOADED']; ?>: <?php echo $this->spellcount($attached_file['hits'], $_LANG['COUNT1'], $_LANG['COUNT2'], $_LANG['COUNT1']); ?></span>
                    <?php if ($is_admin || $is_moder || $post['is_author_can_edit']) { ?>
                        <a href="javascript:" title="<?php echo $_LANG['RELOAD_FILE']; ?>" onclick="forum.reloadFile('<?php echo $attached_file['id']; ?>');"><img src="/images/icons/reload.gif" border="0" /></a>
                        <a href="javascript:" title="<?php echo $_LANG['DELETE_FILE']; ?>" onclick="forum.deleteFile('<?php echo $attached_file['id']; ?>', '<?php echo cmsUser::getCsrfToken(); ?>', <?php echo $post['id']; ?>);"><img src="/images/icons/delete.gif" border="0" /></a>
                    <?php } ?>
                </td>
        </tr></table>
    </div>
    <?php $file_count++; ?>
<?php } ?>
<input type="hidden" name="file_count" id="file_count" value="<?php echo $file_count; ?>" />
</div>