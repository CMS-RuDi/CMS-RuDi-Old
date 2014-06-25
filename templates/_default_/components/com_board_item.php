<h1 class="con_heading"><?php echo $item['title']; ?></h1>
<div class="bd_item_details_full">
    <?php if ($item['is_vip']) { ?>
        <span class="bd_item_is_vip"><?php echo $_LANG['VIP_ITEM']; ?></span>
    <?php } ?>
    <span class="bd_item_date"><?php echo $item['pubdate']; ?></span>
    <span class="bd_item_hits"><?php echo $item['hits']; ?></span>
    <?php if ($item['city']) { ?>
        <span class="bd_item_city">
            <a href="/board/city/<?php echo $item['enc_city']; ?>"><?php echo $item['city']; ?></a>
        </span>
    <?php } ?>
    <?php if ($item['user']) { ?>
        <span class="bd_item_user">
            <a href="<?php echo cmsUser::getProfileURL($item['user_login']); ?>"><?php echo $item['user']; ?></a>
        </span>
    <?php } else { ?>
        <span class="bd_item_user"><?php echo $_LANG['BOARD_GUEST']; ?></span>
    <?php } ?>
    <?php if ($item['moderator']) { ?>
        <span class="bd_item_edit"><a href="/board/edit<?php echo $item['id']; ?>.html"><?php echo $_LANG['EDIT']; ?></a></span>
        <?php if (!$item['published'] && ($is_admin || $is_moder)) { ?>
            <span class="bd_item_publish"><a href="/board/publish<?php echo $item['id']; ?>.html"><?php echo $_LANG['PUBLISH']; ?></a></span>
        <?php } ?>
        <span class="bd_item_delete"><a href="/board/delete<?php echo $item['id']; ?>.html"><?php echo $_LANG['DELETE']; ?></a></span>
    <?php } ?>
</div>

<table width="100%" height="" cellspacing="" cellpadding="0" class="bd_item_full">
    <tr>
        <?php if ($item['file'] && $cfg['photos']) { ?>
        <td width="64">
            <img class="bd_image_small" src="/images/board/medium/<?php echo $item['file']; ?>" border="0" alt="<?php echo $this->escape($item['title']); ?>"/>
        </td>
        <?php } ?>
        <td valign="top">
            <div class="bd_text_full">
            	<p><?php echo $item['content']; ?></p>
                <?php if ($formsdata) { ?>
                    <table width="100%" cellspacing="0" cellpadding="2" style="border-top:1px solid #C3D6DF; margin:5px 0 0 0">
                        <?php foreach($formsdata as $form) { ?>
                        <?php if ($form['field']) { ?>
                            <tr>
                                <td valign="top" width="140px">
                                    <strong><?php echo $form['title']; ?>:</strong>
                                </td>
                                <td valign="top">
                                    <?php echo $form['field']; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php } ?>
                     </table>
                <?php } ?>
            </div>
        </td>
    </tr>
</table>

<div class="bd_links">
    <?php if ($user_id) { ?>
        <?php if ($item['user'] && !$item['user_is_deleted'] && $item['user_id'] != $user_id) { ?>
            <?php cmsCore::c('page')->addHeadJS('components/users/js/profile.js'); ?>
            <span class="bd_message"><a class="ajaxlink" title="<?php echo $_LANG['WRITE_MESS_TO_AVTOR']; ?>" href="javascript:void(0)" onclick="users.sendMess('<?php echo $item['user_id']; ?>', 0, this);return false;"><?php echo $_LANG['WRITE_MESS_TO_AVTOR']; ?></a></span>
        <?php } ?>
    <?php } ?>
    <?php if ($item['user_login']) { ?>
	<span class="bd_author"><a href="/board/by_user_<?php echo $item['user_login']; ?>"><?php echo $_LANG['ALL_AVTOR_ADVS']; ?></a></span>
    <?php } ?>
</div>

<?php
if ($cfg['comments']) {
    cmsCore::includeComments();
    comments('boarditem', $item['id']);
}
?>