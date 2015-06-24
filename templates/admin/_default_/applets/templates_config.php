<form action="/admin/index.php?view=templates&template=<?php echo $template; ?>&do=save_config" method="post" style="width:650px;margin-bottom:30px">
    <?php echo $form_gen_form; ?>
    <div>
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();" />
    </div>
</form>