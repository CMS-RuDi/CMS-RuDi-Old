<div class="con_heading"><?php echo $_LANG['KARMA_HISTORY']; ?> - <?php echo $usr['nickname']; ?></div>
<?php if ($karma) { ?>
<table width="">
    <?php foreach($karma as $karm) { ?>
        <tr>
            <td style="border-bottom:solid 1px silver" width="150" valign="middle"><?php echo $karm['fsenddate']; ?></td>
            <td style="border-bottom:solid 1px silver" width="200" valign="middle"><a href="<?php echo cmsUser::getProfileURL($karm['login']); ?>"><?php echo $karm['nickname']; ?></a></td>
            <td style="border-bottom:solid 1px silver" width="100" valign="middle" align="center">
            <?php if ($karm['kpoints'] > 0) { ?>
                <span style="font-size:24px;color:green">+<?php echo $karm['kpoints']; ?></span>
            <?php } else { ?>
                <span style="font-size:24px;color:red"><?php echo $karm['kpoints']; ?></span>
            <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>
<?php } else { ?>
<p><?php echo $_LANG['KARMA_NOT_MODIFY']; ?></p>
<p><?php echo $_LANG['KARMA_NOT_MODIFY_TEXT']; ?></p>
<p><?php echo $_LANG['KARMA_DESCRIPTION']; ?></p>
<?php } ?>