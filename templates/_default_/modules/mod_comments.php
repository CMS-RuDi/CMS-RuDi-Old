<?php foreach ($comments as $comment) { ?>
    <div class="mod_com_line">
        <a class="mod_com_link" href="<?php echo $comment['target_link'] .'#c'. $comment['id']; ?>"><?php echo $this->truncate($this->strip_tags($comment['content']),90); ?></a> <?php if ($cfg['showtarg']) { echo $this->rating($comment['rating']); } ?>
    </div>
    <div class="mod_com_details">
        <?php if (!$comment['is_profile']) { echo $comment['author']; } else { ?>
            <a class="mod_com_userlink" href="<?php echo cmsUser::getProfileURL($comment['author']['login']); ?>"><?php echo $comment['author']['nickname']; ?></a>
        <?php } ?>
        <?php echo $comment['fpubdate']; ?><br/>
        <?php if ($cfg['showtarg']) { ?>
            <a class="mod_com_targetlink" href="<?php echo $comment['target_link']; ?>"><?php echo $comment['target_title']; ?></a>
        <?php } ?>
    </div>
<?php } ?>
<?php if ($cfg['showrss']) { ?>
    <div style="margin-top:15px"> <a href="/rss/comments/all/feed.rss" class="mod_latest_rss"><?php echo $_LANG['COMMENTS_RSS']; ?></a> </div>
<?php } ?>
<div style="margin-top:5px"> <a href="/comments" class="mod_com_all"><?php echo $_LANG['COMMENTS_ALL']; ?></a> </div>
