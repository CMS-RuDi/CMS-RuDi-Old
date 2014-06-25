<h1 class="con_heading"><?php echo $pagetitle; ?></h1>

<?php $col = 1; ?>
<div class="photo_gallery">
    <table cellpadding="0" cellspacing="0" border="0">
    <?php foreach($photos as $photo) { ?>
        <?php if ($col == 1) { ?> <tr> <?php } ?>
        <td align="center" valign="middle" width="<?php echo 100/$maxcols; ?>%">
            <div class="photo_thumb" align="center">
                <a href="/photos/photo<?php echo $photo['id']; ?>.html" title="<?php echo $this->escape($photo['title']); ?>">
                    <img class="photo_thumb_img" src="/images/photos/small/<?php echo $photo['file']; ?>" alt="<?php echo $this->escape($photo['title']); ?>" border="0" />
                </a><br />
                <a href="/photos/photo<?php echo $photo['id']; ?>.html" title="<?php echo $this->escape($photo['title']); ?>"><?php echo $this->truncate($photo['title'], 18); ?></a>
                <div class="mod_lp_albumlink"><a href="/photos/<?php echo $photo['album_id']; ?>" title="<?php echo $this->escape($photo['cat_title']); ?>"><?php echo $this->truncate($photo['cat_title'], 18); ?></a>
                <div class="mod_lp_details">
                <table cellpadding="2" cellspacing="0" align="center" border="0"><tr>
                    <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/calendar.png" border="0"/></td>
                    <td><?php echo $photo['pubdate']; ?></td>
                    <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/comment-small.png" border="0"/></td>
                    <td><a href="/photos/photo<?php echo $photo['id']; ?>.html#c" title="<?php echo $this->spellcount($photo['comments'], $_LANG['COMMENT1'], $_LANG['COMMENT2'], $_LANG['COMMENT10']); ?>"><?php echo $photo['comments']; ?></a></td>
                    <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/rating.png" /></td>
                    <td><?php echo $this->rating($photo['rating']); ?></td>
                </tr></table>
                </div>
                </div>
            </div>
        </td>
    <?php if ($col == $maxcols){ echo '</tr>'; $col = 1; } else { $col++; } ?>
    <?php } ?>
    <?php if ($col > 1) { ?> 
        <td colspan="<?php echo (($maxcols + 1) - $col); ?>">&nbsp;</td></tr>
    <?php } ?>
    </table>
</div>