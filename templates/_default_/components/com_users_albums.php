<?php if ($my_profile) { ?>
    <div class="float_bar">
        <a href="/users/addphoto.html" class="usr_photo_add"><?php echo $_LANG['ADD_PHOTO']; ?></a>
    </div>
<?php } ?>

<div class="con_heading">
    <a href="<?php echo cmsUser::getProfileURL($user['login']); ?>"><?php echo $user['nickname']; ?></a> &rarr; <?php echo $_LANG['PHOTOALBUMS']; ?>
</div>

<?php if ($albums) { ?>
    <div class="usr_albums_block" style="margin-top:30px">
        <ul class="usr_albums_list">
            <?php foreach($albums as $album) { ?>
                <li>
                    <div class="usr_album_thumb">
                        <a href="/users/<?php echo $user['login']; ?>/photos/<?php echo $album['type']; ?><?php echo $album['id']; ?>.html" title="<?php echo $this->escape($album['title']); ?>">
                            <img src="<?php echo $album['imageurl']; ?>" width="64" height="64" border="0" alt="<?php echo $this->escape($album['title']); ?>" />
                        </a>
                    </div>
                    <div class="usr_album">
                        <div class="link">
                            <a href="/users/<?php echo $user['login']; ?>/photos/<?php echo $album['type']; ?><?php echo $album['id']; ?>.html"><?php echo $album['title']; ?></a>
                        </div>
                        <div class="count"><?php echo $this->spellcount($album['photos_count'], $_LANG['PHOTO'], $_LANG['PHOTO2'], $_LANG['PHOTO10']); ?></div>
                        <div class="date"><?php echo $album['pubdate']; ?></div>
                    </div>
                </li>
            <?php } ?>
         </ul>
         <div class="blog_desc"></div>
    </div>
<?php } else { ?>
    <p><?php echo $_LANG['NOT_PHOTOS']; ?></p>
<?php } ?>