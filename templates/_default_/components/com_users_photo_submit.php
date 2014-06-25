<h1 class="con_heading"><?php echo $_LANG['PHOTOS_CONFIG']; ?></h1>

<script type="text/javascript">
    function togglePhoto(id){
        if ($('#delete'+id).prop('checked')){
            $('#photo'+id+' .text-input').prop('disabled', true);
            $('#photo'+id+' select').prop('disabled', true);
        } else {
            $('#photo'+id+' .text-input').prop('disabled', false);
            $('#photo'+id+' select').prop('disabled', false);
        }
    }
</script>

<form action="" method="post">
    <div id="usr_photos_upload_album">
        <table border="0" cellpadding="0" cellspacing="0">
            <?php if ($albums) { ?>
            <tr>
                <td width="23" height="30"><input type="radio" name="new_album" id="new_album_0" value="0" checked="checked" onclick="$('#description').hide();" /></td>
                <td><label for="new_album_0"><?php echo $_LANG['SAVE_TO_ALBUM']; ?>:</label></td>
                <td style="padding-left: 10px" colspan="3">
                    <select name="album_id" class="select-input">
                        <?php foreach($albums as $album) { ?>
                            <option value="<?php echo $album['id']; ?>" <?php if ($album_id == $album['id']) { ?> selected="selected"<?php } ?>><?php echo $album['title']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td width="23" height="30"><input type="radio" name="new_album" id="new_album_1" value="1" <?php if (!$albums) { ?>checked="checked"<?php } ?> onclick="$('#description').show();" /></td>
                <td><label for="new_album_1"><?php echo $_LANG['CREATE_NEW_ALBUM']; ?>:</label></td>
                <td style="padding:0px 10px">
                    <input type="text" class="text-input" name="album_title" onclick="$('#description').show();$('#new_album_1').prop('checked', true);" />
                </td>
                <td width="80"><?php echo $_LANG['SHOW']; ?>:</td>
                <td>
                    <select name="album_allow_who" id="album_allow_who">
                        <option value="all"><?php echo $_LANG['TO_ALL']; ?></option>
                        <option value="registered"><?php echo $_LANG['TO_REGISTERED']; ?></option>
                        <option value="friends"><?php echo $_LANG['TO_MY_FRIEND']; ?></option>
                    </select>
                </td>
            </tr>
            <tr id="description" <?php if ($albums) { ?>style="display:none;"<?php } ?> >
                <td width="23" height="30"></td>
                <td><label for="description"><?php echo $_LANG['ALBUM_DESCRIPTION']; ?>:</label></td>
                <td style="padding-left: 10px" colspan="3">
                    <textarea name="description" class="text-input" style="width:488px; height:45px;"></textarea>
                </td>
            </tr>
        </table>
    </div>

    <div class="usr_photos_submit_list">
        <?php foreach($photos as $photo) { ?>
        <div id="photo<?php echo $photo['id']; ?>" class="usr_photos_submit_one">
            <div class="float_bar">
                <table>
                    <tr>
                        <td width="20"><input type="checkbox" name="delete[]" value="<?php echo $photo['id']; ?>" id="delete<?php echo $photo['id']; ?>" onclick="togglePhoto(<?php echo $photo['id']; ?>)"/></td>
                        <td><label for="delete<?php echo $photo['id']; ?>"><?php echo $_LANG['DELETE']; ?></label></td>
                    </tr>
                </table>
            </div>
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="120" height="110">
                        <div class="ph_thumb"><img src="/images/users/photos/small/<?php echo $photo['imageurl']; ?>" /></div>
                    </td>
                    <td>

                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="100" height="30"><?php echo $_LANG['TITLE']; ?>:</td>
                                <td><input type="text" name="title[<?php echo $photo['id']; ?>]" value="<?php echo $this->escape($photo['title']); ?>" class="text-input" /></td>
                            </tr>
                            <tr>
                                <td height="30"><?php echo $_LANG['DESCRIPTION']; ?>:</td>
                                <td><input type="text" name="desc[<?php echo $photo['id']; ?>]" value="<?php echo $this->escape($photo['description']); ?>" class="text-input" /></td>
                            </tr>
                            <tr>
                                <td height="30"><?php echo $_LANG['SHOW']; ?>:</td>
                                <td>
                                    <select name="allow[<?php echo $photo['id']; ?>]">
                                        <option value="all" <?php if ($photo['allow_who'] == 'all') { ?>selected="selected"<?php } ?>><?php echo $_LANG['TO_ALL']; ?></option>
                                        <option value="registered" <?php if ($photo['allow_who'] == 'registered') { ?>selected="selected"<?php } ?>><?php echo $_LANG['TO_REGISTERED']; ?></option>
                                        <option value="friends" <?php if ($photo['allow_who'] == 'friends') { ?>selected="selected"<?php } ?>><?php echo $_LANG['TO_MY_FRIEND']; ?></option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <?php } ?>
    </div>
    <div id="usr_photos_submit_btn">
    	<input type="hidden" name="is_edit" value="<?php echo $is_edit; ?>" />
        <input type="submit" name="submit" value="<?php echo $_LANG['SAVE']; ?>" /> <?php echo $_LANG['AND_GO_TO_ALBUM']; ?>
    </div>
</form>