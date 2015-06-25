<h3><?php echo $_LANG['AD_EXCEL_IMPORT']; ?></h3>

<?php if ($is_cat_id) { ?>
<form action="index.php?view=components&do=config&link=catalog" method="POST" enctype="multipart/form-data" name="addform">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:650px;">
        <p><strong><?php echo $_LANG['AD_CAT_BOARD']; ?>:</strong> <a href="index.php?view=components&do=config&link=catalog&opt=import_xls"><?php echo $cat['title']; ?></a></p>
        <p><?php echo $_LANG['AD_CHECK_EXCEL_FILE']; ?></p>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_EXCEL_FILE']; ?></label>
            <input type="file" class="form-control" name="xlsfile" />
            <div class="help-block"><?php echo $_LANG['AD_XLS_EXTENTION']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ENCODING']; ?></label>
            <select class="form-control" name="charset">
                <option value="cp1251" selected><?php echo $_LANG['AD_CP1251']; ?></option>
                <option value="UTF-8"><?php echo $_LANG['AD_UTF8']; ?></option>
            </select>
            <div class="help-block"><?php echo $_LANG['AD_SOFTWARE']; ?></div>
        </div>
        
        <table class="table">
            <tr>
                <td>
                    <label><?php echo $_LANG['AD_LINE_QUANTITY']; ?> (<?php echo $_LANG['AD_PIECES']; ?>)</label>
                    <div class="help-block"><?php echo $_LANG['AD_PRESCRIPTION']; ?></div>
                </td>
                <td width="100"><input type="number" class="form-control" name="xlsrows" /></td>
            </tr>
            <tr>
                <td>
                    <label><?php echo $_LANG['AD_LINE_QUANTITY']; ?> (<?php echo $_LANG['AD_LIST_NUMBER']; ?>)</label>
                </td>
                <td><input type="number" class="form-control" name="xlslist" value="1" /></td>
            </tr>
        </table>
        
        <p><?php echo $_LANG['AD_DATA_NOTE_INFO']; ?></p>
        
        <table class="table">
            <tr id="row_title">
                <td>
                    <label><?php echo $_LANG['TITLE']; ?>:</label>
                </td>
                <td width="80"><?php echo $_LANG['AD_COLUMN'];?>:</td>
                <td width="90">
                    <input type="number" id="title_col" class="form-control" onkeyup="xlsEditCol()" name="cells[title][col]" />
                </td>
                <td width="80"><?php echo $_LANG['AD_LINE'];?>:</td>
                <td width="90">
                    <input type="number" id="title_row" class="form-control" onkeyup="xlsEditRow()" name="cells[title][row]" />
                </td>
                <td width="90">
                    <label><input type="checkbox" id="ignore_title" name="cells[title][ignore]" onclick="ignoreRow('title')" value="1"/> <?php echo $_LANG['AD_TEXT']; ?>: </label>
                </td>
                <td width="200">
                    <input type="text" id="other_title" class="form-control" name="cells[title][other]"disabled="disabled" />
                </td>
            </tr>
            
            <?php
                $current = 0;
                foreach($fstruct as $key => $value) {
                    //strip special markups
                    if (mb_strstr($value, '/~h~/')) {
                        $value=str_replace('/~h~/', '', $value);
                    } else if (mb_strstr($value, '/~l~/')) {
                        $value=str_replace('/~l~/', '', $value);
                    } else {
                        $ftype='text';
                    }
                    if (mb_strstr($value, '/~m~/')) { $value=str_replace('/~m~/', '', $value); }
                    //show field inputs
                    ?>
                        <tr id="row_<?php echo $current; ?>">
                            <td><label><?php echo stripslashes($value); ?>:</label></td>
                            <td><?php echo $_LANG['AD_COLUMN'];?>:</td>
                            <td><input type="number" class="form-control" id="<?php echo $current; ?>" name="cells[<?php echo $current; ?>][col]" /></td>
                            <td><?php echo $_LANG['AD_LINE']; ?>:</td>
                            <td><input type="number" class="form-control" name="cells[<?php echo $current; ?>][row]" /></td>
                            <td><label><input type="checkbox" id="ignore_<?php echo $current; ?>" name="cells[<?php echo $current; ?>][ignore]" onclick="ignoreRow('<?php echo $current; ?>')" value="1" /> <?php echo $_LANG['AD_TEXT']; ?>: </label></td>
                            <td><input type="text" id="other_<?php echo $current; ?>" class="form-control" name="cells[<?php echo $current; ?>][other]" disabled="disabled" /></td>
                        </tr>
                    <?php
                    $current++;
                }

                if ($cat['view_type'] == 'shop') {
                    ?>
                        <tr id="row_price">
                            <td width=""><label><?php echo $_LANG['PRICE'];?>:</label></td>
                            <td><?php echo $_LANG['AD_COLUMN'];?>:</td>
                            <td><input type="number" class="form-control" name="cells[price][col]" /></td>
                            <td><?php echo $_LANG['AD_LINE']; ?>:</td>
                            <td><input type="number" class="form-control" name="cells[price][row]" /></td>
                            <td><label><input type="checkbox" id="ignore_price" name="cells[price][ignore]" onclick="ignoreRow('price')" value="1" /> <?php echo $_LANG['AD_TEXT']; ?>: </label></td>
                            <td><input type="text" id="other_price" class="form-control" name="cells[price][other]" disabled="disabled" /></td>
                        </tr>
                    <?php
                }
            ?>
        </table>
        
        <p><?php echo $_LANG['AD_OTHER_PARAMETRS']; ?>:</p>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ITEM_PUBLIC']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default active">
                    <input type="radio" name="published" checked="checked" value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="published" value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
            <div class="help-block"><?php echo $_LANG['AD_ITEM_VIEW']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALLOW_COMENTS']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default active">
                    <input type="radio" name="is_comments" checked="checked" value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="is_comments" value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <?php if ($cat['view_type'] == 'shop') { ?>
        <div class="form-group">
            <label><?php echo $_LANG['CAN_MANY']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default active">
                    <input type="radio" name="canmany" checked="checked" value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="canmany" value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
            <div class="help-block"><?php echo $_LANG['AD_PRODUCT_ORDER']; ?></div>
        </div>
        <?php } ?>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ITEMS_TAGS']; ?></label>
            <input type="text" class="form-control" name="tags" />
            <div class="help-block"><?php echo $_LANG['AD_NOT_NECESSARILY']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_IMG_FILE']; ?></label>
            <input type="file" class="form-control" name="imgfile" />
            <div class="help-block"><?php echo $_LANG['AD_NOT_NECESSARILY']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_USER']; ?></label>
            <select class="form-control" name="user_id">
                <?php echo $users_opt; ?>
            </select>
            <div class="help-block"><?php echo $_LANG['AD_USER_ALIAS']; ?></div>
        </div>
    </div>

    <div>
        <input name="cat_id" type="hidden" id="cat_id" value="<?php echo $cat_id; ?>" />
        <input name="opt" type="hidden" id="opt" value="go_import_xls" />
        
        <input type="submit" name="save" class="btn btn-primary" value="<?php echo $_LANG['AD_IMPORT']; ?>" />
        <input type="button" name="back" class="btn btn-default" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.go(-1);" />
    </div>
</form>
<?php } else { ?>
    <h4><?php echo $_LANG['AD_CHECK_RUBRIC']; ?></h4>
 
    <div style="padding:10px">
        <?php foreach ($cats as $cat) { ?>
            <div style="padding:2px;padding-left:18px;margin-left:<?php echo (($cat['NSLevel']-1)*15); ?>px;">
                <span class="fa fa-folder"></span>
                <a href="?view=components&do=config&link=catalog&opt=import_xls&cat_id=<?php echo $cat['id']; ?>"><?php echo $cat['title']; ?></a>
            </div>
        <?php } ?>
    </div>
<?php }