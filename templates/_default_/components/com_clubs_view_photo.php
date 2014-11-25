<div class="float_bar">
<strong><?php echo $_LANG['RATING']; ?>: </strong><span id="karmapoints"><?php echo $this->rating($photo['rating']); ?></span> | <strong><?php echo $_LANG['HITS']; ?>: </strong> <?php echo $photo['hits']; ?> | <?php if (!$photo['published']) { ?><span id="pub_photo_wait" style="color:#F00;"><?php echo $_LANG['WAIT_MODERING']; ?></span><span id="pub_photo_date" style="display:none;"><?php echo $photo['pubdate']; ?></span><?php } else { ?><?php echo $photo['pubdate']; ?><?php } ?> | <a href="<?php echo cmsUser::getProfileURL($photo['login']); ?>"><?php echo $photo['nickname']; ?></a> <?php if ($is_author || $is_admin || $is_moder) { ?>| <a class="ajaxlink" href="javascript:void(0)" onclick="clubs.editPhoto(<?php echo $photo['id']; ?>);return false;"><?php echo $_LANG['EDIT']; ?></a> <?php if ($is_admin || $is_moder) { ?><?php if (!$photo['published']) { ?><span id="pub_photo_link">  | <a class="ajaxlink" href="javascript:void(0)" onclick="clubs.publishPhoto(<?php echo $photo['id']; ?>);return false;"><?php echo $_LANG['PUBLISH']; ?></a></span><?php } ?> | <a class="ajaxlink" href="javascript:void(0)" onclick="clubs.deletePhoto(<?php echo $photo['id']; ?>, '<?php echo cmsUser::getCsrfToken(); ?>');return false;"><?php echo $_LANG['DELETE']; ?></a><?php } ?><?php } ?>
</div>

<h1 class="con_heading"><?php echo $photo['title']; ?></h1>
<?php if ($photo['description']) { ?>
    <p class="photo_desc"><?php echo nl2br($photo['description']); ?> </p>
<?php } ?>
<table width="100%" cellspacing="0" cellpadding="3" border="0">
  <tbody>
    <tr>
      <td width="150px" valign="middle" align="center">
      <?php if ($photo['previd']) { ?>
        <cite><?php echo $_LANG['PREVIOUS']; ?></cite><br>
        <a href="/clubs/photo<?php echo $photo['previd']['id']; ?>.html#main"><img alt="{$photo.previd.title|escape:'html'}" src="/images/photos/small/<?php echo $photo['previd']['file']; ?>"></a>
      <?php } ?>
      </td>
      <td align="center" valign="top">
      	<?php if ($is_exists_original) { ?>
            <a href="/images/photos/<?php echo $photo['file']; ?>" class="photobox">
                <img src="/images/photos/medium/<?php echo $photo['file']; ?>" alt="<?php echo $this->escape($photo['title']); ?>" id="view_photo" />
            </a>
        <?php } else { ?>
            <img src="/images/photos/medium/<?php echo $photo['file']; ?>" alt="<?php echo $this->escape($photo['title']); ?>" id="view_photo" />
        <?php } ?>
      </td>
      <td width="150px" valign="middle" align="center">
      <?php if ($photo['nextid']) { ?>
      	<cite><?php echo $_LANG['NEXT']; ?></cite><br>
        <a href="/clubs/photo<?php echo $photo['nextid']['id']; ?>.html#main"><img alt="{$photo.nextid.title|escape:'html'}" src="/images/photos/small/<?php echo $photo['nextid']['file']; ?>"></a>
      <?php } ?>
      </td>
    </tr>
  </tbody>
</table>
<?php if ($photo['karma_buttons']) { ?>
    <div class="club_photo"><?php echo $photo['karma_buttons']; ?></div>
<?php } ?>