<?php
global $_LANG;

function echoRudiField($field) {
    if (isset($field['before'])) { echo $field['before']; }
    
    if (isset($field['fields'])) {
        foreach ($field['fields'] as $f) {
            echoRudiField($f);
        }
    } else {
        echo '<div class="form-group">';
        
        if (!empty($field['title'])) {
            if ($field['type'] == 'radio') {
                echo '<div><label>'. $field['title'] .'</label></div>';
            } else {
                echo '<label'. ($field['type'] == 'btn_yes_no' ? ' style="width:450px"' : '') .'>'. $field['title'] .'</label>';
            }
        }
        
        if (!empty($field['html'])) {
            echo $field['html'];
        }
        
        if (isset($field['options'])) {
            foreach ($field['options'] as $option) {
                echo '<label style="margin-right:10px;">'. $option['html'] .' '. $option['title'] .'</label>';
            }
        }
        
        if (!empty($field['description'])) {
            echo '<div class="help-block">'. $field['description'] .'</div>';
        }
        
        echo '</div>';
    }
    
    if (isset($field['after'])) { echo $field['after']; }
}

?>

<div class="modal fade" id="tplCfgModal" tabindex="-1" role="dialog" aria-labelledby="tplCfgModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="tplCfgModalLabel"><?php echo $_LANG['AD_TEMPLATE_CONFIG']; ?></h4>
            </div>
            <div class="modal-body" style="padding-left:50px;padding-right:50px;">
                <?php
                    foreach ($data as $dat) {
                        echoRudiField($dat);
                    }
                ?>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" value="<?php echo $_LANG['SAVE']; ?>" />
            </div>
        </div>
    </div>
</div>