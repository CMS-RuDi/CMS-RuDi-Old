<?php if ($is_admin || $is_moder || $is_member) { ?>
    <div class="float_bar"><?php if ($is_admin || $is_moder) { ?><a class="ajaxlink usr_edit_album" href="javascript:void(0)" onclick="clubs.renameAlbum(<?php echo $album['id']; ?>);return false;"><?php echo $_LANG['RENAME_ALBUM']; ?></a> | <a class="ajaxlink usr_del_album" href="javascript:void(0)" onclick="clubs.deleteAlbum(<?php echo $album['id']; ?>, '<?php echo cmsUser::getCsrfToken(); ?>');return false;"><?php echo $_LANG['DELETE_ALBUM']; ?></a> | <?php } ?><a class="photo_add_link" href="/clubs/addphoto<?php echo $album['id']; ?>.html"><?php echo $_LANG['ADD_PHOTO_TO_ALBUM']; ?></a></div>
<?php } ?>

<h1 class="con_heading"><span id="album_title"><?php echo $album['title']; ?></span> (<?php echo $total; ?>)</h1>
<div class="clear"></div>
		
<?php if ($photos) { ?>
<?php $col = 1; ?>
<div class="photo_gallery">
    <table cellpadding="0" cellspacing="0" border="0">
        <?php foreach($photos as $con) { ?>
            <?php if ($col == 1) { ?> <tr> <?php } ?>
            <td align="center" valign="middle" width="{math equation="100/x" x=$cfg.photo_maxcols}%">
                <div class="photo_thumb" align="center">
                    <a class="lightbox-enabled" rel="lightbox-galery" href="/images/photos/medium/<?php echo $con['file']; ?>" title="<?php echo $this->escape($con['title']); ?>">
                        <img class="photo_thumb_img" src="/images/photos/small/<?php echo $con['file']; ?>" alt="<?php echo $this->escape($con['title']); ?>" border="0" />
                    </a><br />
                    <a href="/clubs/photo<?php echo $con['id']; ?>.html" title="<?php echo $this->escape($con['title']); ?>"><?php echo $this->truncate($con['title'], 18); ?></a>
                    <?php if (!$con['published']) { ?>
                    	<div style="color:#F00; font-size:12px"><?php echo $_LANG['WAIT_MODERING']; ?></div>
                    <?php } ?>
            	</div>
            </td>
        <?php if ($col == $cfg['photo_maxcols']) { $col = 1; echo '</tr>'; } else { $col++; } ?>
        <?php } ?>
        <?php if ($col > 1) { ?> 
        <td colspan="<?php echo (($cfg['photo_maxcols'] + 1) - $col); ?>">&nbsp;</td></tr>
        <?php } ?>
   </table>
</div>
<?php echo $pagebar; ?>
<?php } else { ?>
    <p><?php echo $_LANG['NOT_PHOTOS_IN_ALBUM']; ?></p>    
<?php } ?>