<div class="cmm_heading">
    <?php echo $labels['comments']; ?> (<span id="comments_count"><?php echo $comments_count; ?></span>)
</div>

<div class="cm_ajax_list">
<?php if (!$cfg['cmm_ajax']) { ?>
    <?php echo $html; ?>
<?php } ?>
</div>

<a name="c"></a>
<div class="cmm_links">
    <span id="cm_add_link0" class="cm_add_link add_comment">
        <a href="javascript:void(0);" onclick="<?php echo $add_comment_js; ?>" class="ajaxlink"><?php echo $labels['add']; ?></a>
    </span>
    <?php if ($cfg['subscribe']) { ?>
        <?php if ($is_user) { ?>
            <?php if (!$user_subscribed) { ?>
            <span class="subscribe">
                <a href="/subscribe/<?php echo $target; ?>/<?php echo $target_id; ?>"><?php echo $_LANG['SUBSCRIBE_TO_NEW']; ?></a>
            </span>
            <?php } else { ?>
            <span class="unsubscribe">
                <a href="/unsubscribe/<?php echo $target; ?>/<?php echo $target_id; ?>"><?php echo $_LANG['UNSUBSCRIBE']; ?></a>
            </span>
            <?php } ?>
        <?php } ?>
    <?php } ?>
    <?php if ($comments_count) { ?>
        <span class="cmm_rss">
            <a href="/rss/comments/<?php echo $target; ?>-<?php echo $target_id; ?>/feed.rss"><?php echo $labels['rss']; ?></a>
        </span>
    <?php } ?>
</div>
<div id="cm_addentry0"></div>

<script type="text/javascript">
    var target_author_can_delete = <?php echo $target_author_can_delete; ?>;
    <?php if ($cfg['cmm_ajax']) { ?>
        var anc = '';
        if (window.location.hash) {
            anc = window.location.hash;
        }
        $(function() {
            loadComments('<?php echo $target; ?>', <?php echo $target_id; ?>, anc);
        });
    <?php } ?>
</script>