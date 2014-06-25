<?php if ($myprofile) { ?>
<div class="float_bar">
    <a href="/users/addfile.html" class="add_file_link"><?php echo $_LANG['UPLOAD_FILE_IN_ARCHIVE']; ?></a>
</div>
<?php } ?>

<div class="con_heading"><a href="<?php echo cmsUser::getProfileURL($usr['login']); ?>"><?php echo $usr['nickname']; ?></a> &rarr; <?php echo $_LANG['FILES']; ?></div>
<?php if ($files) { ?>
<div class="usr_files_orderbar">
  <table width="100%" cellspacing="0" cellpadding="2">
    <tr>
      <td width="15">&nbsp;</td>
      <td width="80"><strong><?php echo $_LANG['FILE_COUNT']; ?>: </strong><?php echo $total_files; ?></td>
      <?php if ($myprofile) { ?>
      		<?php if ($cfg['filessize']) { ?>
                <td width="130"><strong><?php echo $_LANG['FREE']; ?>: </strong><?php echo $free_mb; ?> <?php echo $_LANG['MBITE']; ?></td>
            <?php } else { ?>
            	<td width="130"></td>
          	<?php } ?>
      <?php } ?>
      <?php if ($total_files > 1) { ?>
        <td align="right">
            <form name="orderform" method="post" action="" style="margin:0px">
                <input type="button" class="usr_files_orderbtn" onclick="orderPage('pubdate')" name="order_date" value="<?php echo $_LANG['ORDER_BY_DATE']; ?>" <?php if ($orderby == 'pubdate') { ?> disabled <?php } ?> />
                <input type="button" class="usr_files_orderbtn" onclick="orderPage('filename')" name="order_title" value="<?php echo $_LANG['ORDER_BY_NAME']; ?>" <?php if ($orderby == 'filename') { ?> disabled <?php } ?> />
                <input type="button" class="usr_files_orderbtn" onclick="orderPage('filesize')" name="order_size" value="<?php echo $_LANG['ORDER_BY_SIZE']; ?>" <?php if ($orderby == 'filesize') { ?> disabled <?php } ?> />
                <input type="button" class="usr_files_orderbtn" onclick="orderPage('hits')" name="order_hits" value="<?php echo $_LANG['ORDER_BY_DOWNLOAD']; ?>" <?php if ($orderby == 'hits') { ?> disabled <?php } ?> />
                <input id="orderby" type="hidden" name="orderby" value="<?php echo $orderby; ?>"/>
            </form>
        </td>
      <?php } else { ?>
      <td>&nbsp;</td>
      <?php } ?>
      </tr>
  </table>
</div>

<form name="listform" id="listform" action="" method="post">
  <table width="100%" cellspacing="0" cellpadding="5" style="border:solid 1px gray">
    <tr>
      <td class="usr_files_head" width="20" align="center">#</td>
      <td class="usr_files_head" width="" colspan="2"><?php echo $_LANG['FILE_NAME']; ?> <?php if ($orderby == 'filename') { ?> &darr; <?php } ?></td>
      <?php if ($myprofile) { ?>
        <td class="usr_files_head" width="100" align="center"><?php echo $_LANG['VISIBILITY']; ?></td>
      <?php } ?>
      <td class="usr_files_head" width="100"><?php echo $_LANG['SIZE']; ?> <?php if ($orderby == 'filesize') { ?>&darr;<?php } ?></td>
      <td class="usr_files_head" width="120"><?php echo $_LANG['CREATE_DATE']; ?> <?php if ($orderby == 'pubdate') { ?>&darr;<?php } ?></td>
      <td class="usr_files_head" width="80" align="center"><?php echo $_LANG['DOWNLOAD_HITS']; ?> <?php if ($orderby == 'hits') { ?>&darr;<?php } ?></td>
      </tr>

    <?php foreach($files as $file) { ?>
        <tr>
        <?php if ($myprofile || $is_admin) { ?>
          <td align="center" valign="top"><input id="fileid<?php echo $file['rownum']; ?>" type="checkbox" name="files[]" value="<?php echo $file['id']; ?>"/></td>
        <?php } else { ?>
          <td align="center" valign="top"><?php echo $file['rownum']; ?></td>
        <?php } ?>
          <td width="16" valign="top"><?php echo $file['fileicon']; ?></td>
          <td valign="top"><a href="<?php echo $file['filelink']; ?>"><?php echo $file['filename']; ?></a>
            <div class="usr_files_link"><?php echo $file['filelink']; ?></div></td>
          <?php if ($myprofile) { ?>
          	<?php if ($file['allow_who'] == 'all') { ?>
          <td align="center"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/users/yes.gif" border="0" title="<?php echo $_LANG['FILE_VIS_ALL']; ?>"/></td>
          	<?php } else { ?>
          <td align="center"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/users/no.gif" border="0" title="<?php echo $_LANG['FILE_HIDEN']; ?>"/></td>
            <?php } ?>
          <?php } ?>
          <td><?php echo $file['mb']; ?> <?php echo $_LANG['MBITE']; ?></td>
          <td><?php echo $file['pubdate']; ?></td>
          <td align="center"><?php echo $file['hits']; ?></td>
          </tr>
    <?php } ?>
  </table>

  <?php if ($myprofile || $is_admin) { ?>
    <div style="margin-top:6px; float:right;">
      <input type="button" class="usr_files_orderbtn" name="delete_btn" id="delete_btn" onclick="delFiles('<?php echo $_LANG['YOU_REALLY_DEL_FILES']; ?>?')" value="<?php echo $_LANG['DELETE']; ?>"/>
      <input type="button" class="usr_files_orderbtn" name="hide_btn" id="delete_btn" onclick="pubFiles(0)" value="<?php echo $_LANG['HIDE']; ?>"/>
      <input type="button" class="usr_files_orderbtn" name="show_btn" id="delete_btn" onclick="pubFiles(1)" value="<?php echo $_LANG['SHOW']; ?>"/>
    </div>
  <?php } ?>
  <?php echo $pagination; ?>
</form>
<?php } else { ?>
	<p><?php echo $_LANG['USER_NO_UPLOAD']; ?></p>
<?php } ?>