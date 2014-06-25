<div class="float_bar">
    <a href="/content/add.html" class="usr_article_add"><?php echo $_LANG['ADD_ARTICLE']; ?></a>
</div>

<h1 class="con_heading"><?php echo $_LANG['MY_ARTICLES']; ?> (<?php echo $total; ?>)</h1>

<?php if ($articles) { ?>
<style type="text/css">
    .art_list {
        border: 1px solid #ccc;
        border-collapse: collapse;
    }
    .art_list thead {
        background: #333;
        color: #fff;
    }
</style>
    
<table width="100%" cellpadding="8" cellspacing="0" border="0" class="art_list">
    <thead>
        <tr class="thead">
            <td width="100"><strong><?php echo $_LANG['DATE']; ?></strong></td>
            <td colspan="2"><strong><?php echo $_LANG['ARTICLE']; ?></strong></td>
            <td width="100" align="center"><strong><?php echo $_LANG['STATUS']; ?></strong></td>
            <td width="16">&nbsp;</td>
            <td width="20">&nbsp;</td>
            <td width="100"><strong><?php echo $_LANG['CAT']; ?></strong></td>
            <td width="70" align="center"><strong><?php echo $_LANG['ACTION']; ?></strong></td>
        </tr>
    </thead>
    <tbody>
	<?php foreach($articles as $article) { ?>
            <tr>
                <td><?php echo $article['fpubdate']; ?></td>
                <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/article.png" border="0"></td>
                <td><a href="<?php echo $article['url']; ?>"><?php echo $article['title']; ?></a></td>
                <td align="center">
                <?php if ($article['published']) { ?>
                <span style="color:green"><?php echo $_LANG['PUBLISHED']; ?></span>
                <?php } else { ?>
                <span style="color:#CC0000"><?php echo $_LANG['NO_PUBLISHED']; ?></span>
                <?php } ?>
                </td>
                <td><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/comments.png" border="0"></td>
                <td><?php echo $article['comments']; ?></td>
                <td><a href="<?php echo $article['cat_url']; ?>"><?php echo $article['cat_title']; ?></a></td>
                <td align="center">
                    <a href="/content/edit<?php echo $article['id']; ?>.html" title="<?php echo $_LANG['EDIT']; ?>"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/edit.png" border="0"/></a>
                    <?php if ($user_can_delete) { ?>
                        <a href="/content/delete<?php echo $article['id']; ?>.html" title="<?php echo $_LANG['DELETE']; ?>"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/delete.png" border="0"/></a>
                    <?php } ?>
                </td>
            </tr>
	<?php } ?>
    </tbody>
</table>

<?php echo $pagebar; ?>

<script type="text/javascript">
$(document).ready(function(){
    zebra();

    function zebra() {
       $('.art_list tr').not('.thead').removeClass('search_row1').removeClass('search_row2');
       $('.art_list tr:odd').not('.thead').addClass('search_row1');
       $('.art_list tr:even').not('.thead').addClass('search_row2');
    }
});
</script>

<?php } else { ?>
    <p><?php echo $_LANG['NO_YOUR_ARTICL_ON_SITE']; ?></p>
<?php } ?>