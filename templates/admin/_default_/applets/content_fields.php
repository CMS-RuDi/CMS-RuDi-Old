<?php foreach ($fields as $field) { ?>
    <div class="form-group row">
        <label class="col-lg-2 text-right"><?php echo $field['title']; ?></label>

        <div class="col-lg-10">
            <?php
            switch ($field['type']) {
                case 'text':
                    ?>
                    <input type="text" class="form-control w750" name="fields[<?php echo $field['name'] ?>]" value="<?php echo $this->escape(isset($values[$field['name']]) ? $values[$field['name']] : (!empty($field['default']['value']) ? $field['default']['value'] : '')); ?>" />
                    <?php
                    break;
                case 'html':
                    ?>
                    <textarea class="form-control w750" name="fields[<?php echo $field['name'] ?>]"><?php echo $this->escape(isset($values[$field['name']]) ? $values[$field['name']] : (!empty($field['default']['value']) ? $field['default']['value'] : '')); ?></textarea>
                    <?php
                    break;
                case 'select':
                    ?>
                    <select class="form-control w750" name="fields[<?php echo $field['name'] ?>]">
                        <?php foreach ($field['items']['default']['items'] as $option) { ?>
                        <option value="<?php $option; ?>" <?php if (cmsCore::getArrVal($values, $field['name']) == $option) { ?>selected="selected"<?php } ?>><?php $option; ?></option>
                        <?php } ?>
                    </select>
                    <?php
                    break;
                default:
                    include('content_fields_'. $field['type'] .'.php');
                    break;
            }
            ?>
        </div>
    </div>
<?php } ?>