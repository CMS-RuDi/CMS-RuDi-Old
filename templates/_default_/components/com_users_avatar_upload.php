<div class="float_bar"><a class="usr_avatars_lib_link" href="/users/<?php echo $id; ?>/select-avatar.html"><?php echo $_LANG['SELECT_AVATAR_FROM_COLL']; ?></a></div>

<div class="con_heading"><?php echo $_LANG['LOAD_AVATAR']; ?></div>

<form enctype="multipart/form-data" action="/users/<?php echo $id; ?>/avatar.html" method="POST">
    <p><?php echo $_LANG['SELECT_UPLOAD_FILE']; ?>: </p>
    <input name="upload" type="hidden" value="1"/>
    <input name="userid" type="hidden" value="<?php echo $id; ?>"/>
    <input name="picture" type="file" id="picture" size="30" />
    <p style="margin-top:10px">
        <input type="submit" value="<?php echo $_LANG['UPLOAD']; ?>"> <input type="button" onclick="window.history.go(-1);" value="<?php echo $_LANG['CANCEL']; ?>"/>
    </p>
</form>