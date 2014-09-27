<div class="con_heading">
    <?php if ($do == 'add_item') { ?><?php echo $_LANG['ADD_ITEM']; ?><?php } ?>
    <?php if ($do == 'edit_item') { ?><?php echo $_LANG['EDIT_ITEM']; ?><?php } ?>
</div>

<div id="configtabs">
    <div id="form">
        <form id="add_form" method="post" action="/catalog/<?php echo $cat_id; ?>/submit.html" enctype="multipart/form-data">
        <table cellpadding="5" cellspacing="0" style="margin-bottom:10px" width="100%">
            <tr>
                <td width="210">
                    <strong><?php echo $_LANG['TITLE']; ?>:</strong>
                </td>
                <td><input type="text" name="title" id="title" class="text-input" value="<?php echo $this->escape($item['title']); ?>" style="width:300px"/></td>
            </tr>
            <?php if ($is_admin) { ?>
            <tr>
                <td width="210">
                    <strong><?php echo $_LANG['CAT']; ?>:</strong>
                </td>
                <td><select style="width:300px" class="text-input" name="new_cat_id" id="cat_id" ><?php echo $cats; ?></select></td>
            </tr>
            <?php } ?>
            <tr>
                <td width="">
                    <strong><?php echo $_LANG['IMAGE']; ?>:</strong>
                </td>
                <td>
                    <?php if ($do == 'edit_item' && $item['imageurl']) { ?>
                        <div style="margin-bottom:4px;">
                            <a href="/images/catalog/<?php echo $item['imageurl']; ?>" target="_blank"><?php echo $item['imageurl']; ?></a>
                        </div>
                    <?php } ?>
                    <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td><input name="imgfile" type="file" id="imgfile" style="width:300px" class="text-input" /></td>
                            <?php if ($do == 'edit_item' && $item['imageurl']) { ?>
                                <td style="padding-left:15px">
                                    <label>
                                        <input type="checkbox" value="1" name="delete_img" />
                                        <?php echo $_LANG['DELETE']; ?>
                                    </label>
                                </td>
                            <?php } ?>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php if ($cat['view_type'] == 'shop') { ?>
            <tr>
                <td width="">
                    <strong><?php echo $_LANG['PRICE']; ?>:</strong>
                </td>
                <td>
                    <input type="text" class="text-input" name="price" value="<?php echo $this->escape($item['price']); ?>" style="width:300px"/>
                </td>
            </tr>
            <tr>
                <td width="">
                    <strong><?php echo $_LANG['CAN_MANY']; ?>:</strong>
                </td>
                <td>
                    <label><input type="radio" name="canmany" value="1" <?php if ($item['canmany']) { ?>checked="checked"<?php } ?>> <?php echo $_LANG['YES']; ?> </label>
                    <label><input type="radio" name="canmany" value="0" <?php if (!$item['canmany']) { ?>checked="checked"<?php } ?>> <?php echo $_LANG['NO']; ?> </label>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td width="">
                    <strong><?php echo $_LANG['TAGS']; ?>:</strong><br/>
                    <span class="hint"><?php echo $_LANG['KEYWORDS']; ?></span>
                </td>
                <td>
                    <input type="text" name="tags" class="text-input" value="<?php echo $this->escape($item['tags']); ?>" style="width:300px"/>
                </td>
            </tr>
        <?php foreach($fields as $field) { ?>
            <tr>
                <?php if ($field['ftype'] == 'link' || $field.ftype == 'text') { ?>
                <td valign="top">
                    <strong><?php echo $field['title']; ?>:</strong>
                    <?php if ($field['ftype'] == 'link') { ?> <br/><span class="hint"><?php echo $_LANG['TYPE_LINK']; ?></span><?php } ?>
                    <?php if ($field['makelink']) { ?> <br/><span class="hint"><?php echo $_LANG['COMMA_SEPARATE']; ?></span><?php } ?>
                </td>
                <td>
                    <input style="width:300px" name="fdata[<?php echo $id; ?>]" type="text" class="text-input" value="<?php if ($field['value']) { ?><?php echo $this->escape($field['value']); ?><?php } ?>"/>
                </td>
                <?php } else { ?>
                    <td valign="top"><strong><?php echo $field['title']; ?>:</strong></td>
                    <td>
                        <?php cmsCore::insertEditor('fdata['. $id .']', $field['value'], 300, '98%'); ?>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
        <?php if ($is_admin) { ?>
            <tr>
                <td width="">
                    <strong><?php echo $_LANG['SEO_KEYWORDS']; ?>:</strong><br/>
                    <span class="hint"><?php echo $_LANG['SEO_KEYWORDS_HINT']; ?></span>
                </td>
                <td>
                    <input type="text" name="meta_keys" class="text-input" value="<?php echo $this->escape($item['meta_keys']); ?>" style="width:300px"/>
                </td>
            </tr>
            <tr>
                <td width="">
                    <strong><?php echo $_LANG['SEO_DESCRIPTION']; ?>:</strong>
                </td>
                <td>
                    <input type="text" name="meta_desc" class="text-input" value="<?php echo $this->escape($item['meta_desc']); ?>" style="width:300px"/>
                </td>
            </tr>
            <tr>
                <td width="">
                    <strong><?php echo $_LANG['IS_COMMENTS']; ?>:</strong>
                </td>
                <td>
                    <label><input type="radio" name="is_comments" value="1" <?php if ($item['is_comments']) { ?>checked="checked"<?php } ?>> <?php echo $_LANG['YES']; ?> </label>
                    <label><input type="radio" name="is_comments" value="0" <?php if (!$item['is_comments']) { ?>checked="checked"<?php } ?>> <?php echo $_LANG['NO']; ?> </label>
                </td>
            </tr>
        <?php } ?>
        </table>
        <?php if ($cfg['premod'] && !$is_admin) { ?>
            <p style="margin-top:15px;color:red">
                <?php echo $_LANG['ITEM_PREMOD_NOTICE']; ?>
            </p>
        <?php } ?>
        <p style="margin-top:15px">
            <input type="hidden" name="opt" value="<?php if ($do == 'add_item') { ?>add<?php } else { ?>edit<?php } ?>" />
            <?php if ($do == 'edit_item') { ?>
                <input type="hidden" id="item_id" name="item_id" value="<?php echo $item['id']; ?>" />
            <?php } ?>
            <input type="submit" name="submit" value="<?php echo $_LANG['SAVE']; ?>" style="font-size:18px" />
            <input type="button" name="back" value="<?php echo $_LANG['CANCEL']; ?>"  style="font-size:18px" onClick="window.history.go(-1)" />
        </p>
        </form>
    </div>
</div>