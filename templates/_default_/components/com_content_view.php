<?php if (!$is_homepage) { ?>
    <?php if ($cat['showrss']) { ?>
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td><h1 class="con_heading"><?php echo $pagetitle; ?></h1></td>
                <td valign="top" style="padding-left:6px">
                    <div class="con_rss_icon">
                        <a href="/rss/content/<?php echo $cat['id']; ?>/feed.rss" title="<?php echo $_LANG['RSS']; ?>">
                            <img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/rss.png" alt="<?php echo $_LANG['RSS']; ?>"/>
                        </a>
                    </div>
                </td>
            </tr>
        </table>
    <?php } else { ?>
        <h1 class="con_heading"><?php echo $pagetitle; ?></h1>
    <?php } ?>

    <?php if ($cat['description']) { ?>
        <div class="con_description"><?php echo $cat['description']; ?></div>
    <?php } ?>
<?php } ?>

<?php if ($subcats) { ?>
    <div class="categorylist">
        <?php foreach($subcats as $subcat) { ?>
            <div class="subcat">
                <a href="<?php echo $subcat['url']; ?>" class="con_subcat"><?php echo $subcat['title']; ?></a> (<?php echo $subcat['content_count']; ?>)
                <div class="con_description"><?php echo $subcat['description']; ?></div>
            </div>
        <?php } ?>
    </div>
<?php } ?>

<?php if ($cat_photos) { ?>
    <?php if ($cat_photos['album.title']) { ?>
        <h3><?php echo $cat_photos['album']['title']; ?></h3>
    <?php } ?>
    <?php $fcol = 1; ?>
    <table cellpadding="0" cellspacing="0" border="0">
        <?php foreach($cat_photos['photos'] as $con) { ?>
            <?php if ($fcol == 1) { ?> <tr> <?php } ?>
            <td align="center" valign="middle" width="<?php echo 100/$cat_photos['album']['maxcols']; ?>%">
                <div class="photo_thumb" align="center">
                    <a class="lightbox-enabled" rel="lightbox-galery" href="/images/photos/medium/<?php echo $con['file']; ?>" title="<?php echo $this->escape($con['title']); ?>">
                        <img class="photo_thumb_img" src="/images/photos/small/<?php echo $con['file']; ?>" alt="<?php echo $this->escape($con['title']); ?>" />
                    </a><br />
                    <a href="/photos/photo<?php echo $con['id']; ?>.html" title="<?php echo $this->escape($con['title']); ?>"><?php echo $this->truncate($con['title'], 15); ?></a>
                </div>
            </td>
        <?php if ($fcol == $cat_photos['album']['maxcols']) { $fcol = 1; echo '</tr>'; } else { $fcol++; } ?>
        <?php } ?>
        <?php if ($fcol > 1) { ?>
            <td colspan="<?php echo $cat_photos['album']['maxcols'] - $fcol + 1; ?>">&nbsp;</td></tr>
        <?php } ?>
   </table>
<?php } ?>

<?php if ($articles) { ?>
    <?php $col = 1; ?>

    <table class="contentlist" cellspacing="2" border="0" width="100%">
    <?php foreach($articles as $article) { ?>
        <?php if ($col == 1) { ?> <tr> <?php } ?>
            <td width="" valign="top">
                <div class="con_title">
                    <a href="<?php echo $article['url']; ?>" class="con_titlelink"><?php echo $article['title']; ?></a>
                </div>
                <?php if ($cat['showdesc']) { ?>
                    <div class="con_desc">
                        <?php if ($article['image_small']) { ?>
                            <div class="con_image">
                                <img src="<?php echo $article['image_small']; ?>" alt="<?php echo $this->escape($article['title']); ?>"/>
                            </div>
                        <?php } ?>
                        <?php echo $article['description']; ?>
                    </div>
                <?php } ?>

                <?php if ($cat['showcomm'] || $showdate || ($cat['showtags'] && $article['tagline'])) { ?>
                    <div class="con_details">
                        <?php if ($showdate) { ?>
                            <?php echo $article['fpubdate']; ?> - <a href="<?php echo cmsUser::getProfileURL($article['user_login']); ?>" style="color:#666"><?php echo $article['author']; ?></a>
                        <?php } ?>
                        <?php if ($cat['showcomm']) { ?>
                            <?php if ($showdate) { ?> | <?php } ?>
                            <a href="<?php echo $article['url']; ?>" title="<?php echo $_LANG['DETAIL']; ?>"><?php echo $_LANG['DETAIL']; ?></a>
                            | <a href="<?php echo $article['url']; ?>#c" title="<?php echo $_LANG['COMMENTS']; ?>"><?php echo $this->spellcount($article['comments'], $_LANG['COMMENT1'], $_LANG['COMMENT2'], $_LANG['COMMENT10']); ?></a>
                        <?php } ?>
                         | <?php echo $this->spellcount($article['hits'], $_LANG['HIT'], $_LANG['HIT2'], $_LANG['HIT10']); ?>
                        <?php if ($cat['showtags'] && $article['tagline']) { ?>
                            <?php if ($showdate || $cat['showcomm']) { ?> <br/> <?php } ?>
                            <?php if ($article['tagline']) { ?> <strong><?php echo $_LANG['TAGS']; ?>:</strong> <?php echo $article['tagline']; ?> <?php } ?>
                        <?php } ?>
                    </div>
                <?php } ?>
            </td>
        <?php if ($col == $cat['maxcols']) { $col = 1; echo '</tr>'; } else { $col++; } ?> </tr>
    <?php } ?>
    <?php if ($col > 1) { ?>
        <td colspan="<?php echo $cat['maxcols'] - $col + 1; ?>">&nbsp;</td></tr>
    <?php } ?>
    </table>
    <?php echo $pagebar; ?>
<?php } ?>