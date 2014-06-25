<?php if ($cfg['sw_search']) { ?>
    <div id="users_search_link" class="float_bar"><a href="javascript:void(0)" onclick="$('#users_sbar').slideToggle('fast');"> <span><?php echo $_LANG['USERS_SEARCH']; ?></span> </a> </div>
<?php } ?>

<h1 class="con_heading"><?php echo $_LANG['USERS']; ?></h1>

<?php if ($cfg['sw_search']) { ?>
<div id="users_sbar" <?php if (!$stext) { ?>style="display:none;"<?php } ?>>
  <form name="usr_search_form" method="post" action="/users">
    <table cellpadding="2" width="100%">
      <tr>
        <td width="80"><?php echo $_LANG['FIND']; ?>: </td>
        <td width="170"><select name="gender" id="gender" class="field" style="width:150px">
            <option value="f" <?php if ($gender == 'f') { ?>selected="selected"<?php } ?>><?php echo $_LANG['FIND_FEMALE']; ?></option>
            <option value="m" <?php if ($gender == 'm') { ?>selected="selected"<?php } ?>><?php echo $_LANG['FIND_MALE']; ?></option>
            <option value="all" <?php if (!$gender) { ?>selected="selected"<?php } ?>><?php echo $_LANG['FIND_ALL']; ?></option>
          </select></td>
        <td width="80"><?php echo $_LANG['AGE_FROM']; ?></td>
        <td><input style="width:60px" name="agefrom" type="text" id="agefrom" value="<?php if ($age_fr) { ?><?php echo $age_fr; ?><?php } ?>"/>
          <?php echo $_LANG['TO']; ?>
          <input style="width:60px" name="ageto" type="text" id="ageto" value="<?php if ($age_to) { ?><?php echo $age_to; ?><?php } ?>"/></td>
      </tr>
      <tr>
        <td> <?php echo $_LANG['NAME']; ?> </td>
        <td colspan="3"><input id="name" name="name" class="longfield" type="text" value="<?php echo $this->escape($name); ?>"/></td>
      </tr>
      <tr>
        <td><?php echo $_LANG['CITY']; ?></td>
        <td colspan="3">
            <?php echo cmsCore::city_input(array('value' => $city, 'name' => 'city', 'width' => '408px')); ?>
        </td>
      </tr>
      <tr>
        <td><?php echo $_LANG['HOBBY']; ?></td>
        <td colspan="3"><input style="" id="hobby" class="longfield" name="hobby" type="text" value="<?php echo $this->escape($hobby); ?>"/></td>
      </tr>
    </table>
	<p><label for="online" style="display:inherit;"><input id="online" name="online" type="checkbox" value="1" <?php if ($only_online) { ?> checked="checked"<?php } ?>> <?php echo $_LANG['SHOW_ONLY_ONLINE']; ?></label></p>
    <p>
      <input name="gosearch" type="submit" id="gosearch" value="<?php if ($stext) { ?><?php echo $_LANG['SEARCH_IN_RESULTS']; ?><?php } else { ?><?php echo $_LANG['SEARCH']; ?><?php } ?>" />
      <?php if ($stext) { ?>
      	<input type="button" value="<?php echo $_LANG['CANCEL_SEARCH_SHOWALL']; ?>" onclick="centerLink('/users/all.html')" />
      <?php } ?>
      <input name="hide" type="button" id="hide" value="<?php echo $_LANG['HIDE']; ?>" onclick="$('#users_sbar').slideToggle();"/>
    </p>
  </form>
</div>
<?php } ?>

<?php if ($stext && !$cfg['sw_search']) { ?>
<div class="users_search_results"> <a href="javascript:void(0)" rel="nofollow" onclick="centerLink('/users/all.html')" style="float: right; margin:4px 0 0 0"><?php echo $_LANG['CANCEL_SEARCH_SHOWALL']; ?></a>
  <h3><?php echo $_LANG['SEARCH_RESULT']; ?></h3>
  <ul>
    <?php foreach($stext as $text) { ?>
    <li><?php echo $text; ?></li>
    <?php } ?>
  </ul>
</div>
<?php } ?>
  <div class="users_list_buttons">
    <div class="button <?php if ($link['selected'] == 'latest') { ?>selected<?php } ?>"><a rel=”nofollow” href="<?php echo $link['latest']; ?>"><?php echo $_LANG['LATEST']; ?></a></div>
    <div class="button <?php if ($link['selected'] == 'positive') { ?>selected<?php } ?>"><a rel=”nofollow” href="<?php echo $link['positive']; ?>"><?php echo $_LANG['POSITIVE']; ?></a></div>
    <div class="button <?php if ($link['selected'] == 'rating') { ?>selected<?php } ?>"><a rel=”nofollow” href="<?php echo $link['rating']; ?>"><?php echo $_LANG['RATING']; ?></a></div>
    <?php if ($link['selected'] == 'group') { ?>
        <div class="button selected"><a rel=”nofollow” href="<?php echo $link['group']; ?>"><?php echo $_LANG['GROUP_SEARCH_NAME']; ?></a></div>
    <?php } ?>
  </div>
  <div class="users_list">
    <table width="100%" cellspacing="0" cellpadding="0" class="users_list">
      <?php if ($total) { ?>
      <?php foreach($users as $usr) { ?>
      <tr>
        <td width="80" valign="top"><div class="avatar"><a href="<?php echo cmsUser::getProfileURL($usr['login']); ?>"><img alt="<?php echo $this->escape($usr['nickname']); ?>" class="usr_img_small" src="<?php echo $usr['avatar']; ?>" /></a></div></td>
        <td valign="top">
        	<?php if ($link['selected'] == 'rating') { ?>
                    <div class="rating" title="<?php echo $_LANG['RATING']; ?>"><?php echo $this->rating($usr['rating']); ?></div>
          	<?php } ?>
          	<?php if ($link['selected'] == 'positive') { ?>
                    <div title="<?php echo $_LANG['KARMA']; ?>" class="karma<?php if ($usr['karma'] > 0) { ?> pos<?php } ?><?php if ($usr['karma'] < 0) { ?> neg<?php } ?>"><?php if ($usr['karma'] > 0) { ?>+<?php } ?><?php echo $usr['karma']; ?></div>
          	<?php } ?>
          <div class="status">
          	<?php if ($usr['is_online']) { ?>
            	<span class="online"><?php echo $_LANG['ONLINE']; ?></span>
            <?php } else { ?>
            	<span class="offline"><?php echo $usr['flogdate']; ?></span>
            <?php } ?>
          </div>
          <div class="nickname"><?php echo $usr['user_link']; ?></div>
          <?php if ($usr['microstatus']) { ?>
          <div class="microstatus"><?php echo $usr['microstatus']; ?></div>
          <?php } ?> </td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td><p><?php echo $_LANG['USERS_NOT_FOUND']; ?>.</p></td>
      </tr>
      <?php } ?>
    </table>
  </div>
  <?php echo $pagebar; ?>