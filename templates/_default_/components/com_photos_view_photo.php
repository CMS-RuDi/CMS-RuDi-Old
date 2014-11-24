<?php if ($is_author || $is_admin) { ?>
<div class="float_bar">
    <a class="ajaxlink" href="javascript:void(0)" onclick="photos.editPhoto(<?php echo $photo['id']; ?>);return false;"><?php echo $_LANG['EDIT']; ?></a><?php if ($is_admin) { ?>  | <a class="ajaxlink" href="javascript:void(0)" onclick="photos.movePhoto(<?php echo $photo['id']; ?>);return false;"><?php echo $_LANG['MOVE']; ?></a><?php if (!$photo['published']) { ?><span id="pub_photo_link">  | <a class="ajaxlink" href="javascript:void(0)" onclick="photos.publishPhoto(<?php echo $photo['id']; ?>);return false;"><?php echo $_LANG['PUBLISH']; ?></a></span><?php } ?><?php } ?>   | <a class="ajaxlink" href="javascript:void(0)" onclick="photos.deletePhoto(<?php echo $photo['id']; ?>, '<?php echo cmsUser::getCsrfToken(); ?>');return false;"><?php echo $_LANG['DELETE']; ?></a>
</div>
<?php } ?>

<h1 class="con_heading"><?php echo $photo['title']; ?></h1>

<?php if ($photo['description']) { ?>
    <div class="photo_desc">
        <?php echo nl2br($photo['description']); ?>
    </div>
<?php } ?>

<table cellpadding="0" cellspacing="0" border="0" class="photo_layout">
    <tr>
        <td valign="top" style="padding-right:15px;max-width:630px;">
            <img src="/images/photos/medium/<?php echo $photo['file']; ?>" border="0" alt="<?php echo $this->escape($photo['title']); ?>" style="max-width: 650px;" />

            <?php if ($photo['album_nav']) { ?>
                <div align="center" style="margin:5px 0 0 0">
                    <?php if ($previd) { ?>
                        &larr; <a href="/photos/photo<?php echo $previd['id']; ?>.html"><?php echo $_LANG['PREVIOUS']; ?></a>
                    <?php } ?>
                    <?php if ($previd && $nextid) { ?> | <?php } ?>
                    <?php if ($nextid) { ?>
                        <a href="/photos/photo<?php echo $nextid['id']; ?>.html"><?php echo $_LANG['NEXT']; ?></a> &rarr;
                    <?php } ?>
                </div>
			<?php } ?>
        </td>
        <td width="7" class="photo_larr">&nbsp;

        </td>
        <td width="250" valign="top">
            <div class="photo_details">

                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td>
                            <p><strong><?php echo $_LANG['RATING']; ?>: </strong><span id="karmapoints"><?php echo $this->rating($photo['rating']); ?></span></p>
                            <p><strong><?php echo $_LANG['HITS']; ?>: </strong> <?php echo $photo['hits']; ?></p>
                        </td>
                        <?php if ($photo['karma_buttons']) { ?>
                            <td width="25"><?php echo $photo['karma_buttons']; ?></td>
                        <?php } ?>
                    </tr>
                </table>

                <div class="photo_date_details">
                    <p><?php if (!$photo['published']) { ?><span id="pub_photo_wait" style="color:#F00;"><?php echo $_LANG['WAIT_MODERING']; ?></span><span id="pub_photo_date" style="display:none;"><?php echo $photo['pubdate']; ?></span><?php } else { ?><?php echo $photo['pubdate']; ?><?php } ?></p>
                    <p><?php echo $photo['genderlink']; ?></p>
                </div>

                <?php if ($cfg['link']) { ?>
                    <p class="photo_date_details"><a class="lightbox-enabled" rel="lightbox-galery" href="/images/photos/<?php echo $photo['file']; ?>" title="<?php echo $this->escape($photo['title']); ?>"><?php echo $_LANG['OPEN_ORIGINAL']; ?></a></p>
                <?php } ?>

            </div>

            <?php if ($photo['album_nav']) { ?>
                <div class="photo_sub_details">
                    <?php echo $_LANG['BACK_TO']; ?> <a href="/photos/<?php echo $photo['album_id']; ?>"><?php echo $_LANG['TO_ALBUM']; ?></a><br/>
                    <?php echo $_LANG['BACK_TO']; ?>  <a href="/photos"><?php echo $_LANG['TO_LIST_ALBUMS']; ?></a>
                </div>
            <?php } ?>

            <?php if ($photo['a_bbcode']) { ?>
            <div class="photo_details" style="margin-top:5px;font-size: 12px">
                <?php echo $_LANG['CODE_INPUT_TO_FORUMS']; ?>:<br/>
                <input onclick="$(this).select();" type="text" class="photo_bbinput" value="<?php echo $bbcode; ?>"/>
            </div>
            <?php } ?>

            <div class="photo_sub_details" style="padding:0px 20px">
                <?php echo $tagbar; ?>
            </div>

        </td>
    </tr>
</table>