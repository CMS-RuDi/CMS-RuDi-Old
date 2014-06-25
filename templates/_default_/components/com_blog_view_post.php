<?php if ($myblog || $is_admin || ($is_writer && $is_author)) { ?>
    <div class="float_bar">
        <?php if (!$post['published'] && ($is_admin)) { ?><span id="pub_link"><a class="ajaxlink" href="javascript:void(0)" onclick="<?php echo cmsCore::getInstance()->component; ?>.publishPost(<?php echo $post['id']; ?>);return false;"><?php echo $_LANG['PUBLISH']; ?></a> | </span><?php } ?><a href="/<?php echo cmsCore::getInstance()->component; ?>/editpost<?php echo $post['id']; ?>.html"><?php echo $_LANG['EDIT']; ?></a> | <a class="ajaxlink" href="javascript:void(0)" onclick="<?php echo cmsCore::getInstance()->component; ?>.deletePost(<?php echo $post['id']; ?>, '<?php echo cmsUser::getCsrfToken(); ?>');return false;"><?php echo $_LANG['DELETE']; ?></a>
    </div>
<?php } ?>
<h1 class="con_heading"><?php echo $post['title']; ?></h1>

<table width="100%" cellpadding="4" cellspacing="0">
    <tr>
        <td width="70" valign="top" align="center">
            <div><strong><?php echo $_LANG['AVTOR']; ?></strong></div>
            <div class="blog_post_avatar"><a href="<?php echo cmsUser::getProfileURL($post['author_login']); ?>"><img border="0" class="usr_img_small" src="<?php echo $post['author_avatar']; ?>" /></a></div>
            <div><strong><a href="<?php echo cmsUser::getProfileURL($post['author_login']); ?>"><?php echo $post['author_nickname']; ?></a></strong></div>
        </td>
        <td>
            <div class="blog_post_data" valign="top">
                <div><strong><?php echo $_LANG['PUBLISHED']; ?>:</strong> <?php if (!$post['published']) { ?><span id="pub_wait" style="color:#F00;"><?php echo $_LANG['ON_MODERATE']; ?></span><span id="pub_date" style="display:none;"><?php echo $post['fpubdate']; ?></span><?php } else { ?><?php echo $post['fpubdate']; ?><?php } ?></div>
                <div><strong><?php echo $_LANG['BLOG']; ?>:</strong> <a href="/<?php echo cmsCore::getInstance()->component; ?>/<?php echo $blog['seolink']; ?>"><?php echo $blog['title']; ?></a></div>
		<?php if ($blog['showcats'] && $cat) { ?>
                    <div><strong><?php echo $_LANG['CAT']; ?>:</strong> <a href="/<?php echo cmsCore::getInstance()->component; ?>/<?php echo $blog['seolink']; ?>/cat-<?php echo $cat['id']; ?>"><?php echo $cat['title']; ?></a></div>
                <?php } ?>
                <?php if ($post['edit_times']) { ?>
                    <div><strong><?php echo $_LANG['EDITED']; ?>:</strong> <?php echo $this->spellcount($post['edit_times'], $_LANG['TIME1'], $_LANG['TIME2'], $_LANG['TIME10']); ?> &mdash; <?php if ($post['edit_times'] > 1) { ?><?php echo $_LANG['LATS_TIME']; ?><?php } ?> <?php echo $post['feditdate']; ?></div>
                <?php } ?>
                <?php if ($post['feel']) { ?>
                    <div><strong><?php echo $_LANG['MOOD']; ?>:</strong> <?php echo $post['feel']; ?></div>
                <?php } ?>
                <?php if ($post['music']) { ?>
                    <div><strong><?php echo $_LANG['PLAYING']; ?>:</strong> <?php echo $post['music']; ?></div>
                <?php } ?>
            </div>
        </td>
        <td width="100" valign="top">
            <?php echo $karma_form; ?>
        </td>
    </tr>
</table>

<div class="blog_post_body"><?php echo $post['content_html']; ?></div>
<?php echo $post['tags']; ?>
<?php if ($navigation && ($navigation['prev'] || $navigation['next'])) { ?>
    <div class="blog_post_nav">
    <?php if ($navigation['prev']) { ?><a href="<?php echo $navigation['prev']['url']; ?>" class="prev"><?php echo $navigation['prev']['title']; ?></a><?php } ?>
    <?php if ($navigation['next']) { ?><a href="<?php echo $navigation['next']['url']; ?>" class="next"><?php echo $navigation['next']['title']; ?></a><?php } ?>
    </div>
<?php } ?>