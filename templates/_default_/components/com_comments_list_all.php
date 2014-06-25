<h1 class="con_heading"><?php echo $page_title; ?> (<?php echo $comments_count; ?>)</h1>
<?php if ($comments_count) { ?>
    <?php foreach($comments as $comment) { ?>
    <h3 class="cmm_all_title"><span class="cmm_all_author"><?php if (!$comment['is_profile']) { ?><?php echo $comment['author']; ?><?php } else { ?><a href="{profile_url login=$comment.author.login}"><?php echo $comment['author']['nickname']; ?></a><?php } ?> <?php if ($is_admin) { ?><?php echo $comment['ip']; ?><?php } ?></span> <span class="cmm_all_gender"> <?php echo $comment['gender']; ?></span>  &rarr; <a class="cmm_all_target" href="<?php echo $comment['target_link']; ?>#c<?php echo $comment['id']; ?>" title="<?php echo $_LANG['LINK_TO_COMMENT']; ?>"><?php echo $comment['target_title']; ?></a> <span class="cmm_date"><?php if ($comment['published']) { ?><?php echo $comment['fpubdate']; ?><?php } else { ?><span style="color:#F00"><?php echo $_LANG['WAIT_MODERING']; ?></span><?php } ?></span></h3>
    <table class="cmm_entry">
        <tr>
            <?php if ($comment['is_profile']) { ?>
            <td valign="top">
                <table width="100%" cellpadding="1" cellspacing="0">
                    <tr>
                        <td width="70" height="70"  align="center" valign="top" class="cmm_avatar">
                            <a href="{profile_url login=$comment.author.login}"><img border="0" class="usr_img_small" src="<?php echo $comment['user_image']; ?>" /></a>
                        </td>
                        <td class="cmm_content_av" valign="top">
            <?php } else { ?>
                        <td class="cmm_all_content" valign="top">
            <?php } ?>
                        <?php if ($comment['show']) { ?>
                            <?php echo $comment['content']; ?>
                        <?php } else { ?>
                            <a href="javascript:void(0)" onclick="expandComment(<?php echo $comment['id']; ?>)" id="expandlink<?php echo $comment['id']; ?>"><?php echo $_LANG['SHOW_COMMENT']; ?></a>
                            <div id="expandblock<?php echo $comment['id']; ?>" style="display:none"><?php echo $comment['content']; ?></div>
                        <?php } ?>
                        <?php if ($comment['is_profile']) { ?>
                            </td></tr></table>
                        <?php } ?>
                    </td>
                    <td align="right" valign="middle"><span class="cmm_all_votes" style="font-size:18px;"><?php echo $this->rating($comment['rating']); ?></span></td>
        </tr>
    </table>
	<?php } ?>
<?php echo $pagebar; ?>
<?php } else { ?>
	<p><?php echo $_LANG['NOT_COMMENT_TEXT']; ?></p>
<?php } ?>