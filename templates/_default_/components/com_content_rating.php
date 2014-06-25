<h1 class="con_heading"><?php echo $_LANG['ARTICLES_RATING']; ?></h1>
<?php if ($articles) { ?>

<table class="contentlist" cellspacing="2" border="0" width="">
    <?php foreach($articles as $article) { ?>
        <tr>
            <td width="20" valign="top" style="font-size:20px"><?php echo $this->rating($article['rating']); ?></td>
            <td width="" valign="top">
                <h2 class="con_title">
                    <a href="<?php echo $article['url']; ?>" class="con_titlelink"><?php echo $article['title']; ?></a>
                </h2>
                <?php if ($article['showdesc']) { ?>
                    <div class="con_desc">
                        <?php if ($article['image_small']) { ?>
                            <div class="con_image">
                                <img src="<?php echo $article['image_small']; ?>" border="0" alt="<?php echo $this->escape($article['title']); ?>"/>
                            </div>
                        <?php } ?>
                        <?php echo $article['description']; ?>
                    </div>
                <?php } ?>

                <?php if ($article['showcomm'] || $article['showdate'] || $article['tagline']) { ?>
                    <div class="con_details">
                        <?php if ($article['showdate']) { ?>
                            <?php echo $article['fpubdate']; ?> - <a href="<?php echo cmsUser::getProfileURL($article['user_login']); ?>" style="color:#666"><?php echo $article['author']; ?></a>
                        <?php } ?>
                        <?php if ($article['showcomm']) { ?>
                            <?php if ($article['showdate']) { ?> | <?php } ?>
                            <a href="<?php echo $article['url']; ?>#c" title="<?php echo $_LANG['COMMENTS']; ?>"><?php echo $this->spellcount($article['comments'], $_LANG['COMMENT'], $_LANG['COMMENT2'], $_LANG['COMMENT10']); ?></a>
                        <?php } ?>
                         | <?php echo $this->spellcount($article['hits'], $_LANG['HIT'], $_LANG['HIT2'], $_LANG['HIT10']); ?>
                        <?php if ($article['tagline']) { ?>
                             | <strong><?php echo $_LANG['TAGS']; ?>:</strong> <?php echo $article['tagline']; ?>
                        <?php } ?>
                        	 | <strong><?php echo $_LANG['CAT']; ?>:</strong> <a href="<?php echo $article['cat_url']; ?>"><?php echo $article['cat_title']; ?></a>
                    </div>
                <?php } ?>
            </td>
         </tr>
    <?php } ?>
</table>

<?php } else { ?>
	<p><?php echo $_LANG['NO_ARTICLES_PUBL_ON_SITE']; ?></p>
<?php } ?>