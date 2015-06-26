<fieldset style="width:610px;">
    <legend><?php echo $plugin_title; ?></legend>
    
    <?php if (!$config && empty($plugin_cfg_fields) && !$xml_file_exist) { ?>
        <p><?php echo $_LANG['AD_PLUGIN_DISABLE']; ?>.</p>
        <p><a href="javascript:window.history.go(-1);"><?php echo $_LANG['BACK']; ?></a></p>
    <?php } else { ?>
        <form action="index.php?view=plugins&do=save_config&plugin=<?php echo $plugin_name; ?>" method="POST">
            <?php if (!empty($plugin_cfg_fields)) { ?>
                <div style="width:610px;">
                    <?php echo $form_gen_form; ?>
                </div>
            <?php } else if ($xml_file_exist) { ?>
                <?php echo $form_gen_form; ?>
            <?php } else { ?>
                <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
                    <table class="proptable" width="605" cellpadding="8" cellspacing="0" border="0">
                        <?php foreach ($config as $field => $value) { ?>
                            <tr>
                                <td width="150"><strong><?php echo cmsCore::getArrVal($_LANG, mb_strtoupper($field), $field); ?>:</strong></td>
                                <td><input type="text" style="width:90%" name="config[<?php echo $field; ?>]" value="<?php echo $this->escape($value); ?>" /></td>
                            </tr>
                        <?php } ?>
                    </table>
            <?php } ?>
            
            <?php if (!$xml_file_exist) { ?>
                <input type="hidden" name="do" value="save_config" />
                <div style="margin-top:6px;">
                    <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
                    <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.go(-1)" />
                </div>
            <?php } ?>
        </form>
    <?php } ?>
</fieldset>