<?php cmsCore::c('page')->addHeadJS('includes/jquery/tabs/jquery.ui.min.js'); ?>
<?php cmsCore::c('page')->addHeadCSS('includes/jquery/tabs/tabs.css'); ?>

<script type="text/javascript">
    $(function(){$(".uitabs").tabs();});
</script>

<div id="usertitle">

    <div id="user_ratings">
        <div class="karma" title="<?php echo $_LANG['KARMA']; ?>">
        	<div class="<?php if ($usr['karma'] >= 0) { ?>value-positive<?php } else { ?>value-negative<?php } ?>" id="u_karma_cont">
                <table cellpadding="2" cellspacing="0"><tr>
                    <td class="sign_link" style="color:green">
                    <?php if ($usr['can_change_karma']) { ?>
                        <a href="javascript:void(0)" onclick="users.changeKarma('<?php echo $usr['id']; ?>', 'plus');return false;" title="<?php echo $_LANG['KARMA']; ?> +"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/karma_up.png" alt="<?php echo $_LANG['KARMA']; ?> +"/></a>
                    <?php } ?>
                    </td>
                    <td><span class="user_karma_point" id="u_karma"><?php echo $usr['karma']; ?></span></td>
                    <td style="color:red" class="sign_link">
                    <?php if ($usr['can_change_karma']) { ?>
                        <a href="javascript:void(0)" onclick="users.changeKarma('<?php echo $usr['id']; ?>', 'minus'); return false;" title="<?php echo $_LANG['KARMA']; ?> -"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/karma_down.png" border="0" alt="<?php echo $_LANG['KARMA']; ?> -"/></a>
                    <?php } ?>
                    </td>
                </tr></table>
            </div>
        </div>
        <div class="rating" title="<?php echo $_LANG['RATING']; ?>">
            <div class="value"><?php echo $usr['user_rating']; ?></div>
        </div>
    </div>

    <div class="user_group_name">
        <div class="<?php echo $usr['group_alias']; ?>"><a href="/users/group/<?php echo $usr['group_id']; ?>"><?php echo $usr['grp']; ?></a></div>
    </div>

    <div class="con_heading" id="nickname">
        <?php echo $usr['nickname']; ?> <?php if ($usr['banned']) { ?><span style="color:red; font-size:12px;"><?php echo $_LANG['USER_IN_BANLIST']; ?></span><?php } ?>
    </div>

</div>

<div class="usr_status_bar">
    <div class="usr_status_text" <?php if (!$usr['status_text']) { ?>style="display:none"<?php } ?>>
        <span><?php echo $this->escape($usr['status_text']); ?></span>
        <span class="usr_status_date" >// <?php echo $usr['status_date']; ?> <?php echo $_LANG['BACK']; ?></span>
    </div>
    <?php if ($myprofile || $is_admin) { ?>
        <div class="usr_status_link"><a href="javascript:" onclick="setStatus(<?php echo $usr['id']; ?>)"><?php echo $_LANG['CHANGE_STATUS']; ?></a></div>
    <?php } ?>
