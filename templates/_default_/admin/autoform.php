<?php

    global $tpl_data, $_LANG;
    extract($tpl_data);

?>

<input type="hidden" name="do" value="save_auto_config" />

<div class="panel panel-default" style="width:650px;">
    <div class="panel-body">
        <table class="table">
        <?php foreach($fields as $fid=>$field) { ?>
            <tr id="f<?php echo $fid; ?>">
                <td width="260" style="border:0;">
                    <label><?php echo $field['title']; ?> <?php if (!empty($field['units'])) { echo '('. $field['units'] .')';  } ?></label>
                    <?php if ($field['hint']) { ?>
                        <div class="help-block"><?php echo $field['hint']; ?></div>
                    <?php } ?>
                </td>
                <td style="border:0;">
                    <?php if ($field['type'] == 'list_db' && $field['multiple']) { ?>
                        <div class="param-links">
                            <a href="javascript:void(0);" onclick="$('tr#f<?php echo $fid; ?> td input:checkbox').prop('checked', true)"><?php echo $_LANG['SELECT_ALL']; ?></a> |
                            <a href="javascript:void(0);" onclick="$('tr#f<?php echo $fid; ?> td input:checkbox').prop('checked', false)"><?php echo $_LANG['REMOVE_ALL']; ?></a>
                        </div>
                    <?php } ?>
                    
                    <?php echo $field['html']; ?>
                </td>
            </tr>
        <?php } ?>
        </table>
    </div>
</div>

<div class="params-buttons">
    <input type="submit" name="save" class="btn btn-primary" value="<?php echo $_LANG['SAVE']; ?>" />
</div>

<script type="text/javascript">
    function submitModuleConfig(){
        $('#optform').submit();
    }
</script>