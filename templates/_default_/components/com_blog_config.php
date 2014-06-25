<form action="<?php echo $form_action; ?>" method="post" name="cfgform" id="cfgform" style="margin-top:5px">
    <table width="100%" border="0" cellpadding="4" cellspacing="0">
        <tr>
          <td width="180"><strong><?php echo $_LANG['BLOG_TITLE']; ?>: </strong></td>
          <td><input name="title" type="text" id="title" class="text-input" value="<?php echo $this->escape($blog['title']); ?>" style="width:360px"/></td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['SHOW_BLOG']; ?>:</strong></td>
            <td>
                <select name="allow_who" id="allow_who" style="width:360px" class="text-input">
                    <option value="all" <?php if ($blog['allow_who'] == 'all') { ?> selected="selected" <?php } ?>><?php echo $_LANG['TO_ALL']; ?></option>
                    <option value="friends" <?php if ($blog['allow_who'] == 'friends') { ?> selected="selected" <?php } ?>><?php echo $_LANG['TO_MY_FRIENDS']; ?></option>
                    <option value="nobody" <?php if ($blog['allow_who'] == 'nobody') { ?> selected="selected" <?php } ?>><?php echo $_LANG['TO_ONLY_ME']; ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['SHOW_CAT']; ?></strong>: </td>
            <td>
                <select name="showcats" id="showcats" class="text-input">
                    <option value="1" <?php if ($blog['showcats'] == 1) { ?> selected="selected" <?php } ?>><?php echo $_LANG['YES']; ?></option>
                    <option value="0" <?php if ($blog['showcats'] == 0) { ?> selected="selected" <?php } ?>><?php echo $_LANG['NO']; ?></option>
                </select>
            </td>
        </tr>
    </table>
    <table width="100%" border="0" cellpadding="4" cellspacing="0">
        <tr>
            <td width="180"><strong><?php echo $_LANG['BLOG_TYPE']; ?>: </strong></td>
            <td>
                <select name="ownertype" id="ownertype" onchange="selectOwnerType()" style="width:360px" class="text-input">
                    <option value="single" <?php if ($blog['ownertype'] == 'single') { ?> selected <?php } ?>><?php echo $_LANG['PERSONAL']; ?> <?php if ($is_restrictions && $cfg['min_karma_private'] > 0) { ?>(<?php echo $_LANG['BLOG_KARMA_NEED']; ?> <?php echo $cfg['min_karma_private']; ?>)<?php } ?></option>
                    <option value="multi" <?php if ($blog['ownertype'] == 'multi') { ?> selected <?php } ?>><?php echo $_LANG['COLLECTIVE']; ?> <?php if ($is_restrictions && $cfg['min_karma_public'] > 0) { ?>(<?php echo $_LANG['BLOG_KARMA_NEED']; ?> <?php echo $cfg['min_karma_public']; ?>)<?php } ?></option>
                </select>
            </td>
        </tr>
    </table>
    <table width="100%" border="0" cellpadding="4" cellspacing="0" id="multiblogcfg" style="display:<?php if ($blog['ownertype'] == 'single') { ?>none;<?php } else { ?>block;<?php } ?>">
        <tr>
            <td width="180"><strong><?php echo $_LANG['PREMODER_POST']; ?>: </strong></td>
            <td>
                <select name="premod" id="premod" style="width:360px" class="text-input">
                    <option value="1" <?php if ($blog['premod'] == 1) { ?> selected <?php } ?>><?php echo $_LANG['ON']; ?></option>
                    <option value="0" <?php if ($blog['premod'] == 0) { ?> selected <?php } ?>><?php echo $_LANG['OFF']; ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['WHO_CAN_WRITE_TO_BLOG']; ?>: </strong></td>
            <td>
                <select name="forall" id="forall" onchange="selectAuthorsType()" style="width:360px" class="text-input">
                    <option value="1" <?php if ($blog['forall'] == 1) { ?> selected <?php } ?>><?php echo $_LANG['ALL_USERS']; ?></option>
                    <option value="0" <?php if ($blog['forall'] == 0) { ?> selected <?php } ?>><?php echo $_LANG['LIST_USERS']; ?></option>
                </select>
            </td>
        </tr>
    </table>
    
    <table width="100%" border="0" cellspacing="0" cellpadding="4" id="multiuserscfg" style="margin:5px 0;display: <?php if ($blog['ownertype'] == 'single' || $blog['forall']) { ?>none;<?php } else { ?>table;<?php } ?>">
        <tr>
            <td align="center" valign="top">
                <strong><?php echo $_LANG['CAN_WRITE_TO_BLOG']; ?>: </strong><br/>
                <select name="authorslist[]" size="15" multiple id="authorslist" style="width:200px" class="text-input">
                    <?php echo $authors_list; ?>
                </select>
            </td>
            <td align="center">
                <div><input name="author_add" type="button" id="author_add" value="&lt;&lt;"></div>
                <div><input name="author_remove" type="button" id="author_remove" value="&gt;&gt;" style="margin-top:4px"></div>
            </td>
            <td align="center" valign="top">
                <strong><?php echo $_LANG['ALL_USERS']; ?>:</strong><br/>
                <select name="userslist" size="15" multiple id="userslist" style="width:200px" class="text-input">
                    <?php echo $users_list; ?>
                </select>
            </td>
        </tr>
    </table>
    
    <input type="hidden" name="goadd" value="1" />
    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
</form>

<div class="sess_messages" style="display:none">
    <div class="message_info" id="error_mess"></div>
</div>
<script type="text/javascript" src="/includes/jquery/jquery.form.js"></script>

<script type="text/javascript">
    $().ready(function() {
        $('#author_remove').click(function() {
            return !$('#authorslist option:selected').remove().appendTo('#userslist');
        });
        $('#author_add').click(function() {
            return !$('#userslist option:selected').remove().appendTo('#authorslist');
        });
    });
    
    function selectOwnerType(){
        var ot = $('#ownertype').val();
        if (ot == 'multi') {
            $('#multiblogcfg').show();
            if ($('#forall').val()==0){
                $('#multiuserscfg').show();
            }
        } else {
            $('#multiblogcfg').hide();
            $('#multiuserscfg').hide();
        }
    }
    function selectAuthorsType(){
        var ot = $('#forall').val();
        if (ot == '0') {
            $('#multiuserscfg').show();
        } else {
            $('#multiuserscfg').hide();
        }
    }
</script>
