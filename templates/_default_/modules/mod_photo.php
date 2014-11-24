<?php $col = 1; ?>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
    <?php foreach($photos as $photo) { ?>
        <?php if ($col == 1) { ?> <tr> <?php } ?>
        <td align="center" valign="middle" width="<?php echo 100/$cfg['maxcols']; ?>%" class="mod_lp_photo">
                <a href="/<?php if ($photo['NSDiffer'] != '') { ?>clubs<?php } else { ?>photos<?php } ?>/photo<?php echo $photo['id']; ?>.html" title="<?php echo $this->escape($photo['title']); ?>">
                    <img class="photo_thumb_img" src="/images/photos/small/<?php echo $photo['file']; ?>" alt="<?php echo $this->escape($photo['title']); ?>" border="0" />
                </a>
                <?php if ($cfg['is_full']) { ?>
                <br /><a href="/<?php if ($photo['NSDiffer'] != '') { ?>clubs<?php } else { ?>photos<?php } ?>/photo<?php echo $photo['id']; ?>.html" title="<?php echo $this->escape($photo['title']); ?>"><?php echo $this->truncate($photo['title'], 18); ?></a>
                <div class="mod_lp_albumlink"><a href="/<?php if ($photo['NSDiffer'] != '') { ?>clubs/photoalbum<?php } else { ?>photos/<?php } ?><?php echo $photo['album_id']; ?>" title="<?php echo $this->escape($photo['cat_title']); ?>"><?php echo $this->truncate($photo['cat_title'], 18); ?></a>
                    <div class="mod_lp_details">
                    <table cellpadding="2" cellspacing="0" align="center" border="0"><tr>
                        <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/calendar.png" border="0"/></td>
                        <td><?php echo $photo['pubdate']; ?></td>
                        <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/comment-small.png" border="0"/></td>
                        <td><a href="/photos/photo<?php echo $photo['id']; ?>.html#c" title="<?php echo $this->spellcount($photo['comments'], $_LANG['COMMENT1'], $_LANG['COMMENT2'], $_LANG['COMMENT10']); ?>"><?php echo $photo['comments']; ?></a></td>
                        <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/rating.png" /></td>
                        <td><?php echo $this->rating($photo['rating']); ?></td>
                    </tr></table>
                    </div></div>
                <?php } ?>
        </td>
        <?php if ($col==$cfg['maxcols']) { echo '</tr>'; $col = 1; } else { $col++; } ?>
    <?php } ?>
    <?php if ($col>1) { ?>
        <td colspan="<?php echo (($col - $cfg['maxcols']) + 1); ?>">&nbsp;</td></tr>
    <?php } ?>
</table>

<?php if ($cfg['showmore']) { ?>
    <div>
        <?php if ($cfg['sort'] == 'pubdate') { ?>
            <a href="/photos/latest.html"><?php echo $_LANG['NEW_PHOTO_IN_GALLERY']; ?></a> &rarr;
        <?php } elseif ($cfg['sort'] == 'rating') { ?>
            <a href="/photos/top.html"><?php echo $_LANG['BEST_PHOTOS']; ?></a> &rarr;
        <?php } elseif ($cfg['is_full']) { ?>
            <a href="<?php if ($photo['NSDiffer'] != '') { ?>clubs/photoalbum<?php } else { ?>photos/<?php } ?><?php echo $photo['album_id']; ?>"><?php echo $this->escape($photo['cat_title']); ?></a> &rarr;
        <?php } ?>
    </div>
<?php } ?>

<?php if ($cfg['is_lightbox']) { ?> 
    <script type="text/javascript">
        $(function() {
            $( '.photo_thumb_img' ).each( function( idx ) {
                var regex = /small\//;
                var orig = $( this ).attr( 'src' ).replace( regex, 'medium/' );
                var ss = $( this ).parent( 'a' );
                ss.attr( 'rel', 'gal' ).attr( 'href', orig ).addClass( 'photobox' );
            } );
            $('a.photobox').colorbox( { rel: 'gal', transition: 'none', slideshow: true, width: '650px', height: '650px' } );
        } );
    </script>
<?php } ?>