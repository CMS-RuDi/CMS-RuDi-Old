<?php if ($page_title) { ?><h1 class="con_heading"><?php echo $page_title; ?></h1><?php } ?>
<?php if ($order_form) { ?><?php echo $order_form; ?><?php } ?>
<div class="board_gallery">
    <?php if ($items) { ?>
        <table width="100%" cellpadding="3" cellspacing="0" border="0">
            <?php $col = 1; $is_moder = 0; ?>
            <?php foreach($items as $con) { ?>
                <?php if ($col == 1) { ?> <tr> <?php } ?>
                    <td valign="top" width="<?php echo $colwidth; ?>%">
                        <div class="bd_item<?php if ($con['is_vip']) { ?>_vip<?php } ?>">
                            <table width="100%" height="" cellspacing="" cellpadding="0" class="b_table_tr">
                                <tr>
                                    <?php if ($cfg['photos']) { ?>
                                    <td width="30" valign="top">
                                        <img class="bd_image_small" src="/images/board/small/<?php echo $con['file']; ?>" border="0" alt="<?php echo $this->escape($con['title']); ?>"/>
                                    </td>
                                    <?php } ?>
                                    <td valign="top">
                                        <?php if ($con['moderator']) { ?>
                                            <?php $is_moder = 1; ?>
                                            <div class="bd_moderate_link">
                                                <a href="/board/edit<?php echo $con['id']; ?>.html"><?php echo $_LANG['EDIT']; ?></a> | 
                                                <a href="/board/delete<?php echo $con['id']; ?>.html"><?php echo $_LANG['DELETE']; ?></a>
                                            </div>
                                        <?php } ?>
                                        <div class="bd_title">
                                            <a href="/board/read<?php echo $con['id']; ?>.html" title="<?php echo $this->escape($con['title']); ?>"><?php echo $con['title']; ?></a>
                                        </div>
                                        <div class="bd_text">
                                            <?php echo $this->truncate($this->strip_tags($con['content']), 250); ?>
                                        </div>
                                        <div class="bd_item_details">
                                            <?php if ($cat['showdate'] && $con['published']) { ?>
                                                <span class="bd_item_date"><?php echo $con['fpubdate']; ?></span>
                                            <?php } ?>
                                            <?php if (!$con['published'] && $con['is_overdue']) { ?>
                                                <span class="bd_item_status_bad"><?php echo $_LANG['ADV_EXTEND_INFO']; ?></span>
                                            <?php } else if (!$con['published']) { ?>
                                                <span class="bd_item_status_bad"><?php echo $_LANG['WAIT_MODER']; ?></span>
                                            <?php } ?>
                                            <span class="bd_item_hits"><?php echo $con['hits']; ?></span>
                                            <?php if ($con['city']) { ?>
                                                <span class="bd_item_city"><a href="/board/city/<?php echo $this->escape($con['enc_city']); ?>"><?php echo $con['city']; ?></a></span>
                                            <?php } ?>
                                            <?php if ($con['nickname']) { ?>
                                                <span class="bd_item_user"><a href="<?php echo cmsUser::getProfileURL($con['login']); ?>"><?php echo $con['nickname']; ?></a></span>
                                            <?php } else { ?>
                                                <span class="bd_item_user"><?php echo $_LANG['BOARD_GUEST']; ?></span>
                                            <?php } ?>
                                            <?php if ($con['cat_title']) { ?>
                                                <span class="bd_item_cat"><a href="/board/<?php echo $con['category_id']; ?>"><?php echo $con['cat_title']; ?></a></span>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                <?php if ($col == $maxcols) { $col = 1; echo '</tr>'; } else { $col++; } ?>
            <?php } ?>
            <?php if ($col > 1) { ?>
                <td colspan="<?php echo (($maxcols + 1) - $col); ?>">&nbsp;</td></tr>
            <?php } ?>
        </table>
    <?php echo $pagebar; ?>
    <?php } else if ($cat['id'] != $root_id) { ?>
        <p><?php echo $_LANG['ADVS_NOT_FOUND']; ?></p>
    <?php } ?>
</div>
<?php if ($is_moder) { ?>

<script type="text/javascript" language="JavaScript">
    $(document).ready(function(){
        $('.b_table_tr .bd_moderate_link').css({opacity:0.3, filter:'alpha(opacity=30)'});
        $('.b_table_tr').hover(
            function() {
                $(this).find('.bd_moderate_link').css({opacity:1.0, filter:'alpha(opacity=100)'});
            },
            function() {
                $(this).find('.bd_moderate_link').css({opacity:0.3, filter:'alpha(opacity=30)'});
            }
        );
    });
</script>

<?php } ?>