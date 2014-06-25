<form enctype="multipart/form-data" action="/forum/reloadfile<?php echo $file['id']; ?>.html" method="POST" id="reload_file">
    <input name="goreload" type="hidden" value="1" />
    <div style="margin: 10px 5px;">
        <div class="forum_fa_desc">
            <div><strong><?php echo $_LANG['MAX_SIZE_FILE']; ?>:</strong> <?php echo $cfg['fa_size']; ?> <?php echo $_LANG['KBITE']; ?>.</div>
            <div><strong><?php echo $_LANG['MUST_FILE_TYPE']; ?>:</strong> <?php echo $cfg['fa_ext']; ?></div>
        </div>
        <input type="file" name="fa[]" size="30" />
    </div>
</form>
<div class="sess_messages" style="display:none">
  <div class="message_info" id="error_mess"></div>
</div>
<script type="text/javascript" src="/includes/jquery/jquery.form.js"></script>
