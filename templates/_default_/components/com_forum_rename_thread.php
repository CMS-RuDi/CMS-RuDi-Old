<form action="/forum/renamethread<?php echo $thread['id']; ?>.html" method="POST" id="renamethread_form">
    <input type="hidden" name="gorename" value="1" />
    <table border="0" cellpadding="5" width="100%">
        <tr>
            <td valign="top"><?php echo $_LANG['THREAD_TITLE']; ?>:</td>
            <td valign="top">
                <input type="text" size="45" value="<?php echo $this->escape($thread['title']); ?>" name="title" id="title" class="text-input" />
            </td>
        </tr>
        <tr>
            <td valign="top"><?php echo $_LANG['DESCRIPTION']; ?>:</td>
            <td valign="top">
                <input type="text" size="45" value="<?php echo $this->escape($thread['description']); ?>" name="description" class="text-input" />
            </td>
        </tr>
    </table>
</form>
<script type="text/javascript" src="/includes/jquery/jquery.form.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#title').focus();
    });
</script>