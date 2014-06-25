<div class="con_heading"><?php echo $_LANG['CREATE_BLOG']; ?></div>

<p><strong><?php echo $_LANG['BLOG']; ?></strong> <?php echo $_LANG['BLOG_DESCRIPTION']; ?></p>

<form style="margin-top:15px" action="" method="post" name="addform">
    <div style="background-color:#EBEBEB;padding:10px;width:550px">
        <table border="0" cellspacing="0" cellpadding="4">
            <tr>
                <td width="180"><strong><?php echo $_LANG['BLOG_TITLE']; ?>: </strong></td>
                <td><input name="title" type="text" id="title" class="text-input" size="40" /></td>
            </tr>
            <tr>
                <td><strong><?php echo $_LANG['BLOG_TYPE']; ?>: </strong></td>
                <td>
                    <select name="ownertype" id="ownertype">
                        <option value="single" selected><?php echo $_LANG['PERSONAL']; ?> <?php if ($is_restrictions && $cfg['min_karma_private'] > 0) { ?>(<?php echo $_LANG['BLOG_KARMA_NEED']; ?> <?php echo $cfg['min_karma_private']; ?>)<?php } ?></option>
                        <option value="multi" ><?php echo $_LANG['COLLECTIVE']; ?> <?php if ($is_restrictions && $cfg['min_karma_public'] > 0) { ?>(<?php echo $_LANG['BLOG_KARMA_NEED']; ?> <?php echo $cfg['min_karma_public']; ?>)<?php } ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php echo $_LANG['SHOW_BLOG']; ?>:</strong></td>
                <td>
                    <select name="allow_who" id="allow_who">
                        <option value="all" selected="selected"><?php echo $_LANG['TO_ALL']; ?></option>
                        <option value="friends"><?php echo $_LANG['TO_MY_FRIENDS']; ?></option>
                        <option value="nobody"><?php echo $_LANG['TO_ONLY_ME']; ?></option>
                    </select>
               </td>
            </tr>
        </table>
    </div>
    <p style="margin-top:20px">
        <input name="goadd" type="submit" id="goadd" value="<?php echo $_LANG['CREATE_BLOG']; ?>" />
        <input name="cancel" type="button" onclick="window.history.go(-1)" value="<?php echo $_LANG['CANCEL']; ?>" />
    </p>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        $('#title').focus();
    });
</script>