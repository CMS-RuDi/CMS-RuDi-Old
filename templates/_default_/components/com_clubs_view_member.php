<h1 class="con_heading"><?php echo $_LANG['CLUB_MEMBERS']; ?> - <?php echo $club['title']; ?> (<?php echo $total_members + 1; ?>)</h1>
<div class="users_list">
<table width="100%" cellspacing="0" cellpadding="0" class="users_list">
<?php if ($page == 1) { ?>
  <tr>
    <td width="80" valign="top"><div class="avatar"><a href="<?php echo cmsUser::getProfileURL($club['login']); ?>"><img border="0" class="usr_img_small" src="<?php echo $club['admin_avatar']; ?>" /></a></div></td>
    <td valign="top">
      <div title="<?php echo $_LANG['KARMA']; ?>" class="karma<?php if ($club['karma'] > 0) { ?> pos<?php } ?><?php if ($club['karma'] < 0) { ?> neg<?php } ?>"><?php if ($club['karma'] > 0) { ?>+<?php } ?><?php echo $club['karma']; ?></div>
      <div class="status">
        <?php if ($club['is_online']) { ?>
            <span class="online"><?php echo $_LANG['ONLINE']; ?></span>
        <?php } else { ?>
            <span class="offline"><?php echo $club['logdate']; ?></span>
        <?php } ?>
      </div>
      <div class="nickname"><a href="<?php echo cmsUser::getProfileURL($club['login']); ?>" style="color:#F00" title="<?php echo $_LANG['CLUB_ADMIN']; ?>"><?php echo $club['nickname']; ?></a></div>
      <?php if ($club['status']) { ?>
      <div class="microstatus"><?php echo $club['status']; ?></div>
      <?php } ?> </td>
  </tr>
<?php } ?>
  <?php foreach($members as $usr) { ?>
  <tr>
    <td width="80" valign="top"><div class="avatar"><a href="<?php echo cmsUser::getProfileURL($usr['login']); ?>"><img border="0" class="usr_img_small" src="<?php echo $usr['admin_avatar']; ?>" /></a></div></td>
    <td valign="top">
      <div title="<?php echo $_LANG['KARMA']; ?>" class="karma<?php if ($usr['karma'] > 0) { ?> pos<?php } ?><?php if ($usr['karma'] < 0) { ?> neg<?php } ?>"><?php if ($usr['karma'] > 0) { ?>+<?php } ?><?php echo $usr['karma']; ?></div>
      <div class="status">
        <?php if ($usr['is_online']) { ?>
            <span class="online"><?php echo $_LANG['ONLINE']; ?></span>
        <?php } else { ?>
            <span class="offline"><?php echo $usr['logdate']; ?></span>
        <?php } ?>
      </div>
      <div class="nickname"><a href="<?php echo cmsUser::getProfileURL($usr['login']); ?>" <?php if ($usr['role'] == 'moderator') { ?>style="color:#090;" title="<?php echo $_LANG['MODERATOR']; ?>"<?php } ?>><?php echo $usr['nickname']; ?></a></div>
      <?php if ($usr['status']) { ?>
      <div class="microstatus"><?php echo $usr['status']; ?></div>
      <?php } ?> </td>
  </tr>
  <?php } ?>		
</table>
</div>
<?php echo $pagebar; ?>