<div class="con_heading"><?php echo $club['title']; ?></div>

<?php if ($is_access) { ?>

<table class="club_full_entry" cellpadding="0" cellspacing="0">
    <tr>
        <td valign="top" class="left">
            <div class="image"><img src="/images/clubs/<?php echo $club['f_imageurl']; ?>" border="0"/></div>
            <div class="members_list">
                <div class="title"><?php echo $_LANG['CLUB_ADMINISTRATION']; ?>:</div>
                <div class="list"><a href="<?php echo cmsUser::getProfileURL($club['login']); ?>"><img border="0" class="usr_img_small" src="<?php echo $club['admin_avatar']; ?>" style="float:left; margin: 0 7px 0 0;" /> <?php echo $club['nickname']; ?></a><br /><em style="font-size:10px"><?php echo $_LANG['CLUB_ADMIN']; ?></em><br /><?php echo $club['flogdate']; ?></div>
                <?php if ($club['moderators']) { ?>
                    <?php foreach($club['moderators_list'] as $moderator) { ?>
			<div class="list"><a href="<?php echo cmsUser::getProfileURL($moderator['login']); ?>"><img border="0" class="usr_img_small" src="<?php echo $moderator['admin_avatar']; ?>" style="float:left; margin: 0 7px 0 0;" /> <?php echo $moderator['nickname']; ?></a><br /><em style="font-size:10px"><?php echo $_LANG['MODERATOR']; ?></em><?php if ($moderator['is_online']) { ?><br><span class="online"><?php echo $_LANG['ONLINE']; ?></span><?php } ?></div>
                    <?php } ?>
                <?php } ?>
            </div>
            <?php if ($club['members_list']) { ?>
                <div class="members_list">
                    <div class="title">
                    	<?php if ($club['members'] - $club['moderators'] > $cfg['club_perpage']) { ?>
                            <a href="/clubs/<?php echo $club['id']; ?>/members-1"><?php echo $_LANG['CLUB_MEMBERS']; ?> (<?php echo $club['members']-$club['moderators'];?>):</a>
                        <?php } else { ?>
                            <?php echo $_LANG['CLUB_MEMBERS']; ?> (<?php echo $club['members']-$club['moderators'];?>):
                        <?php } ?>
                    </div>
                    <div class="list">
                        <?php foreach($club['members_list'] as $member) { ?>
                            <div class="member_list" align="center"><a href="<?php echo cmsUser::getProfileURL($member['login']); ?>"><img border="0" class="usr_img_small" src="<?php echo $member['admin_avatar']; ?>" /></a><br /><a href="<?php echo cmsUser::getProfileURL($member['login']); ?>" title="<?php echo $this->escape($member['nickname']); ?>"><?php echo $this->truncate($member['nickname'], 8); ?></a><?php if ($member['is_online']) { ?><span class="online"><?php echo $_LANG['ONLINE']; ?></span><?php } ?></div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </td>
        <td valign="top">
            <div class="data">
                <div class="details">
                    <?php if ($club['is_vip']) { ?>
                        <span class="vip"><strong><?php echo $_LANG['VIP_CLUB']; ?></strong></span>
                    <?php } else { ?>
                        <span class="rating"><strong><?php echo $_LANG['RATING']; ?>:</strong> <?php echo $club['rating']; ?></span>
                    <?php } ?>
                    <span class="members"><strong><?php echo $this->spellcount($club['members']+1, $_LANG['CLUB_USER'], $_LANG['CLUB_USER2'], $_LANG['CLUB_USER10']); ?></strong></span>
                    <span class="date"><?php echo $club['fpubdate']; ?></span>
                </div>
                <div class="description">
                    <?php echo $club['description']; ?>
                </div>
                <?php if ($is_member || $is_admin || $is_moder || $user_id) { ?>
                <div class="clubmenu">
                    <?php if ($is_admin) { ?>
                        <div class="config"><a  href="/clubs/<?php echo $club['id']; ?>/config.html"><?php echo $_LANG['CONFIG_CLUB']; ?></a></div>
                    	<div class="messages"><a class="ajaxlink" href="javascript:void(0)" onclick="clubs.sendMessages(<?php echo $club['id']; ?>);return false;" title="<?php echo $_LANG['SEND_MESSAGE_TO_MEMBERS']; ?>"><?php echo $_LANG['SEND_MESSAGE']; ?></a></div>
                    <?php } ?>
                    <?php if ($user_id) { ?>
                        <?php if (($is_member || $is_admin || $is_moder) && $club['clubtype'] == 'public') { ?>
                            <div class="invite"><a class="ajaxlink" href="javascript:void(0)" onclick="clubs.intive(<?php echo $club['id']; ?>);return false;"><?php echo $_LANG['INVITE']; ?></a></div>
                        <?php } ?>
                        <?php if ($is_member) { ?>
                            <div class="leave"><a class="ajaxlink" href="javascript:void(0)" onclick="clubs.leaveClub(<?php echo $club['id']; ?>, '<?php echo cmsUser::getCsrfToken(); ?>');return false;"><?php echo $_LANG['LEAVE_CLUB']; ?></a></div>
                        <?php } else if ($club['admin_id'] != $user_id) { ?>
                            <div class="join"><a class="ajaxlink" href="javascript:void(0)" onclick="clubs.joinClub(<?php echo $club['id']; ?>);return false;"><?php echo $_LANG['JOIN_CLUB']; ?></a></div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
            <div class="clubcontent">
                <?php if ($club['enabled_blogs']) { ?>
                <div class="blog">
                    <div class="title"><?php echo $_LANG['CLUB_BLOG']; ?></div>
                    <div class="content">
                    <?php if ($club['blog_posts']) { ?>
                        <?php foreach($club['blog_posts'] as $post) { ?>
                            <div class="club_blog_post">
                                <a href="<?php echo $post['url']; ?>" title="<?php echo $this->escape($post['title']); ?>" class="club_post_title"><?php echo $this->truncate($post['title'], 40); ?></a> &mdash;
                                <a href="<?php echo cmsUser::getProfileURL($post['login']); ?>" class="club_post_author"><?php echo $post['author']; ?></a>,
                                <span class="club_post_descr"><?php if (!$post['published']) { ?><span style="color:#CC0000"><?php echo $_LANG['ON_MODERATE']; ?></span><?php } else { ?><?php echo $post['fpubdate']; ?><?php } ?><?php if ($post['comments_count'] > 0) { ?>, <?php echo $this->spellcount($post['comments_count'], $_LANG['COMMENT'], $_LANG['COMMENT2'], $_LANG['COMMENT10']); ?><?php } ?></span>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="usr_albums_block">
                            <ul class="usr_albums_list">
                                <li class="no_albums"><?php echo $_LANG['NO_BLOG_POSTS']; ?></li>
                            </ul>
                        </div>
                    <?php } ?>

                    <p style="margin:0 0 5px 0">
                    	<span><a href="/clubs/<?php echo $club['id']; ?>_blog"><?php echo $_LANG['POSTS_RSS']; ?> (<?php echo $club['total_posts']; ?>)</a></span>
                        <?php if ($is_admin || $is_moder || $is_blog_karma_enabled) { ?>
                            <span><a href="/clubs/<?php echo $club['id']; ?>/newpost.html" class="service"><?php echo $_LANG['NEW_POST']; ?></a></span>
                        <?php } ?>
                    </p>

                    </div>
                </div>
                <?php } ?>
                
                <?php if ($club['enabled_photos']) { ?>
                <div class="album">
                    <div class="title"><?php echo $_LANG['PHOTOALBUMS']; ?></div>
                    <div class="content">
                        <div id="album_list"><?php include(PATH .'/templates/'. cmsCore::c('config')->template .'/components/com_clubs_albums.php'); ?></div>
                        <p>
                        <?php if ($club['all_albums'] > $cfg['club_album_perpage']) { ?>
                            <span><a href="/clubs/<?php echo $club['id']; ?>/photoalbums"><?php echo $_LANG['ALL_ALBUMS']; ?> (<strong id="count_photo"><?php echo $club['all_albums']; ?></strong>)</a></span>
                        <?php } ?>
                        <?php if ($is_admin || $is_moder || $is_photo_karma_enabled) { ?>
                            <span><a class="service ajaxlink" href="javascript:void(0)" onclick="clubs.addAlbum(<?php echo $club['id']; ?>);"><?php echo $_LANG['ADD_PHOTOALBUM']; ?></a></span>
                        <?php } ?>
                        </p>
                    </div>
                </div>
                <?php } ?>
                
                <?php if ($plugins) { ?>
                    <?php foreach($plugins as $plugin) { ?>
                    	<?php if (!is_array($plugin['html'])) { ?>
                            <div id="plugin_<?php echo $plugin['name']; ?>"><?php echo $plugin['html']; ?></div>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="wall">
                <div class="header">
                    <?php echo $_LANG['CLUB_WALL']; ?>
                    <div class="club_wall_addlink">
                        <a href="javascript:void(0)" id="addlink" class="ajaxlink" onclick="addWall('clubs', '<?php echo $club['id']; ?>');return false;">
                            <?php echo $_LANG['WRITE_ON_WALL']; ?>
                        </a>
                    </div>
                </div>
                <div class="body">
                    <div class="wall_body"><?php echo $club['wall_html']; ?></div>
                </div>
            </div>
        </td>
    </tr>
</table>

<?php } else { ?>
    <p><?php echo $_LANG['CLUB_PRIVATE']; ?></p>
    <p><?php echo $_LANG['CLUB_ADMIN']; ?>: <a href="<?php echo cmsUser::getProfileURL($club['login']); ?>"><?php echo $club['nickname']; ?></a></p>
<?php } ?>