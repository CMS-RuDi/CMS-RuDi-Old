<div class="con_heading"><?php echo $pagetitle; ?></div>

<form id="addform" name="addform" method="post" action="" enctype="multipart/form-data">
    <div class="bar" style="padding:15px 10px">
    <table width="100%" cellspacing="5" cellpadding="3" class="proptable">
        <tr>
            <td width="230" valign="top">
                <strong><?php echo $_LANG['TITLE']; ?>:</strong>
            </td>
            <td valign="top">
                <input name="title" type="text" class="text-input" id="title" style="width:350px" value="<?php echo $this->escape($mod['title']); ?>" />
            </td>
        </tr>
        <tr>
            <td valign="top">
                <strong><?php echo $_LANG['TAGS']; ?>:</strong><br />
                <span class="hinttext"><?php echo $_LANG['KEYWORDS_TEXT']; ?></span>
            </td>
            <td valign="top">
                <input name="tags" type="text" class="text-input" id="tags" style="width:350px" value="<?php echo $this->escape($mod['tags']); ?>" />
                <script type="text/javascript">
                    <?php echo $autocomplete_js; ?>
                </script>
            </td>
        </tr>
        <?php if ($do == 'addarticle') { ?>
        <tr>
            <td valign="top">
                <strong><?php echo $_LANG['CAT']; ?>:</strong><br />
                <div><span class="hinttext"><?php echo $_LANG['WHERE_LOCATE_ARTICLE']; ?></span></div>
                <?php if ($is_admin) { ?>
                    <div style="margin-top:10px"><span class="hinttext"><?php echo $_LANG['FOR_ADD_ARTICLE_ON']; ?> <a href="/admin/index.php?view=tree"><?php echo $_LANG['IN_CONFIG']; ?></a> <?php echo $_LANG['FOR_ADD_ARTICLE_ON_TEXT']; ?></span></div>
                <?php } ?>
            </td>
            <td valign="top">
                <select name="category_id" id="category_id" style="width:357px">
                        <option value=""><?php echo $_LANG['SELECT_CAT']; ?></option>
                    <?php foreach($pubcats as $pubcat) { ?>
                        <option value="<?php echo $pubcat['id']; ?>" <?php if ($mod['category_id'] == $pubcat['id']) { ?>selected="selected"<?php } ?>>
                            <?php str_repeat('--', $pubcat['NSLevel']) ?> <?php echo $pubcat['title']; ?>
                            <?php if ($is_billing && $pubcat['cost'] && $dynamic_cost) { ?>
                                (<?php echo $_LANG['BILLING_COST']; ?>: <?php echo $this->spellcount($pubcat['cost'], $_LANG['BILLING_POINT1'], $_LANG['BILLING_POINT2'], $_LANG['BILLING_POINT10']); ?>)
                            <?php } ?>
                        </option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <?php } ?>
        <?php if ($cfg['img_users']) { ?>
        <tr>
            <td valign="top" style="padding-top:8px">
                <strong><?php echo $_LANG['IMAGE']; ?>:</strong>
            </td>
            <td>
                <?php if ($mod['image']) { ?>
                    <div style="padding-bottom:10px">
                        <img src="<?php echo $mod['image_small']; ?>" border="0" />
                    </div>
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="16"><input type="checkbox" id="delete_image" name="delete_image" value="1" /></td>
                            <td><label for="delete_image"><?php echo $_LANG['DELETE']; ?></label></td>
                        </tr>
                    </table>
                <?php } ?>
                <input type="file" name="picture" style="width:350px" />
            </td>
        </tr>
        <?php } ?>
        <?php if ($do == 'editarticle') { ?>
            <input type="hidden" name="category_id" value="<?php echo $mod['category_id']; ?>" />
        <?php } ?>
    </table>
    </div>

    <table width="100%" border="0">
            <tr>
                <td>
                    <h3><?php echo $_LANG['ARTICLE_ANNOUNCE']; ?></h3>
                    <div><?php cmsCore::insertEditor('description', $mod['description'], 200, '100%'); ?></div>

                    <h3><?php echo $_LANG['ARTICLE_TEXT']; ?></h3>
                    <div><?php cmsCore::insertEditor('content', $mod['content'], 450, '100%'); ?></div>
                </td>
            </tr>
    </table>

    <?php if ($cfg['img_on'] && $ajaxUploader) { ?>
    <div class="bar" style="padding:10px 10px;margin-top: 10px;">
    <table width="100%" cellspacing="5" cellpadding="3" class="proptable">
        <tr valign="top">
            <td width="230" style="padding-top:8px">
                <strong><?php echo $_LANG['IMAGES']; ?>:</strong>
                <div class="hinttext">
                    <?php echo $_LANG['IMAGES_INSERT_HINT_TEXT']; ?>
                </div>
            </td>
            <td>
                <?php echo $ajaxUploader; ?>
            </td>
        </tr>
    </table>
    </div>
    <?php } ?>
    
    <script type="text/javascript">
        var LANG_SELECT_CAT = '<?php echo $_LANG['SELECT_CAT']; ?>';
        var LANG_REQ_TITLE  = '<?php echo $_LANG['REQ_TITLE']; ?>';
        var LANG_ERROR      = '<?php echo $_LANG['ERROR']; ?>';
        
        function submitArticle(){
            if (!$('input#title').val()){ core.alert(LANG_REQ_TITLE, LANG_ERROR); return false; }

            <?php if ($do == 'addarticle') { ?>
                if (!$('select#category_id').val()){ core.alert(LANG_SELECT_CAT, LANG_ERROR); return false; }
            <?php } ?>

            $('form#addform').submit();
        }
    </script>

    <p style="margin-top:15px">
        <input name="add_mod" type="hidden" value="1" />
        <input name="savebtn" type="button" onclick="submitArticle()" id="add_mod" <?php if ($do == 'addarticle') { ?> value="<?php echo $_LANG['ADD_ARTICLE']; ?>" <?php } else { ?> value="<?php echo $_LANG['SAVE_CHANGES']; ?>" <?php } ?> />
        <input name="back" type="button" id="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();"/>
        <?php if ($do == 'editarticle') { ?>
            <input name="id" type="hidden" value="<?php echo $mod['id']; ?>" />
        <?php } ?>
    </p>
</form>