<?php
    global $tpl_data, $_LANG;
    extract($tpl_data);
?>

<input type="hidden" name="do" value="save_auto_config" />
<input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />

<?php foreach($fields as $fid=>$field) { ?>
    <div id="f<?php echo $fid; ?>" class="form-group">
        <label class="col-sm-5 control-label"><?php echo $field['title']; ?> <?php if (!empty($field['units'])) { echo '('. $field['units'] .')';  } ?></label>
        <div class="col-sm-7">
            <?php if ($field['type'] == 'list_db' && $field['multiple']) { ?>
                <div class="param-links">
                    <a href="javascript:void(0);" onclick="$('div#f<?php echo $fid; ?> div input:checkbox').prop('checked', true)"><?php echo $_LANG['SELECT_ALL']; ?></a> |
                    <a href="javascript:void(0);" onclick="$('div#f<?php echo $fid; ?> div input:checkbox').prop('checked', false)"><?php echo $_LANG['REMOVE_ALL']; ?></a>
                </div>
            <?php } ?>

            <?php echo $field['html']; ?>
            
            <?php if ($field['hint']) { ?>
                <div class="help-block"><?php echo $field['hint']; ?></div>
            <?php } ?>
        </div>
    </div>
    <div style="clear:both"></div>
<?php } ?>