<p>
    <strong><?php echo $_LANG['CLUBS']; ?></strong> <?php echo $_LANG['CLUBS_DESC']; ?>
</p>
<?php if ($can_create) { ?>
    <script type="text/javascript" src="/includes/jquery/jquery.form.js"></script>
    <form action="/clubs/create.html" method="post" id="create_club">
        <input type="hidden" name="create" value="1" />
        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
        <table border="0" cellspacing="0" cellpadding="0" align="left">
            <tr>
                <td width="120">
                    <strong><?php echo $_LANG['CLUB_NAME']; ?>: </strong>
                </td>
                <td>
                    <input name="title" type="text" id="title" class="text-input" style="width:300px" />
                </td>
            </tr>
            <tr>
                <td><strong><?php echo $_LANG['CLUB_TYPE']; ?>: </strong></td>
                <td>
                    <select name="clubtype" id="clubtype" style="width:300px">
                        <option value="public"><?php echo $_LANG['PUBLIC']; ?> (public)</option>
                        <option value="private"><?php echo $_LANG['PRIVATE']; ?> (private)</option>
                    </select>
                </td>
            </tr>
        </table>
    </form>

    
    <script type="text/javascript">
        $(document).ready(function(){
            $('#title').focus();
        });
    </script>
    
<?php } ?>
<div class="sess_messages" <?php if (!$last_message) { ?>style="display:none"<?php } ?>>
    <div class="message_info" id="error_mess"><?php echo $last_message; ?></div>
</div>