</div>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:14px">
    <tr>
        <td width="200" valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center" valign="middle">
                        <div class="usr_avatar">
                            <img alt="<?php echo $this->escape($usr['nickname']); ?>" class="usr_img" src="<?php echo $usr['avatar']; ?>" />
                        </div>
                        <?php if ($is_auth) { ?>
                        <div id="usermenu" style="">
                            <div class="usr_profile_menu">
                                <table cellpadding="0" cellspacing="6" >
                                    <?php if (!$myprofile) { ?>
                                    <tr>
                                        <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/profile/message.png" border="0"/></td>
                                        <td><a class="ajaxlink" href="javascript:void(0)" title="<?php echo $_LANG['WRITE_MESS']; ?>: <?php echo $this->escape($usr['nickname']); ?>" onclick="users.sendMess('<?php echo $usr['id']; ?>', 0, this);return false;"><?php echo $_LANG['WRITE_MESS']; ?></a></td>
                                    </tr>
                                    <?php } ?>
                                        
                                    <?php if (!$myprofile) { ?>
                                        <?php if (!$usr['isfriend']) { ?>
                                        <tr>
                                            <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/profile/friends.png" border="0"/></td>
                                            <td><a class="ajaxlink" href="javascript:void(0)" title="<?php echo $this->escape($usr['nickname']); ?>" onclick="users.addFriend('<?php echo $usr['id']; ?>', this);return false;"><?php echo $_LANG['ADD_TO_FRIEND']; ?></a></td>
                                        </tr>
                                        <?php } else { ?>
                                        <tr>
                                            <td class="add_friend_ajax" style="display: none;"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/profile/friends.png" border="0"/></td>
                                            <td class="add_friend_ajax" style="display: none;"><a class="ajaxlink" href="javascript:void(0)" title="<?php echo $this->escape($usr['nickname']); ?>" onclick="users.addFriend('<?php echo $usr['id']; ?>', this);return false;"><?php echo $_LANG['ADD_TO_FRIEND']; ?></a></td>
                                            <td class="del_friend_ajax"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/profile/nofriends.png" border="0"/></td>
                                            <td class="del_friend_ajax"><a id="del_friend" class="ajaxlink" href="javascript:void(0)" title="<?php echo $this->escape($usr['nickname']); ?>" onclick="users.delFriend('<?php echo $usr['id']; ?>', this);return false;"><?php echo $_LANG['STOP_FRIENDLY']; ?></a></td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                    
                                    <?php if ($myprofile) { ?>
                                        <?php if ($cfg['sw_msg']) { ?>
                                            <tr>
                                                <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/profile/message.png" border="0"/></td>
                                                <td><a href="/users/<?php echo $usr['id']; ?>/messages.html" title="<?php echo $_LANG['MY_MESS']; ?>"><?php echo $_LANG['MY_MESS']; ?></a></td>
                                            </tr>
                                        <?php } ?>
                                        <?php if ($cfg['sw_photo']) { ?>
                                            <tr>
                                                <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/profile/addphoto.png" border="0"/></td>
                                                <td><a href="/users/addphoto.html" title="<?php echo $_LANG['ADD_PHOTO']; ?>"><?php echo $_LANG['ADD_PHOTO']; ?></a></td>
                                            </tr>
                                        <?php } ?>
                                            <tr>
                                                <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/profile/avatar.png" border="0"/></td>
                                                <td><a href="/users/<?php echo $usr['id']; ?>/avatar.html" title="<?php echo $_LANG['SET_AVATAR']; ?>"><?php echo $_LANG['SET_AVATAR']; ?></a></td>
                                            </tr>
                                        <?php if ($usr['invites_count']) { ?>
                                            <tr>
                                                <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/profile/invites.png" border="0"/></td>
                                                <td><a href="/users/invites.html" title="<?php echo $_LANG['MY_INVITES']; ?>"><?php echo $_LANG['MY_INVITES']; ?></a> <?php echo $usr['invites_count']; ?></td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/profile/edit.png" border="0"/></td>
                                            <td><a href="/users/<?php echo $usr['id']; ?>/editprofile.html" title="<?php echo $_LANG['CONFIG_PROFILE']; ?>"><?php echo $_LANG['MY_CONFIG']; ?></a></td>
                                        </tr>
                                    <?php } ?>
                                        
                                    <?php if ($is_admin && !$myprofile) { ?>
                                        <tr>
                                            <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/profile/edit.png" border="0"/></td>
                                            <td><a href="/users/<?php echo $usr['id']; ?>/editprofile.html" title="<?php echo $_LANG['CONFIG_PROFILE']; ?>"><?php echo $_LANG['CONFIG_PROFILE']; ?></a></td>
                                        </tr>
                                    <?php } ?>
                                    
                                    <tr>
                                        <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/profile/karma.png" border="0"/></td>
                                        <td><a href="/users/<?php echo $usr['id']; ?>/karma.html" title="<?php echo $_LANG['KARMA_HISTORY']; ?>"><?php echo $_LANG['KARMA_HISTORY']; ?></a></td>
                                    </tr>
                                    
                                    <?php if (!$myprofile) { ?>
                                        <?php if ($is_admin) { ?>
                                            <?php if (!$usr['banned']) { ?>
                                                <tr>
                                                    <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/profile/award.png" border="0"/></td>
                                                    <td><a href="/users/<?php echo $usr['id']; ?>/giveaward.html" title="<?php echo $_LANG['TO_AWARD']; ?>"><?php echo $_LANG['TO_AWARD']; ?></a></td>
                                                </tr>
                                                <?php if ($usr['id'] != 1) { ?>
                                                    <tr>
                                                        <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/profile/ban.png" border="0"/></td>
                                                        <td><a href="/admin/index.php?view=userbanlist&do=add&to=<?php echo $usr['id']; ?>" title="<?php echo $_LANG['TO_BANN']; ?>"><?php echo $_LANG['TO_BANN']; ?></a></td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if ($usr['id'] != 1) { ?>
                                                <tr>
                                                    <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/profile/delprofile.png" border="0"/></td>
                                                    <td><a href="/users/<?php echo $usr['id']; ?>/delprofile.html" title="<?php echo $_LANG['DEL_PROFILE']; ?>"><?php echo $_LANG['DEL_PROFILE']; ?></a></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                        <?php } ?>
                    </td>
                </tr>
            </table>
            
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <div id="user_profile_url">
                            <div><?php echo $_LANG['LINK_TO_THIS_PAGE']; ?>:</div>
                            <a href="<?php echo $usr['profile_link']; ?>" title="<?php echo $this->escape($usr['nickname']); ?>"><?php echo $usr['profile_link']; ?></a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    	<td valign="top" style="padding-left:10px">
            <div id="profiletabs" class="uitabs">
                <ul id="tabs">
                    <li><a href="#upr_profile"><span><?php echo $_LANG['PROFILE']; ?></span></a></li>
                    <?php if ($myprofile && $cfg['sw_feed']) { ?>
                        <li><a href="/actions/my_friends" title="upr_feed"><span><?php echo $_LANG['FEED']; ?></span></a></li>
                    <?php } ?>
                    <?php if ($cfg['sw_clubs']) { ?>
                        <li><a href="/clubs/by_user_<?php echo $usr['id']; ?>" title="upr_clubs"><span><?php echo $_LANG['CLUBS']; ?></span></a></li>
                    <?php } ?>
                    <?php if ($cfg['sw_awards']) { ?>
                        <li><a href="#upr_awards"><span><?php echo $_LANG['AWARDS']; ?></span></a></li>
                    <?php } ?>
                    <?php foreach($plugins as $plugin) { ?>
                        <li><a href="<?php if ($plugin['ajax_link']) { ?><?php echo $plugin['ajax_link']; ?><?php } else { ?>#upr_<?php echo $plugin['name']; ?><?php } ?>" title="<?php echo $plugin['name']; ?>"><span><?php echo $plugin['title']; ?></span></a></li>
                    <?php } ?>
                </ul>
                
                <div id="upr_profile">
                    <div class="user_profile_data">
                        <div class="field">
                            <div class="title"><?php echo $_LANG['LAST_VISIT']; ?>:</div>
                            <div class="value"><?php echo $usr['flogdate']; ?></div>
                        </div>
                        <div class="field">
                            <div class="title"><?php echo $_LANG['DATE_REGISTRATION']; ?>:</div>
                            <div class="value">
                                <?php echo $usr['fregdate']; ?>
                            </div>
                        </div>
                        <?php if ($usr['inv_login']) { ?>
                            <div class="field">
                                <div class="title"><?php echo $_LANG['INVITED_BY']; ?>:</div>
                                <div class="value">
                                    <a href="<?php echo cmsUser::getProfileURL($usr['inv_login']); ?>"><?php echo $usr['inv_nickname']; ?></a>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <?php if ($usr['city']) { ?>
                            <div class="field">
                                <div class="title"><?php echo $_LANG['CITY']; ?>:</div>
                                <div class="value"><a href="/users/city/<?php echo $this->escape($usr['cityurl']); ?>"><?php echo $usr['city']; ?></a><?php if ($usr['country']) { echo ', '. $usr['country']; } ?></div>
                            </div>
                        <?php } ?>
                        
                        <?php if ($usr['showbirth'] && $usr['fbirthdate']) { ?>
                            <div class="field">
                                <div class="title"><?php echo $_LANG['BIRTH']; ?>:</div>
                                <div class="value"><?php echo $usr['fbirthdate']; ?></div>
                            </div>
                        <?php } ?>
                        
                        <?php if ($usr['fgender']) { ?>
                            <div class="field">
                                <div class="title"><?php echo $_LANG['SEX']; ?>:</div>
                                <div class="value"><?php echo $usr['fgender']; ?></div>
                            </div>
                        <?php } ?>
                        
                        <?php if ($usr['showicq'] && $usr['icq']) { ?>
                            <div class="field">
                                <div class="title">ICQ:</div>
                                <div class="value"><?php echo $usr['icq']; ?></div>
                            </div>
                        <?php } ?>
                        
                        <?php if ($usr['showmail']) { ?>
                            <?php cmsCore::c('page')->addHeadJS('includes/jquery/jquery.nospam.js'); ?>
                            <div class="field">
                                <div class="title">E-mail:</div>
                                <div class="value"><a href="#" rel="<?php echo $this->NoSpam($usr['email']); ?>" class="email"><?php echo $usr['email']; ?></a></div>
                            </div>
                            <script>
                                $('.email').nospam({ replaceText: true });
                            </script>
                        <?php } ?>
                        
                        <div class="field">
                            <div class="title"><?php echo $_LANG['HOBBY']; ?> (<?php echo $_LANG['TAGSS']; ?>):</div>
                            <div class="value"><?php if ($usr['fdescription']) { ?><?php echo $usr['fdescription']; ?><?php } else { ?><span style="color:#999"><em><?php echo $_LANG['TAGS_NOT_SPEC']; ?></em></span><?php } ?></div>
                        </div>
                        
                        <?php if ($cfg['privforms'] && $usr['form_fields']) { ?>
                            <?php foreach($usr['form_fields'] as $field) { ?>
                                <div class="field">
                                    <div class="title"><?php echo $field['title']; ?>:</div>
                                    <div class="value"><?php if ($field['field']) { ?><?php echo $field['field']; ?><?php } else { ?><em><?php echo $_LANG['NOT_SET']; ?></em><?php } ?></div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    
                    <div>
                        <div class="usr_profile_block">
                            <div class="usr_wall_header">
                                <?php if (!$myprofile) { ?>
                                    <?php echo $_LANG['USER_CONTENT']; ?>
                                <?php } else { ?>
                                    <?php echo $_LANG['MY_CONTENT']; ?>
                                <?php } ?>
                            </div>
                            <div id="usr_links">
                                <?php if ($cfg['sw_blogs']) { ?>
                                    <?php if ($usr['blog']) { ?>
                                        <div id="usr_blog"><a href="/blogs/<?php echo $usr['blog']['seolink']; ?>" title="{$usr.blog.title|escape:'html'}"><?php echo $_LANG['BLOG']; ?></a></div>
                                    <?php } else if ($myprofile) { ?>
                                        <div id="usr_blog"><a href="/blogs/createblog.html"><?php echo $_LANG['CREATE_BLOG']; ?></a></div>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($cfg['sw_files']) { ?>
                                    <div id="usr_files">
                                        <a href="/users/<?php echo $usr['id']; ?>/files.html"><?php echo $_LANG['FILES']; ?></a> <sup><?php echo $usr['files_count']; ?></sup>
                                    </div>
                                <?php } ?>
                                <?php if ($cfg['sw_board'] && $usr['board_count']) { ?>
                                    <div id="usr_board">
                                        <a href="/board/by_user_<?php echo $usr['login']; ?>"><?php echo $_LANG['ADVS']; ?></a> <sup><?php echo $usr['board_count']; ?></sup>
                                    </div>
                                <?php } ?>
                                <?php if ($cfg['sw_forum'] && $cfg_forum['component_enabled'] && $usr['forum_count']) { ?>
                                    <div id="usr_forum">
                                        <a href="/forum/<?php echo $usr['login']; ?>_activity.html" title="<?php echo $_LANG['MY_ACTIVITY_ON_FORUM']; ?>"><?php echo $_LANG['FORUM']; ?></a> <sup title="<?php echo $_LANG['MESS_IN_FORUM']; ?>"><?php echo $usr['forum_count']; ?></sup>
                                    </div>
                                <?php } ?>
                                <?php if ($cfg['sw_comm'] && $usr['comments_count']) { ?>
                                    <div id="usr_comments">
                                        <a href="/comments/by_user_<?php echo $usr['login']; ?>" title="<?php echo $_LANG['READ']; ?>"><?php echo $_LANG['COMMENTS']; ?></a> <sup><?php echo $usr['comments_count']; ?></sup>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <?php if ($usr['albums']) { ?>
                            <div class="usr_albums_block usr_profile_block">
                                <?php if ($usr['albums_total'] > $usr['albums_show']) { ?>
                                    <div class="float_bar">
                                        <a href="/users/<?php echo $usr['id']; ?>/photoalbum.html"><?php echo $_LANG['ALL_ALBUMS']; ?></a> (<?php echo $usr['albums_total']; ?>)
                                    </div>
                                <?php } ?>
                                <div class="usr_wall_header">
                                    <?php if (!$myprofile) { ?>
                                        <?php echo $_LANG['USER_PHOTOS']; ?>
                                    <?php } else { ?>
                                        <?php echo $_LANG['MY_PHOTOS']; ?>
                                    <?php } ?>
                                </div>
                                <ul class="usr_albums_list">
                                    <?php foreach($usr['albums'] as $album) { ?>
                                        <li>
                                            <div class="usr_album_thumb">
                                                <a href="/users/<?php echo $usr['login']; ?>/photos/<?php echo $album['type']; ?><?php echo $album['id']; ?>.html" title="<?php echo $this->escape($album['title']); ?>">
                                                    <img src="<?php echo $album['imageurl']; ?>" width="64" height="64" border="0" alt="<?php echo $this->escape($album['title']); ?>" />
                                                </a>
                                            </div>
                                            <div class="usr_album">
                                                <div class="link">
                                                    <a href="/users/<?php echo $usr['login']; ?>/photos/<?php echo $album['type']; ?><?php echo $album['id']; ?>.html"><?php echo $album['title']; ?></a>
                                                </div>
                                                <div class="count"><?php echo $this->spellcount($album['photos_count'], $_LANG['PHOTO'], $_LANG['PHOTO2'], $_LANG['PHOTO10']); ?></div>
                                                <div class="date"><?php echo $album['pubdate']; ?></div>
                                            </div>
                                        </li>
                                    <?php } ?>
                                 </ul>
                            </div>
                        <?php } ?>

                        <?php if ($usr['friends']) { ?>
                            <div class="usr_friends_block usr_profile_block">
                                <?php if ($usr['friends_total'] > 6) { ?>
                                    <div class="float_bar">
                                        <a href="/users/<?php echo $usr['id']; ?>/friendlist.html"><?php echo $_LANG['ALL_FRIENDS']; ?></a> (<?php echo $usr['friends_total']; ?>)
                                    </div>
                                <?php } ?>
                                <div class="usr_wall_header">
                                    <?php if (!$myprofile) { ?>
                                        <?php echo $_LANG['USER_FRIENDS']; ?>
                                    <?php } else { ?>
                                        <?php echo $_LANG['MY_FRIENDS']; ?>
                                    <?php } ?>
                                </div>
                                <?php $col = 1; ?>
                                <table width="" cellpadding="5" cellspacing="0" border="0" class="usr_friends_list" align="left">
                                  <?php foreach($usr['friends'] as $friend) { ?>
                                  <?php if ($col == 1) { ?><tr><?php } ?>
                                        <td align="center" valign="top">
                                            <div class="usr_friend_cell">
                                                <div align="center"><a class="friend_link" href="<?php echo cmsUser::getProfileURL($friend['login']); ?>"><?php echo $friend['nickname']; ?></a></div>
                                                <div align="center"><a href="<?php echo cmsUser::getProfileURL($friend['login']); ?>"><img border="0" class="usr_img_small" src="<?php echo $friend['avatar']; ?>" /></a></div>
                                                <div align="center"><?php echo $friend['flogdate']; ?></div>
                                            </div>
                                        </td>

                                      <?php if ($col == 6) { echo '</tr>'; $col = 1; } else { $col++; } ?>
                                  <?php } ?>
                                  <?php if ($col > 1) { ?><td colspan="<?php echo 7-$col; ?>">&nbsp;</td></tr><?php } ?>
                                </table>
                            </div>
                        <?php } ?>
                        
                        <?php if ($cfg['sw_wall']) { ?>
                            <div class="usr_wall usr_profile_block">
                                <div class="usr_wall_header">
                                    <?php echo $_LANG['USER_WALL']; ?>
                                    <div class="usr_wall_addlink" style="float:right">
                                        <a href="javascript:void(0)" id="addlink" class="ajaxlink" onclick="addWall('users', '<?php echo $usr['id']; ?>');return false;">
                                            <span><?php echo $_LANG['WRITE_ON_WALL']; ?></span>
                                        </a>
                                    </div>
                                </div>
                                <div class="usr_wall_body" style="clear:both">
                                    <div class="wall_body"><?php echo $usr['wall_html']; ?></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php if ($myprofile && $cfg['sw_feed']) { ?>
                    <div id="upr_feed"></div>
                <?php } ?>

                <?php if ($cfg['sw_clubs']) { ?>
                    <div id="upr_clubs"></div>
                <?php } ?>

                <?php if ($cfg['sw_awards']) { ?>
                    <div id="upr_awards">
                        <div class="awards_list_link">
                            <a href="/users/awardslist.html"><?php echo $_LANG['HOW_GET_AWARD']; ?></a>
                        </div>
                        <?php if ($usr['awards']) { ?>
                            <?php foreach($usr['awards'] as $aw) { ?>
                                <div class="usr_award_block">
                                  <table style="width:100%; margin-bottom:2px;" cellspacing="2">
                                    <tr>
                                      <td class="usr_com_title"><strong><?php echo $aw['title']; ?></strong>
                                      <?php if ($is_admin) { ?>
                                        [<a href="/users/delaward<?php echo $aw['id']; ?>.html"><?php echo $_LANG['DELETE']; ?></a>]
                                      <?php } ?>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="usr_com_body">
                                        <table border="0" cellpadding="5" cellspacing="0">
                                          <tr>
                                            <td valign="top"><img src="/images/users/awards/<?php echo $aw['imageurl']; ?>" border="0" alt="<?php echo $this->escape($aw['title']); ?>"/></td>
                                            <td valign="top"><?php echo $aw['description']; ?>
                                              <div class="usr_award_date"><?php echo $aw['pubdate']; ?></div></td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php foreach($plugins as $plugin) { ?>
                    <div id="upr_<?php echo $plugin['name']; ?>"><?php echo $plugin['html']; ?></div>
                <?php } ?>
            </div>
	</td>
    </tr>
</table>