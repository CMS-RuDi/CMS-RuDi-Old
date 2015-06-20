<form name="templform" action="/modules/mod_template/set.php" method="post">
    <select name="template" id="template" style="width:100%">
        <option value="0"><?php echo $_LANG['TEMPLATE_DEFAULT']; ?></option>
        <?php foreach ($templates as $template) { ?>
            <?php if ($template == 'admin') { continue; } ?>
            <option value="<?php echo $template; ?>" <?php if ($template == $current_template) { ?>selected="selected"<?php } ?>><?php echo $template; ?></option>
        <?php } ?>
    </select><br/>
    <input style="margin-top:5px" type="submit" value="<?php echo $_LANG['TEMPLATE_CHOOSE']; ?>"/>
</form>