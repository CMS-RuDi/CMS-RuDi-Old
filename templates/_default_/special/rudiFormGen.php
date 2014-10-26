<?php

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


<div class="rudi_form">
    <?php if ($insert_token) { ?>
        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <?php } ?>
<?php
    foreach ($data as $dat) {
        echoRudiField($dat);
    }
?>
</div>