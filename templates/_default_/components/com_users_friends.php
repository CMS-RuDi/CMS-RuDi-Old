<div class="con_heading"><a href="<?php echo cmsUser::getProfileURL($usr['login']); ?>"><?php echo $usr['nickname']; ?></a> &rarr; <?php echo $_LANG['FRIENDS']; ?> (<?php echo $total; ?>)</div>
<div class="users_list">
  <table width="100%" cellspacing="0" cellpadding="0" class="users_list">
    <?php if ($friends) { ?>
    <?php foreach($friends as $friend) { ?>
    <tr id="friend_id_<?php echo $friend['id']; ?>">
      <td width="80" valign="top"><div class="avatar"><a href="<?php echo cmsUser::getProfileURL($friend['login']); ?>"><img border="0" class="usr_img_small" src="<?php echo $friend['avatar']; ?>" /></a></div></td>
      <td valign="top"><div class="status"><?php echo $friend['flogdate']; ?><br />
          <a href="javascript:void(0)" class="ajaxlink" onclick="users.sendMess('<?php echo $friend['id']; ?>', 0, this);return false;" title="<?php echo $_LANG['WRITE_MESS']; ?>: <?php echo $this->escape($friend['nickname']); ?>"><?php echo $_LANG['WRITE_MESS']; ?></a> <?php if ($myprofile) { ?><br />
          <a href="javascript:void(0)" title="<?php echo $this->escape($friend['nickname']); ?>" onclick="users.delFriend('<?php echo $friend['id']; ?>', this);return false;" class="ajaxlink"><?php echo $_LANG['STOP_FRIENDLY']; ?></a><?php } ?> </div>
        <div class="nickname"> <a class="friend_link" href="<?php echo cmsUser::getProfileURL($friend['login']); ?>"><?php echo $friend['nickname']; ?></a><br />
          <?php if ($friend['status']) { ?> <span class="microstatus"><?php echo $friend['status']; ?></span> <?php } ?> </div></td>
    </tr>
    <?php } ?>
    <?php } ?>
  </table>
</div>
<?php echo $pagebar; ?>