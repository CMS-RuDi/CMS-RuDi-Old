<form action="<?php echo $form_action; ?>" method="post" name="addform" id="addform">
    <input type="hidden" name="goadd" value="1" />
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
	<table border="0" cellspacing="0" cellpadding="6" width="100%">
            <tr>
                <td width="170"><strong><?php echo $_LANG['CAT_NAME']; ?>: </strong></td>
                <td><input name="title" type="text" id="title" class="text-input" style="width:350px" value="<?php echo $this->escape($mod['title']); ?>"/></td>
            </tr>
            <tr>
                <td valign="top"><strong><?php echo $_LANG['CAT_DESCRIPTION']; ?>: </strong></td>
                <td><textarea class="text-input" name="description" cols="1" rows="10" style="width:350px" ><?php echo $this->escape($mod['description']); ?></textarea></td>
            </tr>
	</table>
</form>
<script type="text/javascript" src="/includes/jquery/jquery.form.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#title').focus();
    });
</script>

<div class="sess_messages" style="display:none">
  <div class="message_info" id="error_mess"></div>
</div>