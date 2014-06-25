<?php if ($articles) { ?>
    <table cellspacing="2" cellpadding="4" border="0" width="100%">
    <?php foreach ($articles as $article) { ?>
	<tr>
            <td class="mod_blog_karma" valign="top" width="30"><?php echo $this->rating($article['rating']); ?></td>
            <td valign="top">
                <div>
                    <a class="mod_bcon_content" style="font-size:16px" href="<?php echo $article['url']; ?>"><?php echo $this->truncate($article['title'], 60); ?></a> &mdash;
                    <span class="mod_bcon_date"><?php echo $article['fpubdate']; ?></span> (<a class="mod_bcon_author" href="<?php echo cmsUser::getProfileURL($article['user_login']) ?>"><?php echo $article['author']; ?></a>)
                </div>
                <?php if ($cfg['showdesc'] != 0) { ?>
                    <?php if ($article['image_small']) { ?>
                        <div class="mod_latest_image">
                            <img src="<?php echo $article['image_small']; ?>" border="0" width="32" height="32" alt="<?php echo $this->escape($article['title']); ?>"/>
                        </div>
                    <?php } ?>
                    <div><?php echo $article['description']; ?></div>
		<?php } ?>
            </td>
	</tr>
    <?php } ?>
    <?php if ($cfg['showlink'] != 0) { ?>
	<tr><td colspan="2">
            <div style="text-align:right">
                <a href="/content/top.html"><?php echo $_LANG['BESTCONTENT_FULL_RATING']; ?></a> &rarr;
            </div>
	</td></tr>
    <?php } ?>
    </table>
<?php } else { ?>
    <p><?php echo $_LANG['BESTCONTENT_NOT_ARTICLES']; ?></p>
<?php } ?>