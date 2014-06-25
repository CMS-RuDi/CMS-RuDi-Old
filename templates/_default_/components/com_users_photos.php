<?php if (($my_profile || $is_admin) && $album_type == 'private') { ?>
    <div class="float_bar">
        <?php if ($my_profile) { ?>
            <a href="/users/addphoto.html" class="usr_photo_add"><?php echo $_LANG['ADD_PHOTO']; ?></a>
        <?php } ?>
        <a href="javascript:void(0)" onclick="$('#usr_photos_upload_album').show();" class="usr_edit_album"><span class="ajaxlink"><?php echo $_LANG['EDIT_ALBUM']; ?></span></a>
        <a href="/users/<?php echo $user_id; ?>/delalbum<?php echo $album['id']; ?>.html" onclick="if(!confirm('<?php echo $_LANG['DELETE_ALBUM_CONFIRM']; ?>')){ return false; }" class="usr_del_album"><span class="ajaxlink"><?php echo $_LANG['DELETE_ALBUM']; ?></span></a>
    </div>
<?php } ?>

<div class="con_heading">
    <a href="<?php echo cmsUser::getProfileURL($usr['login']); ?>"><?php echo $usr['nickname']; ?></a> &rarr; <?php echo $page_title; ?>
</div>
<?php if (($my_profile || $is_admin) && $album_type == 'private') { ?>
    <div id="usr_photos_upload_album" style="display:none;">
	<form action="/users/<?php echo $usr['id']; ?>/editalbum<?php echo $album['id']; ?>.html" method="post">
        <table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><label for="album_title"><?php echo $_LANG['ALBUM_TITLE']; ?>:</label></td>
            <td><input type="text" class="text-input" name="album_title" value="<?php echo $this->escape($album['title']); ?>" /></td>
            <td><?php echo $_LANG['SHOW']; ?>:
                    <select name="album_allow_who" id="album_allow_who">
                       <option value="all" <?php if ($album['allow_who'] == 'all') { ?>selected="selected"<?php } ?>><?php echo $_LANG['EVERYBODY']; ?></option>
                       <option value="registered" <?php if ($album['allow_who'] == 'registered') { ?>selected="selected"<?php } ?>><?php echo $_LANG['REGISTERED']; ?></option>
                       <option value="friends" <?php if ($album['allow_who'] == 'friends') { ?>selected="selected"<?php } ?>><?php echo $_LANG['MY_FRIENDS']; ?></option>
                    </select>
            </td>
          </tr>
          <tr>
            <td><label for="description"><?php echo $_LANG['ALBUM_DESCRIPTION']; ?>:</label></td>
            <td colspan="2"><textarea name="description" style="width:465px; height:45px;"><?php echo $album['description']; ?></textarea></td>
          </tr>
        </table>
        <div class="usr_photo_sel_bar bar">
           <input type="submit" name="save_album" value="<?php echo $_LANG['SAVE']; ?>"/>
           <input name="Button" type="button" value="<?php echo $_LANG['CANCEL']; ?>" onclick="$('#usr_photos_upload_album').hide();"/>
        </div>
      </form>
    </div>
<?php } ?>
<?php if ($album_type == 'public') { ?>
    <div class="usr_photos_notice"><?php echo $_LANG['IS_PUBLIC_ALBUM']; ?> <a href="<?php if (!$album['NSDiffer']) { ?>/photos/<?php echo $album['id']; ?><?php } else { ?>/clubs/photoalbum<?php echo $album['id']; ?><?php } ?>"><?php echo $_LANG['ALL_PUBLIC_PHOTOS']; ?></a></div>
<?php } ?>
<?php if ($album_type == 'private' && $album['description']) { ?>
    <div id="usr_photos_upload_album"><?php echo nl2br($album['description']); ?></div>
<?php } ?>
<?php if ($photos) { ?>
    <?php if (($is_admin || $my_profile) && $album_type == 'private') { ?>
    <form action="/users/<?php echo $user_id; ?>/photos/editlist" method="post">
        <input type="hidden" name="album_id" value="<?php echo $album['id']; ?>" />
        <script type="text/javascript">

            function toggleButtons(){
                var is_sel = $('.photo_id:checked').length;
                if (is_sel > 0) {
                    $('#edit_btn, #delete_btn').prop('disabled', false);
                } else {
                    $('#edit_btn, #delete_btn').prop('disabled', true);
                }
            }

        </script>
    <?php } ?>

        <table width="" cellpadding="0" cellspacing="0" border="0">

            <?php $maxcols = 7; ?>
            <?php $col = 1; ?>

            <?php foreach($photos as $photo) { ?>
                <?php if ($col == 1) { ?> <tr> <?php } ?>
                <td valign="top" width="">
                    <div class="usr_photo_thumb">
                        <a class="usr_photo_link" href="<?php echo $photo['url']; ?>" title="<?php echo $this->escape($photo['title']); ?>">
                            <img border="0" src="<?php echo $photo['file']; ?>" alt="<?php echo $this->escape($photo['title']); ?>"/>
                        </a>
                        <div>
                            <span class="usr_photo_date"><?php echo $photo['fpubdate']; ?></span>
                            <span class="usr_photo_hits"><strong><?php echo $_LANG['HITS']; ?>:</strong> <?php echo $photo['hits']; ?></span>
                        </div>
                        <?php if (($is_admin || $my_profile) && $album_type == 'private') { ?>
                            <input type="checkbox" name="photos[]" class="photo_id" value="<?php echo $photo['id']; ?>" onclick="toggleButtons()" />
                        <?php } ?>
                    </div>
                </td>
                <?php if ($col == $maxcols) { echo '</tr>'; $col = 1; } else { $col++; } ?>
            <?php } ?>

            <?php if ($col > 1) { ?>
                <td colspan="<?php echo (($maxcols + 1) - $col); ?>">&nbsp;</td></tr>
            <?php } ?>
        </table>

    <?php if (($is_admin || $my_profile) && $album_type == 'private') { ?>
        <div class="usr_photo_sel_bar bar">
            <?php echo $_LANG['SELECTED_ITEMS']; ?>:
            <input type="submit" name="edit" id="edit_btn" value="<?php echo $_LANG['EDIT']; ?>" disabled="disabled" />
            <input type="submit" name="delete" id="delete_btn" value="<?php echo $_LANG['DELETE']; ?>" disabled="disabled" />
        </div>
    </form>
    <?php } ?>
        
    <?php echo $pagebar; ?>

<?php } else { ?>
    <p><?php echo $_LANG['NOT_PHOTOS']; ?></p>
<?php } ?>