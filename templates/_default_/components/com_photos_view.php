<?php if ($album['id'] == $root_album_id && $cfg['showlat']) { ?>
<div class="float_bar">
    <table cellspacing="0" cellpadding="0">
      <tr>
        <td width="23"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/calendar.png" /></td>
        <td style="padding-right: 10px"><a href="/photos/latest.html"><?php echo $_LANG['LAST_UPLOADED']; ?></a></td>
        <td width="23"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/rating.png" /></td>
        <td><a href="/photos/top.html"><?php echo $_LANG['BEST_PHOTOS']; ?></a></td>
      </tr>
    </table>
</div>
<?php } else if ($album['id'] != $root_album_id && $album['orderform']) { ?>
    <div class="float_bar">
        <form action="" method="POST" style="float: left">
            <?php echo $_LANG['SORTING_PHOTOS']; ?>:
            <select name="orderby" id="orderby">
                <option value="title" <?php if ($orderby == 'title') { ?> selected <?php } ?>><?php echo $_LANG['ORDERBY_TITLE']; ?></option>
                <option value="pubdate" <?php if ($orderby == 'pubdate') { ?> selected <?php } ?>><?php echo $_LANG['ORDERBY_DATE']; ?></option>
                <option value="rating" <?php if ($orderby == 'rating') { ?> selected <?php } ?>><?php echo $_LANG['ORDERBY_RATING']; ?></option>
                <option value="hits" <?php if ($orderby == 'hits') { ?> selected <?php } ?>><?php echo $_LANG['ORDERBY_HITS']; ?></option>
            </select>
            <select name="orderto" id="orderto">
                <option value="desc" <?php if ($orderto == 'desc') { ?> selected <?php } ?>><?php echo $_LANG['ORDERBY_DESC']; ?></option>
                <option value="asc" <?php if ($orderto == 'asc') { ?> selected <?php } ?>><?php echo $_LANG['ORDERBY_ASC']; ?></option>
            </select>
            <input type="submit" value=">>" />
        </form>
        <?php if ($can_add_photo) { ?>
            <a class="photo_add_link" href="/photos/<?php echo $album['id']; ?>/addphoto.html"><?php echo $_LANG['ADD_PHOTO_TO_ALBUM']; ?></a>
        <?php } ?>
    </div>

<?php } else if ($can_add_photo && $album['parent_id'] > 0) { ?>
	<div class="float_bar"><a class="photo_add_link" href="/photos/<?php echo $album['id']; ?>/addphoto.html"><?php echo $_LANG['ADD_PHOTO_TO_ALBUM']; ?></a></div>
<?php } ?>

<h1 class="con_heading"><?php echo $album['title']; ?> <?php if ($total) { ?>(<?php echo $total; ?>)<?php } ?></h1>

<div class="clear"></div>
<?php if ($album['description']) { ?>
    <p class="usr_photos_notice"><?php echo nl2br($album['description']); ?></p>
<?php } ?>
<?php if ($subcats) { ?>
    <?php $col = 1; ?>
        <?php foreach($subcats as $cat) { ?>
        <?php if ($col == 1) { ?><div class="photo_row"><?php } ?>
            <div class="photo_album_tumb">
                <div class="photo_container">
                    <a href="/photos/<?php echo $cat['id']; ?>"><img class="photo_album_img" src="/images/photos/small/<?php echo $cat['file']; ?>" alt="<?php echo $this->escape($cat['title']); ?>" width="{$cat.thumb1}px" /></a>
                </div>
                <div class="photo_txt">
                    <ul>
                        <li class="photo_album_title"><a href="/photos/<?php echo $cat['id']; ?>"><?php echo $cat['title']; ?></a> (<?php echo $cat['content_count']; ?>)</li>
                        <?php if ($cat['description']) { ?><li><?php echo $cat['description']; ?></li><?php } ?>
                    </ul>
                </div>
            </div>

         <?php if ($col == $cfg['maxcols']) { $col = 1; ?><div class="blog_desc"></div></div> <?php } else { $col++; } ?>

        <?php } ?>
        <?php if ($col > 1) { ?>
            <div class="blog_desc"></div></div>
        <?php } ?>
<?php } ?>

<?php if ($photos) { ?>
<?php $col = 1; ?>
<div class="photo_gallery">
    <table cellpadding="0" cellspacing="0">
    <?php foreach($photos as $photo) { ?>
        <?php if ($col == 1) { ?> <tr> <?php } ?>
        <td align="center" valign="middle" width="<?php echo 100/$album['maxcols']; ?>%">
            <div class="<?php echo $album['cssprefix']; ?>photo_thumb" align="center">
                <?php if ($album['showtype'] == 'lightbox') { ?>
                <a class="lightbox-enabled" rel="lightbox-galery" href="/images/photos/medium/<?php echo $photo['file']; ?>" title="<?php echo $this->escape($photo['title']); ?>">
                <?php } else { ?>
                <a href="/photos/photo<?php echo $photo['id']; ?>.html" title="<?php echo $this->escape($photo['title']); ?>">
                <?php } ?>
                    <img src="/images/photos/small/<?php echo $photo['file']; ?>" alt="<?php echo $this->escape($photo['title']); ?>" />
                </a><br />
                <a href="/photos/photo<?php echo $photo['id']; ?>.html" title="<?php echo $this->escape($photo['title']); ?>"><?php echo $this->truncate($photo['title'], 18); ?></a>
                <?php if ($album['showdate']) { ?>
                    <div class="mod_lp_albumlink"><div class="mod_lp_details">
                    <table cellpadding="2" cellspacing="0" align="center"><tr>
                        <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/calendar.png" /></td>
                        <td><?php echo $photo['pubdate']; ?></td>
                        <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/comment-small.png" /></td>
                        <td><a href="/photos/photo<?php echo $photo['id']; ?>.html#c" title="<?php echo $this->spellcount($photo['comments'], $_LANG['COMMENT1'], $_LANG['COMMENT2'], $_LANG['COMMENT10']); ?>"><?php echo $photo['comments']; ?></a></td>
                    </tr></table>
                    </div></div>
                <?php } ?>
                <?php if (!$photo['published']) { ?>
                    <div style="color:#F00; font-size:12px"><?php echo $_LANG['WAIT_MODERING']; ?></div>
                <?php } ?>
        	</div>
        </td>
    <?php if ($col == $album['maxcols']) { $col = 1; echo '</tr>'; } else { $col++; } ?>
    <?php } ?>
    <?php if ($col > 1) { ?>
        <td colspan="<?php echo (($album['maxcols'] + 1) - $col); ?>">&nbsp;</td></tr>
    <?php } ?>
    </table>
</div>
<?php echo $pagebar; ?>
<?php } else { ?>
    <?php if ($album['parent_id'] > 0) { ?><p><?php echo $_LANG['NOT_PHOTOS_IN_ALBUM']; ?></p><?php } ?>
<?php } ?>