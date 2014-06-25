<?php if ($comments_count) { ?> 
    <?php foreach($comments as $cid=>$comment) { ?>
        <?php $next = $cid + 1; ?>
        <a name="c<?php echo $comment['id']; ?>"></a>
        <?php if ($comment['level'] < $cfg['max_level']-1) { ?>
            <div style="margin-left:<?php echo $comment['level']*35; ?>px;">
        <?php } else { ?>
            <div style="margin-left:<?php echo ($comment['max_level']-1)*35; ?>px;">
        <?php } ?>
        <table class="cmm_entry">
            <tr>
                <td class="cmm_title" valign="middle">
                    <?php if (!$comment['is_profile']) { ?>
                        <span class="cmm_author"><?php echo $comment['author']; ?> <?php if ($is_admin && $comment['ip']) { ?>(<?php echo $comment['ip']; ?>)<?php } ?></span>
                    <?php } else { ?>
                        <span class="cmm_author"><a href="{profile_url login=$comment.author.login}"><?php echo $comment['author']['nickname']; ?></a> <?php if ($is_admin && $comment['ip']) { ?>(<?php echo $comment['ip']; ?>)<?php } ?></span>
                    <?php } ?>
                    <a class="cmm_anchor" href="#c<?php echo $comment['id']; ?>" title="<?php echo $_LANG['LINK_TO_COMMENT']; ?>">#</a>
                    <span class="cmm_date"><?php if ($comment['published']) { ?><?php echo $comment['fpubdate']; ?><?php } else { ?><span style="color:#F00"><?php echo $_LANG['WAIT_MODERING']; ?></span><?php } ?></span>
                    <?php if (!$is_user || $comment['is_voted'] || !$comment['is_profile']) { ?>
                        <span class="cmm_votes">
                        <?php if ($comment['rating'] > 0) { ?>
                            <span class="cmm_good">+<?php echo $comment['rating']; ?></span>
                        <?php } else if ($comment['rating'] < 0) { ?>
                            <span class="cmm_bad"><?php echo $comment['rating']; ?></span>
                        <?php } else { ?>
                            <?php echo $comment['rating']; ?>
                        <?php } ?>
                        </span>
                    <?php } else { ?>
                        <span class="cmm_votes" id="votes<?php echo $comment['id']; ?>">
                            <table border="0" cellpadding="0" cellspacing="0"><tr>
                            <td><?php echo $this->rating($comment['rating']); ?></td>
                            <td><a href="javascript:void(0);" onclick="voteComment(<?php echo $comment['id']; ?>, -1);" title="<?php echo $_LANG['BAD_COMMENT']; ?>"><img border="0" alt="-" src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/comments/vote_down.gif" style="margin-left:8px"/></a></td>
                            <td><a href="javascript:void(0);" onclick="voteComment(<?php echo $comment['id']; ?>, 1);" title="<?php echo $_LANG['GOOD_COMMENT']; ?>"><img border="0" alt="+" src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/comments/vote_up.gif" style="margin-left:2px"/></a></td>
                            </tr></table>
                        </span>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <?php if ($comment['is_profile']) { ?>
                <td valign="top">
                    <table width="100%" cellpadding="1" cellspacing="0">
                        <tr>
                            <td width="70" height="70"  align="center" valign="top" class="cmm_avatar">
                                <a href="<?php echo cmsUser::getProfileURL($comment['author']['login']); ?>"><img border="0" class="usr_img_small" src="<?php echo $comment['user_image']; ?>" /></a>
                            </td>
                            <td class="cmm_content_av" valign="top">
                <?php } else { ?>
                            <td class="cmm_content" valign="top">
                <?php } ?>
                                <div id="cm_msg_<?php echo $comment['id']; ?>">
                                    <?php if ($comment['show']) { ?>
                                        <?php echo $comment['content']; ?>
                                    <?php } else { ?>
                                        <a href="javascript:void(0)" onclick="expandComment(<?php echo $comment['id']; ?>)" id="expandlink<?php echo $comment['id']; ?>"><?php echo $_LANG['SHOW_COMMENT']; ?></a>
                                        <div id="expandblock<?php echo $comment['id']; ?>" style="display:none"><?php echo $comment['content']; ?></div>
                                    <?php } ?>
                                </div>

                    <div style="margin-top:15px;">
                        <span id="cm_add_link<?php echo $comment['id']; ?>" class="cm_add_link"><a href="javascript:void(0)" onclick="addComment('<?php echo $this->escape($target); ?>', '<?php echo $target_id; ?>', <?php echo $comment['id']; ?>)" class="ajaxlink"><?php echo $_LANG['REPLY']; ?></a></span>
                        <?php if ($is_user) { ?>
                            <?php if ($is_admin || ($comment['is_my'] && $comment['is_editable'] && $comment['content_bbcode']) || ($user_can_moderate && $comment['content_bbcode'])) { ?>
                                <?php if (!$comment['content_bbcode']) { ?>
                                    <span class="left_border"><a href="/admin/index.php?view=components&do=config&link=comments&opt=edit&item_id=<?php echo $comment['id']; ?>"><?php echo $_LANG['EDIT']; ?></a></span>
                                <?php } else { ?>
                                   <span class="left_border"><a href="javascript:" onclick="editComment('<?php echo $comment['id']; ?>', '<?php echo cmsUser::getCsrfToken(); ?>')" class="ajaxlink"><?php echo $_LANG['EDIT']; ?></a></span>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($is_admin || ($comment['is_my'] && $user_can_delete) || $user_can_moderate) { ?>
                                <span class="left_border"><a href="javascript:" onclick="deleteComment(<?php echo $comment['id']; ?>, '<?php echo cmsUser::getCsrfToken(); ?>'<?php if ($comments[$next]['level'] > $comment['level']) { ?>, 1<?php } ?>);return false;" class="ajaxlink"><?php if ($comments[$next]['level'] > $comment['level']) { ?><?php echo $_LANG['DELETE_BRANCH']; ?><?php } else { ?><?php echo $_LANG['DELETE']; ?><?php } ?></a></span>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <?php if ($comment['is_profile']) { ?>
                        </td></tr></table>
                    <?php } ?>
					</td>
				</tr>
			</table>
            <div id="cm_addentry<?php echo $comment['id']; ?>" class="reply" style="display:none"></div>
        </div>
	<?php } ?>

<?php } else { ?>
	<p><?php echo $labels['not_comments']; ?></p>
<?php } ?>