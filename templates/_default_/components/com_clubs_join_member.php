<div class="friend_list_top">
    <input id="friend_list_lookup" class="inputText" type="text"  onkeydown="clubs.intive_search();" placeholder="<?php echo $_LANG['SEARCH']; ?>" />
  <div id="list_tab" class="t_filter_selected" onclick="clubs.intive_filter('all')">
    <div class="t_filter2">
      <div class="t_filter3"><?php echo $_LANG['ALL_MEMBER']; ?></div>
    </div>
  </div>
  <div id="list_selected_tab" class="t_filter_off" onclick="clubs.intive_filter('checked')">
    <div class="t_filter2">
      <div class="t_filter3"><?php echo $_LANG['SELECTED_ITEMS']; ?> (<span id="count_friends">0</span>)</div>
    </div>
  </div>
</div>
<div class="clearFix"></div>
<div class="friend_list_body_ajax">
  <div id="flist_data">
  <?php foreach($friends as $friend) { ?>
    <div id="flist<?php echo $friend['id']; ?>" class="flist_cell" onclick="clubs.intive_click(<?php echo $friend['id']; ?>);" value="<?php echo $friend['id']; ?>">
      <div class="flist_border_wrap">
        <div class="flist_wrap">
          <div class="flist_div">
            <div class="flist_image">
              <img class="usr_img_small" border="0" src="<?php echo $friend['avatar']; ?>" />
            </div>
          </div>
          <div class="flist_name"><?php echo $friend['nickname']; ?></div>
        </div>
      </div>
    </div>
  <?php } ?>
  </div>
</div>