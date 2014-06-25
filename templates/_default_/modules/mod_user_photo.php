<?php $col = 1; ?>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<?php foreach($photos as $photo) { ?>
    <?php if ($col == 1) { ?> <tr> <?php } ?>
    <td align="center" valign="middle" width="<?php echo 100/$cfg['maxcols']; ?>%" class="mod_lp_photo">
        <a href="/users/<?php echo $photo['uid']; ?>/photo<?php echo $photo['id']; ?>.html" title="<?php echo $this->escape($photo['title']); ?>">
            <img class="photo_thumb_img" src="/images/users/photos/small/<?php echo $photo['file']; ?>" alt="<?php echo $this->escape($photo['title']); ?>" border="0" />
        </a>
        <?php if ($cfg['is_full']) { ?>
            <br /><a href="/users/<?php echo $photo['uid']; ?>/photo<?php echo $photo['id']; ?>.html" title="<?php echo $this->escape($photo['title']); ?>"><?php echo $this->truncate($photo['title'], 18); ?></a>
            <div class="mod_lp_albumlink"><a href="/users/<?php echo $photo['login']; ?>/photos/private<?php echo $photo['album_id']; ?>.html" title="<?php echo $this->escape($photo['album_title']); ?>"><?php echo $this->truncate($photo['album_title'], 18); ?></a>
                <div class="mod_lp_details">
                <table cellpadding="2" cellspacing="0" align="center" border="0"><tr>
                    <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/calendar.png" border="0"/></td>
                    <td><?php echo $photo['pubdate']; ?></td>
                    <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/comment-small.png" border="0"/></td>
                    <td><a href="/users/<?php echo $photo['uid']; ?>/photo<?php echo $photo['id']; ?>.html#c" title="<?php echo $this->spellcount($photo['comments'], $_LANG['COMMENT1'], $_LANG['COMMENT2'], $_LANG['COMMENT10']); ?>"><?php echo $photo['comments']; ?></a></td>
                </tr></table>
                </div>
            </div>
        <?php } ?>
    </td>
    <?php if ($col == $cfg['maxcols']){ echo '</tr>'; $col = 1; } else { $col++; } ?>
<?php } ?>
<?php if ($col > 1) { ?>
    <td colspan="<?php echo (($cfg['maxcols'] + 1) - $col); ?>">&nbsp;</td></tr>
<?php } ?>
</table>