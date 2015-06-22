<table border="0" class="table" style="margin:0;">
    <tr>
        <td style="border:0;">
            <table border="0" class="table" style="margin:0;">
                <tr>
                    <td width="120">
                        <label><?php echo $_LANG['AD_INSERT']; ?>:</label>
                    </td>
                    <td width="">
                        <select id="ins" style="width:99%" class="form-control" name="ins" onChange="showIns()">
                            <option value="frm" selected="selected"><?php echo $_LANG['AD_FORM']; ?></option>
                            <option value="include"><?php echo $_LANG['FILE']; ?></option>
                            <option value="filelink"><?php echo $_LANG['AD_LINK_DOWNLOAD_FILE']; ?></option>';
                            <?php if ($bannersInstalled) { ?>
                                <option value="banpos"><?php echo $_LANG['AD_BANNER_POSITION']; ?></option>
                            <?php } ?>
                            <option value="pagebreak">-- <?php echo $_LANG['AD_PAGEBREAK']; ?> --</option>
                            <option value="pagetitle">-- <?php echo $_LANG['AD_PAGETITLE']; ?> --</option>
                        </select>
                    </td>
                    <td width="100">&nbsp;</td>
                </tr>
                <tr id="frm">
                    <td width="120">
                        <label><?php echo $_LANG['AD_FORM']; ?>:</label>
                    </td>
                    <td>
                        <select class="form-control" style="width:99%" name="fm">
                            <?php echo $forms_options; ?>
                        </select>
                    </td>
                    <td width="100">
                        <input type="button" class="btn btn-default" style="width:100px" value="<?php echo $_LANG['AD_INSERT']; ?>" onClick="insertTag(document.addform.ins.options[document.addform.ins.selectedIndex].value)" />
                    </td>
                </tr>
                <tr id="include">
                    <td width="120">
                        <label><?php echo $_LANG['FILE']; ?>:</label>
                    </td>
                    <td style="vertical-align: middle;">
                        /includes/myphp/<input type="text" class="form-control" style="width:300px;display:inline-block;" name="i" value="myscript.php" />
                    </td>
                    <td width="100">
                        <input type="button" class="btn btn-default" style="width:100px" value="<?php echo $_LANG['AD_INSERT']; ?>" onClick="insertTag(document.addform.ins.options[document.addform.ins.selectedIndex].value)" />
                    </td>
                </tr>
                <tr id="filelink">
                    <td width="120">
                        <label><?php echo $_LANG['FILE']; ?>:</label>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="fl" value="/files/myfile.rar" />
                    </td>
                    <td width="100">
                        <input type="button" class="btn btn-default" style="width:100px" value="<?php echo $_LANG['AD_INSERT']; ?>" onClick="insertTag(document.addform.ins.options[document.addform.ins.selectedIndex].value)" />
                    </td>
                </tr>';
                <?php if ($bannersInstalled) { ?>
                <tr id="banpos">
                    <td width="120">
                        <label><?php echo $_LANG['AD_POSITION']; ?>:</label>
                    </td>
                    <td>
                        <select class="form-control" style="width:99%" name="ban">
                            <?php echo $banners_options; ?>
                        </select>
                    </td>
                    <td width="100">
                        <input type="button" class="btn btn-default" style="width:100px" value="<?php echo $_LANG['AD_INSERT']; ?>" onClick="insertTag(document.addform.ins.options[document.addform.ins.selectedIndex].value)" />
                    </td>
                </tr>
                <?php } ?>
                <tr id="pagebreak">
                    <td width="120">
                        <label><?php echo $_LANG['TAG']; ?>:</label>
                    </td>
                    <td>
                        {pagebreak}
                    </td>
                    <td width="100">
                        <input type="button" class="btn btn-default" style="width:100px" value="<?php echo $_LANG['AD_INSERT']; ?>" onClick="insertTag(document.addform.ins.options[document.addform.ins.selectedIndex].value)" />
                    </td>
                </tr>
                <tr id="pagetitle">
                    <td width="120">
                        <label><?php echo $_LANG['AD_TITLE']; ?>:</label>
                    </td>
                    <td>
                        <input type="text" class="form-control" style="width:99%" name="ptitle" />
                    </td>
                    <td width="100">
                        <input type="button" class="btn btn-default" style="width:100px" value="<?php echo $_LANG['AD_INSERT']; ?>" onClick="insertTag(document.addform.ins.options[document.addform.ins.selectedIndex].value)" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<script type="text/javascript">showIns();</script>