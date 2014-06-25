<?php if ($total) { ?>
    <table class="contentlist" cellspacing="2" border="0" width="">
        <?php foreach ($articles as $article) { ?>
        <tr>
            <td width="20">
                <img src="/images/markers/article.png" border="0" class="con_icon"/>
            </td>
            <td>
                <a href="<?php echo $article['url']; ?>" class="con_titlelink"><?php echo $article['title']; ?></a>
            </td>
        </tr>
        <?php } ?>
    </table>
<?php } else { ?>
    echo '<p>'. $_LANG['PU_USER_NO_ADD_ART'] .'</p>';
<?php } ?>