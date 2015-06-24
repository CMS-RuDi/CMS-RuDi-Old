<div class="panel panel-<?php echo $class; ?>" style="width:650px;">
    <div class="panel-heading">
        <h4>Тема: <?php echo $item['title']; ?></h4>
        <div><?php echo $item['msg']; ?></div>
    </div>
    
    <div class="panel-body">
        <?php if (!empty($item['msgs'])) {
            foreach ($item['msgs'] as $msg) {
        ?>
            <div style="text-align: <?php if (!empty($msg['support'])) { echo 'right'; } else { echo 'left'; } ?>;">
                <span>
                    <i class="fa fa-calendar-o"></i>
                    <?php echo $msg['pubdate']; ?>
                </span>
                <?php if (!empty($msg['support'])) { ?>
                    <span>
                        <i class="fa fa-user"></i>
                        <?php echo $msg['support']; ?>
                    </span>
                <?php } ?>
            </div>
            <div class="alert alert-warning" style="margin-<?php if (!empty($msg['support'])) { echo 'left'; } else { echo 'right'; } ?>: 50px;">
                <?php echo $msg['msg']; ?>
            </div>
        <?php } } ?>
    </div>
    
    <div class="panel-footer">
        <?php if ($item['msg_count'] > 1 && $item['status'] != 3) { ?>
            <form id="ticket_msg_add" action="index.php?view=tickets&do=submit_msg" method="post">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_TICKET_MSG']; ?></label>
                    <textarea class="form-control" name="msg" style="height: 200px;"></textarea>
                </div>

                <div style="margin-top:5px">
                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>" />
                    <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SEND']; ?>" />
                    <input type="button" class="btn btn-warning" value="<?php echo $_LANG['AD_TICKET_CLOSE']; ?>" onclick="window.location.href='index.php?view=tickets&do=close_ticket&id=<?php echo $item['id']; ?>';return false;" />
                    <input type="button" class="btn btn-danger" value="<?php echo $_LANG['DELETE']; ?>" onclick="jsmsg('<?php echo $_LANG['AD_TICKET_DELETE']; ?>', '?view=tickets&do=delete&id=<?php echo $item['id']; ?>');" />
                    <input type="button" class="btn btn-default" value="<?php echo $_LANG['BACK']; ?>" onclick="window.location.href='index.php?view=tickets';return false;" />
                </div>
            </form>
        <?php } else { ?>
            <div>
                <?php if ($item['status'] != 3) { ?>
                    <input type="button" class="btn btn-warning" value="<?php echo $_LANG['AD_TICKET_CLOSE']; ?>" onclick="window.location.href='index.php?view=tickets&do=close_ticket&id=<?php echo $item['id']; ?>';return false;" />
                <?php } ?>
                <input type="button" class="btn btn-danger" value="<?php echo $_LANG['DELETE']; ?>" onclick="jsmsg('<?php echo $_LANG['AD_TICKET_DELETE']; ?>', '?view=tickets&do=delete&id=<?php echo $item['id']; ?>');" />
                <input type="button" class="btn btn-default" value="<?php echo $_LANG['BACK']; ?>" onclick="window.location.href='index.php?view=tickets';return false;" />
            </div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('body').animate({ scrollTop: $('#ticket_msg_add').offset().top }, 1100);
    });
</script>