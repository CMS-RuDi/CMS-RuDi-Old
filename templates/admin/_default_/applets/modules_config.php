<h3><?php echo $module_title; ?></h3>

<form action="index.php?view=modules&do=save_auto_config&id=<?php echo $id; ?>" method="post" name="optform" target="_self" id="optform">
    <div class="panel panel-default" style="width:650px;">
        <div class="panel-body">
            <?php if (isset($formGenHtml)) { ?>
                <?php echo $formGenHtml; ?>
            <?php } else { ?>
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_MODULE_TEMPLATE']; ?></label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" value="<?php echo $cfg['tpl']; ?>" />
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="panel-footer">
            <input type="submit" name="save" class="btn btn-primary" value="<?php echo $_LANG['SAVE']; ?>" />
        </div>
    </div>
</form>

<script type="text/javascript">
    function submitModuleConfig(){
        $('#optform').submit();
    }
</script>