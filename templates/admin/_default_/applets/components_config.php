<form action="index.php?view=components&do=save_config&id=<?php echo $com_id; ?>" method="POST">
    <div style="width:650px;"><?php echo $formConfig; ?></div>
    <div style="margin-top:6px;">
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.go(-1)" />
    </div>
</form>