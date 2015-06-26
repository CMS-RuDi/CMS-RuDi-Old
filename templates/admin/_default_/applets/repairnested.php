<h3><?php echo $_LANG['AD_TREE_FULL']; ?></h3>

<div style="margin:20px; margin-top:0px;">
    <form method="post" action="" id="repairform">
        <input id="go_repair" type="hidden" name="go_repair" value="0">
        <input id="go_repair_tree" type="hidden" name="go_repair_tree" value="0">
        <table cellpadding="2">
        <?php foreach ($tables as $id => $table) { ?>
            <tr>
                <td width="15">
                    <?php if ($table['error']) { $errors_found = true; ?>
                        <input type="checkbox" name="tables[]" value="<?php echo $id; ?>" checked="checked" />
                    <?php } ?>
                </td>
                <td><div>
                    <span><?php echo $table['title']; ?></span> &mdash;
                    <?php if ($table['error']) { ?>
                    <span style="color:red"><?php echo $_LANG['AD_ERROR_FOUND']; ?></span>
                    <?php } else { ?>
                    <span style="color:green"><?php echo $_LANG['AD_NO_ERROR_FOUND']; ?></span>
                    <?php } ?>
                </div></td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php if ($errors_found) { ?>
    <div style="margin-bottom:20px">
        <input type="button" class="btn btn-primary" onclick="repairTreesRoot()" value="<?php echo $_LANG['AD_REPAIR']; ?>" />
        <input type="button" class="btn btn-primary" onclick="repairTrees()" value="<?php echo $_LANG['AD_REPAIR_TOTREE']; ?>" />
    </div>
<?php } ?>