<?php if ($is_user || $cfg['guest_enabled']) { ?>
<div class="faq_send_quest">
    <a href="/faq/sendquest<?php if ($id > 0) { ?><?php echo $id; ?><?php } ?>.html"><?php echo $_LANG['SET_QUESTION']; ?></a>
</div>
<?php } ?>

<div class="con_heading"><?php echo $pagetitle; ?></div>

<?php if ($is_subcats) { ?>
    <?php if ($id > 0) { ?>
    <div class="faq_subcats">
    <?php } else { ?>
    <div class="faq_cats">
    <?php } ?>
        <table width="100%">
            <?php foreach($subcats as $subcat) { ?>
                <tr>
                    <td width="40" valign="top"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/big/folder.png" border="0" /></td>
                    <td valign="top">
                        <div class="faq_cat_link"><a href="/faq/<?php echo $subcat['id']; ?>"><?php echo $subcat['title']; ?></a></div>
                        <?php if ($subcat['description']) { ?>
                            <div class="faq_cat_desc"><?php echo $subcat['description']; ?></div>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
<?php } ?>

<?php if ($is_quests) { ?>
    <?php if ($id == 0) { ?>
        <h1 class="con_heading"><?php echo $_LANG['LAST_QUESTIONS']; ?></h1>
    <?php } ?>
    <?php foreach($quests as $quest) { ?>
        <div class="faq_quest">
            <table cellspacing="5" cellpadding="0" border="0" width="100%">
                <tr>
                    <td width="30" valign="top"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/big/faq_quest.png" border="0" /></td>
                    <td width="" valign="middle">
                        <div class="faq_quest_link"><a href="/faq/quest<?php echo $quest['id']; ?>.html"><?php echo $quest['quest']; ?></a></div>
                        <?php if ($id == 0) { ?>
                        <div class="faq_questcat">&rarr;  <a href="/faq/<?php echo $quest['cid']; ?>"><?php echo $quest['cat_title']; ?></a></div>
                        <?php } ?>
                                                <div class="faq_questdate"><?php echo $quest['pubdate']; ?></div>
                        <?php if ($cfg['user_link']) { ?>
                        <div class="faq_questuser"><?php if ($quest['nickname']) { ?><a href="<?php echo cmsUser::getProfileURL($quest['login']); ?>"><?php echo $quest['nickname']; ?></a><?php } else { ?><?php echo $_LANG['QUESTION_GUEST']; ?><?php } ?></div>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php } ?>
    <?php if ($id > 0) { ?> <?php echo $pagebar; ?> <?php } ?>
<?php } ?>