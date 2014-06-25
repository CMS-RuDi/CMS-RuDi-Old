<?php if ($faq) { ?>
<table cellspacing="4" border="0" width="100%">
    <?php foreach ($faq as $quest) { ?>
        <tr>
            <td width="20" valign="top"><img src="/images/markers/faq.png" border="0" /></td>
            <td>
                <div class="mod_faq_quest"><?php echo $this->truncate($quest['quest'], $cfg['maxlen']); ?></div>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><span class="mod_faq_date"><?php echo $quest['date']; ?></span> &mdash; <a href="<?php echo $quest['href']; ?>"><?php echo $_LANG['LATEST_FAQ_DETAIL']; ?>...</a></td>
        </tr>
    <?php } ?>
</table>
<?php } else { ?>
    <p><?php echo $_LANG['LATEST_FAQ_NOT_QUES']; ?></p>
<?php } ?>
