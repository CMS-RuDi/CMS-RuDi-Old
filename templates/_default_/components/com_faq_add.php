<div class="con_heading"><?php echo $_LANG['SET_QUESTION']; ?></div>

<div style="margin-top:10px"><?php echo $_LANG['SET_QUESTION_TEXT']; ?></div>
<div style="margin-bottom:10px"><?php echo $_LANG['CONTACTS_TEXT']; ?></div>

<?php if ($error) { ?><p style="color:red"><?php echo $error; ?></p><?php } ?>

<form action="" method="POST" name="questform">
    <table cellpadding="0" cellspacing="0" class="faq_add_cat">
        <tr>
            <td width="150">
                <strong><?php echo $_LANG['CAT_QUESTIONS']; ?>: </strong>
            </td>
            <td>
                <select name="category_id" style="width:300px">
                    <?php echo $catslist; ?>
                </select>
            </td>
        </tr>
    </table>

    <textarea name="message" id="faq_message" style=""><?php echo $message; ?></textarea>

    <?php if (!$user_id) { ?>
        <p style="margin-bottom:10px"><?php echo cmsPage::getCaptcha(); ?></p>
    <?php } ?>

    <div>
        <input type="button" style="font-size:16px;margin-right:2px;margin-top:3px;" onclick="sendQuestion()" name="gosend" value="<?php echo $_LANG['SEND']; ?>"/>
        <input type="button" style="font-size:16px;margin-top:3px;" name="cancel" onclick="window.history.go(-1)" value="<?php echo $_LANG['CANCEL']; ?>"/>
    </div>
</form>