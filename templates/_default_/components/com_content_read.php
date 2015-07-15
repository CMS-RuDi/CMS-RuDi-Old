<?php if ($article['showtitle']) { ?>
    <h1 class="con_heading"><?php echo $article['title']; ?></h1>
<?php } ?>

<?php if ($article['showdate']) { ?>
    <div class="con_pubdate">
        <?php if (!$article['published']) { ?><span style="color:#CC0000"><?php echo $_LANG['NO_PUBLISHED']; ?></span><?php } else { ?><?php echo $article['pubdate']; ?><?php } ?> - <a href="<?php echo cmsUser::getProfileURL($article['user_login']); ?>"><?php echo $article['author']; ?></a>
    </div>
<?php } ?>

<?php if ($is_pages) { ?>
    <div class="con_pt" id="pt">
        <span class="con_pt_heading">
            <a class="con_pt_hidelink" href="javascript:void;" onClick="$('#pt_list').toggle();"><?php echo $_LANG['CONTENT']; ?></a>
            <?php if ($cfg['pt_hide']) { ?> [<a href="javascript:void(0);" onclick="$('#pt').hide();"><?php echo $_LANG['HIDE']; ?></a>] <?php } ?>
        </span>
        <div id="pt_list" style="<?php if ($cfg['pt_disp']) { ?>display: block;<?php } else { ?>display: none;<?php } ?> width:100%">
            <div>
                <ul id="con_pt_list">
                <?php foreach($pt_pages as $tid => $pages) { ?>
                        <?php if ($tid+1 != $page) { ?>
                            <?php $key = $tid+1; ?>
                            <li><a href="<?php echo $pages['url']; ?>"><?php echo $pages['title']; ?></a></li>
                        <?php } else { ?>
                            <li><?php echo $pages['title']; ?></li>
                        <?php } ?>
                <?php } ?>
                <ul>
            </div>
        </div>
    </div>
<?php } ?>

<div class="con_text" style="overflow:hidden">
    <?php if ($article['image']) { ?>
        <div class="con_image" style="float:left;margin-top:10px;margin-right:20px;margin-bottom:20px">
            <img src="<?php echo $article['image']; ?>" alt="<?php echo $this->escape($article['title']); ?>"/>
        </div>
    <?php } ?>
    <?php echo $article['content']; ?>
</div>
    
<?php
if (!empty($fields)) {
    foreach ($fields as $field) {
        if (!empty($field['value'])) {
?>
        <div class="<?php echo 'field_'. $field['type'] .' field_'. $field['type'] .'_'. $field['name']; ?>">
            <?php echo $field['value']; ?>
        </div>
<?php
        }
    }
}
?>

<?php if ($is_admin || $is_editor || $is_author) { ?>
    <div class="blog_comments">
        <?php if (!$article['published'] && ($is_admin || $is_editor)) { ?>
            <a class="blog_moderate_yes" href="/content/publish<?php echo $article['id']; ?>.html"><?php echo $_LANG['ARTICLE_ALLOW']; ?></a> |
        <?php } ?>
        <?php if ($is_admin || $is_editor || $is_author_del) { ?>
            <a class="blog_moderate_no" href="/content/delete<?php echo $article['id']; ?>.html"><?php echo $_LANG['DELETE']; ?></a> |
        <?php } ?>
        <?php if ($is_admin || $is_editor || $is_author) { ?>
            <a href="/content/edit<?php echo $article['id']; ?>.html" class="blog_entry_edit"><?php echo $_LANG['EDIT']; ?></a>
        <?php } ?>
    </div>
<?php } ?>

<?php if ($article['showtags']) { ?>
	<?php echo $tagbar; ?>
<?php } ?>

<?php if ($cfg['rating'] && $article['canrate']) { ?>
    <div id="con_rating_block">
        <div>
            <strong><?php echo $_LANG['RATING']; ?>: </strong><span id="karmapoints"><?php echo $karma_points; ?></span>
            <span style="padding-left:10px;color:#999"><strong><?php echo $_LANG['VOTES']; ?>:</strong> <?php echo $karma_votes; ?></span>
            <span style="padding-left:10px;color:#999"><?php echo $this->spellcount($article['hits'], $_LANG['HIT'], $_LANG['HIT2'], $_LANG['HIT10']); ?></span>
        </div>
        <?php if ($karma_buttons) { ?>
        <div id="karmactrl"><strong><?php echo $_LANG['RAT_ARTICLE']; ?>:</strong> <?php echo $karma_buttons; ?></div>
        <?php } ?>
    </div>
<?php } ?>