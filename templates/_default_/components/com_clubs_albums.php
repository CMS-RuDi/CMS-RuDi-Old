<?php if ($show_title) { ?>
    <?php if ($is_admin || $is_moder || $is_karma_enabled) { ?>
        <div class="float_bar">
            <a class="service ajaxlink" href="javascript:void(0)" onclick="clubs.addAlbum(<?php echo $club['id']; ?>);"><?php echo $_LANG['ADD_PHOTOALBUM']; ?></a>
        </div>
    <?php } ?>
    <h1 class="con_heading"><?php echo $pagetitle; ?></h1>
<?php } ?>

<?php if ($club['photo_albums']) { ?>
    <div class="usr_albums_block" style="margin-top:25px">
        <ul class="usr_albums_list">
            <?php foreach($club['photo_albums'] as $album) { ?>
                <li id="<?php echo $album['id']; ?>">
                    <div class="usr_album_thumb">
                        <a href="/clubs/photoalbum<?php echo $album['id']; ?>" title="<?php echo $this->escape($album['title']); ?>">
                            <img src="/images/photos/small/<?php echo $album['file']; ?>" width="64" height="64" border="0" alt="<?php echo $this->escape($album['title']); ?>" />
                        </a>
                    </div>
                    <div class="usr_album">
                        <div class="link">
                            <a href="/clubs/photoalbum<?php echo $album['id']; ?>" title="<?php echo $this->escape($album['title']); ?>"><?php echo $this->truncate($album['title'], 14); ?></a>
                        </div>
                        <div class="count"><?php if ($album['content_count']) { ?> <?php echo $this->spellcount($album['content_count'], $_LANG['PHOTO'], $_LANG['PHOTO2'], $_LANG['PHOTO10']); ?> <?php } else { ?> <?php echo $_LANG['NOT_PHOTO']; ?><?php } ?></div>
                        <div class="date"><?php echo $album['pubdate']; ?></div>
                    </div>
                </li>
            <?php } ?>
         </ul>
    </div>
        
<?php } else { ?>
    <div class="usr_albums_block" style="margin-top:30px">
        <ul class="usr_albums_list">
    		<li class="no_albums"><?php echo $_LANG['NO_PHOTOALBUM']; ?></li>
        </ul>
    </div>
<?php } ?>