<div class="con_heading"><?php echo $_LANG['QUESTION_VIEW']; ?> <?php if ($is_admin) { ?><a href="/faq/delquest<?php echo $quest['id']; ?>.html">X</a><?php } ?></div>

<table cellspacing="5" cellpadding="0" border="0" width="100%">
    <tr>
        <td width="35" valign="top"><img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/big/faq_quest.png" border="0" /></td>
        <td width="" valign="top">
            <div class="faq_questtext"><?php echo $quest['quest']; ?></div>
            <?php if ($cfg['user_link']) { ?>
<div class="faq_questuser"><?php if ($quest['nickname']) { ?><a href="<?php echo cmsUser::getProfileURL($quest['login']); ?>"><?php echo $quest['nickname']; ?></a><?php } else { ?><?php echo $_LANG['QUESTION_GUEST']; ?><?php } ?></div>
            <?php } ?>
            <div class="faq_questdate"><?php echo $quest['pubdate']; ?></div>
        </td>
    </tr>
</table>

<?php if ($quest['answer']) { ?>
<table cellspacing="5" cellpadding="0" border="0" width="100%" style="margin:15px 0px;">
    <tr>
        <td width="35" valign="top">
            <img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/big/faq_answer.png" border="0" />
        </td>
        <td width="" valign="top">
            <div class="faq_answertext"><?php echo $quest['answer']; ?></div>
            <div class="faq_questdate"><?php echo $quest['answerdate']; ?></div>
        </td>
    </tr>
</table>
<?php } ?>

<?php
if ($cfg['is_comment']) {
    cmsCore::includeComments();
    comments('faq', $quest['id'], $labels);
}
?>