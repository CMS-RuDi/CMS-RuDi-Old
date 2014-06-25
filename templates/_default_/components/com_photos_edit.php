<form action="<?php echo $form_action; ?>" method="post" id="edit_photo_form">
<input type="hidden" value="1" name="edit_photo" />
<table width="100%" border="0" cellspacing="0" cellpadding="3" style="margin:10px">
  <tr>
    <td width="175" valign="top"><strong><?php echo $_LANG['PHOTO_TITLE']; ?>:</strong></td>
    <td><input name="title" type="text" class="text-input" size="40" maxlength="250" style="width:350px" value="<?php echo $this->escape($photo['title']); ?>"/></td>
  </tr>
  <tr>
    <td valign="top"><strong><?php echo $_LANG['PHOTO_DESC']; ?>:</strong></td>
    <td><textarea name="description" cols="39" rows="5" style="width:350px" class="text-input"><?php echo $this->escape($photo['description']); ?></textarea></td>
  </tr>
<?php if (!$no_tags) { ?>
  <tr>
    <td valign="top"><strong><?php echo $_LANG['TAGS']; ?>:</strong></td>
    <td><input name="tags" type="text" class="text-input" style="width:350px" size="40" value="<?php echo $this->escape($photo['tags']); ?>"/><br /><span><small><?php echo $_LANG['KEYWORDS']; ?></small></span></td>
  </tr>
<?php } ?>
  <tr>
    <td valign="top"><strong><?php echo $_LANG['REPLACE_FILE']; ?>:</strong></td>
    <td><input name="Filedata" type="file" class="text-input" style="width:350px" /><br><br><img alt="" src="/images/photos/small/<?php echo $photo['file']; ?>" border="0" /></td>
  </tr>
<?php if ($is_admin) { ?>
  <tr>
    <td valign="top"><strong><?php echo $_LANG['COMMENT_PHOTO']; ?>:</strong></td>
    <td><select name="comments" style="width:60px">
            <option value="0" <?php if (!$photo['comments']) { ?>selected="selected"<?php } ?>><?php echo $_LANG['NO']; ?></option>
            <option value="1" <?php if ($photo['comments']) { ?>selected="selected"<?php } ?> ><?php echo $_LANG['YES']; ?></option>
        </select>
    </td>
  </tr>
<?php } ?>
</table>
</form>
<script type="text/javascript" src="/includes/jquery/jquery.form.js"></script>