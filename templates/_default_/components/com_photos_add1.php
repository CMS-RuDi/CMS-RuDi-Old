<script type="text/javascript">
    function mod_text(){
        if ($('#only_mod').prop('checked')){
            $('#text_mes').html('<strong><?php echo $_LANG['STEP_1']; ?></strong>: <?php echo $_LANG['PHOTO_DESCS']; ?>.');
            $('#text_title').html('<?php echo $_LANG['PHOTO_TITLES']; ?>:');
            $('#text_desc').html('<?php echo $_LANG['PHOTO_DESCS']; ?>:');
        } else {
            $('#text_mes').html('<strong><?php echo $_LANG['STEP_1']; ?></strong>: <?php echo $_LANG['PHOTO_DESC']; ?>.');
            $('#text_title').html('<?php echo $_LANG['PHOTO_TITLE']; ?>:');
            $('#text_desc').html('<?php echo $_LANG['PHOTO_DESC']; ?>:');
        }
    }
</script>

<h3 style="border-bottom: solid 1px gray" id="text_mes">
    <strong><?php echo $_LANG['STEP_1']; ?></strong>: <?php echo $_LANG['PHOTO_DESC']; ?>.
</h3>

<div class="usr_photos_notice"><?php echo $_LANG['PHOTO_PLEASE_NOTE']; ?></div>

<form action="" method="POST">
    <table width="100%" cellpadding="4">
        <tr>
            <td width="260" id="text_title"><strong><?php echo $_LANG['PHOTO_TITLE']; ?>:</strong></td>
            <td>
                <input name="title" type="text" id="title" class="text-input" style="width:350px;" maxlength="250" value="<?php echo $this->escape($mod['title']); ?>" />
            </td>
        </tr>
        <tr>
            <td valign="top" id="text_desc"><strong><?php echo $_LANG['PHOTO_DESC']; ?>:</strong></td>
            <td valign="top">
                <textarea name="description" style="width:350px;" rows="5" class="text-input"><?php echo $mod['description']; ?></textarea>
            </td>
        </tr>
        <?php if (!$no_tags) { ?>
            <tr>
                <td><strong><?php echo $_LANG['TAGS']; ?>:</strong></td>
                <td>
                    <input name="tags" type="text" id="tags" class="text-input" style="width:350px;" value="<?php echo $this->escape($mod['tags']); ?>"/>
                    <div><small><?php echo $_LANG['KEYWORDS']; ?></small></div>
                    <script type="text/javascript">
                        <?php echo $autocomplete_js; ?>
                    </script>
                </td>
            </tr>
        <?php } ?>
        <?php if ($is_admin) { ?>
            <tr>
                <td valign="top"><strong><?php echo $_LANG['COMMENT_PHOTO']; ?>:</strong></td>
                <td>
                    <select name="comments" style="width:60px">
                        <option value="0"><?php echo $_LANG['NO']; ?></option>
                        <option value="1" selected="selected"><?php echo $_LANG['YES']; ?></option>
                    </select>
                </td>
            </tr>
        <?php } ?>
    
        <?php if ($cfg['seo_user_access'] || $is_admin) { ?>
            <tr>
                <td valign="top"><strong><?php echo $_LANG['SEO_PAGETITLE']; ?></strong><div class="hint"><?php echo $_LANG['SEO_PAGETITLE_HINT']; ?></div></td>
                <td>
                    <input name="pagetitle" style="width:350px" class="text-input" value="<?php echo $this->escape($mod['pagetitle']); ?>" />
                </td>
            </tr>
            <tr>
                <td valign="top"><strong><?php echo $_LANG['SEO_METAKEYS']; ?></strong></td>
                <td>
                    <input name="meta_keys" style="width:350px" class="text-input" value="<?php echo $this->escape($mod['meta_keys']); ?>" />
                </td>
            </tr>
            <tr>
                <td valign="top"><strong><?php echo $_LANG['SEO_METADESCR']; ?></strong><div class="hint"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div></td>
                <td>
                    <textarea name="meta_desc" rows="3" style="width:350px" class="text-input"><?php echo $this->escape($mod['meta_desc']); ?></textarea>
                </td>
            </tr>
        <?php } ?>
    
        <tr>
            <td colspan="2" valign="top">
                <input type="submit" name="submit" id="text_subm" value="<?php echo $_LANG['GO_TO_UPLOAD']; ?>" /> <input id="only_mod" name="only_mod" type="checkbox" value="1" onclick="mod_text()" />  <label for="only_mod"><?php echo $_LANG['ADD_MULTY']; ?></label>
            </td>
        </tr>
    </table>
</form>