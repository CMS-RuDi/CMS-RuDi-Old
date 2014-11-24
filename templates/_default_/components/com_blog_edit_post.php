<div class="con_heading"><?php echo $pagetitle; ?></div>

<form style="margin-top:15px" action="" method="post" name="msgform" enctype="multipart/form-data">
    <table width="100%" border="0" cellpadding="6" cellspacing="0">
        <tr>
            <td width="240"><strong><?php echo $_LANG['TITLE_POST']; ?>: </strong></td>
            <td><input name="title" class="text-input" type="text" id="title" style="width:400px" value="<?php echo $this->escape($mod['title']); ?>"/></td>
        </tr>
        
        <?php if ($blog['showcats'] && $cat_list) { ?>
            <tr>
                <td><strong><?php echo $_LANG['BLOG_CAT']; ?>:</strong></td>
                <td>
                    <select name="cat_id" id="cat_id" style="width:407px">
                        <option value="0" <?php if (empty($mod['cat_id'])) { ?>  selected <?php } ?>><?php echo $_LANG['WITHOUT_CAT']; ?></option>
                        <?php echo $cat_list; ?>
                    </select>
                </td>
            </tr>
        <?php } ?>
        
        <?php if ($myblog || $is_admin) { ?>
            <tr>
                <td><strong><?php echo $_LANG['SHOW_POST']; ?>:</strong></td>
                <td>
                    <select name="allow_who" id="allow_who" style="width:407px">
                        <option value="all" <?php if (!isset($mod['allow_who']) || $mod['allow_who'] == 'all') { ?> selected <?php } ?>><?php echo $_LANG['TO_ALL']; ?></option>
                        <option value="friends" <?php if ($mod['allow_who'] == 'friends') { ?> selected <?php } ?>><?php echo $_LANG['TO_MY_FRIENDS']; ?></option>
                        <option value="nobody" <?php if ($mod['allow_who'] == 'nobody') { ?> selected <?php } ?>><?php echo $_LANG['TO_ONLY_ME']; ?></option>
                    </select>
                </td>
            </tr>
        <?php } else { ?>
            <input type="hidden" name="allow_who" value="<?php echo $blog['allow_who']; ?>" />
        <?php } ?>
        
        <tr>
            <td><strong><?php echo $_LANG['YOUR_MOOD']; ?>:</strong></td>
            <td><input name="feel" class="text-input" type="text" id="feel" style="width:400px" value="<?php echo $this->escape($mod['feel']); ?>"/></td>
        </tr>
        
        <tr>
            <td><strong><?php echo $_LANG['PLAY_MUSIC']; ?>:</strong></td>
            <td><input name="music" class="text-input" type="text" id="music" style="width:400px" value="<?php echo $this->escape($mod['music']); ?>"/></td>
        </tr>
        
        <?php if ($is_admin || $user_can_iscomments) { ?>
            <tr>
                <td valign="top">
                    <strong><?php echo $_LANG['COMMENTS']; ?>:</strong>
                </td>
                <td>
                    <select name="comments" id="comments" style="width:407px">
                        <option value="0" <?php if (!$mod['comments']) { ?>selected="selected"<?php } ?>><?php echo $_LANG['NO']; ?></option>
                        <option value="1" <?php if ($mod['comments']) { ?>selected="selected"<?php } ?> ><?php echo $_LANG['YES']; ?></option>
                    </select><br />
                    <span class="hinttext" style="font-size:11px"><?php echo $_LANG['IS_COMMENTS']; ?></span>
                </td>
            </tr>
        <?php } ?>
        
        <tr>
            <td valign="top">
                <strong><?php echo $_LANG['TAGS']; ?>:</strong>
            </td>
            <td>
                <input name="tags" class="text-input" type="text" id="tags" style="width:400px" value="<?php echo $this->escape($mod['tags']); ?>"/><br />
                <span class="hinttext" style="font-size:11px"><?php echo $_LANG['KEYWORDS']; ?></span>
                <script type="text/javascript">
                    <?php echo $autocomplete_js; ?>
                </script>
            </td>
        </tr>
        
        <?php if ($cfg['seo_user_access'] || $is_admin) { ?>
        <tr>
            <td valign="top"><strong><?php echo $_LANG['SEO_PAGETITLE']; ?></strong></td>
            <td>
                <input name="pagetitle" style="width:400px" class="text-input" value="<?php echo $this->escape($mod['pagetitle']); ?>" />
                <div class="hinttext" style="font-size:11px"><?php echo $_LANG['SEO_PAGETITLE_HINT']; ?></div>
            </td>
        </tr>
        <tr>
            <td valign="top"><strong><?php echo $_LANG['SEO_METAKEYS']; ?></strong></td>
            <td>
                <input name="meta_keys" style="width:400px" class="text-input" value="<?php echo $this->escape($mod['meta_keys']); ?>" />
            </td>
        </tr>
        <tr>
            <td valign="top"><strong><?php echo $_LANG['SEO_METADESCR']; ?></strong></td>
            <td>
                <textarea name="meta_desc" rows="3" style="width:400px" class="text-input"><?php echo $this->escape($mod['meta_desc']); ?></textarea>
                <div class="hinttext" style="font-size:11px"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
            </td>
        </tr>
        <?php } ?>
        
        <tr>
            <td colspan="2">
                <div class="usr_msg_bbcodebox"><?php echo $bb_toolbar; ?></div>
                <?php echo $smilies; ?>
                <?php echo $autogrow; ?>
                <div class="cm_editor"><textarea rows="15" class="ajax_autogrowarea" name="content" id="message"><?php echo $this->escape($mod['content']); ?></textarea></div>
                <div style="margin-top:12px;margin-bottom:15px;" class="hinttext">
                    <strong><?php echo $_LANG['IMPORTANT']; ?>:</strong> <?php echo $_LANG['CUT_TEXT']; ?>,<br/>
                    <a href="javascript:addTagCut('message');" class="ajaxlink"><?php echo $_LANG['ADD_CUT_TAG']; ?></a> <?php echo $_LANG['BETWEEN']; ?>.
                </div>
            </td>
        </tr>
    </table>
                
    <p>
        <input name="goadd" type="submit" id="goadd" value="<?php echo $_LANG['SAVE_POST']; ?>" />
        <input name="cancel" type="button" onclick="window.history.go(-1)" value="<?php echo $_LANG['CANCEL']; ?>" />
    </p>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        $('#title').focus();
    });
</script>