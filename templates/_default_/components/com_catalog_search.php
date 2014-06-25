<h1 class="con_heading"><?php echo $_LANG['SEARCH_IN_CAT']; ?></h1>
<div class="uc_search_in_cat">
    <a href="/catalog/<?php echo $cat['id']; ?>"><?php echo $cat['title']; ?></a>
</div>

<p><strong><?php echo $_LANG['FILL_FIELDS']; ?>:</strong></p>

<form action="/catalog/<?php echo $id; ?>/search.html" name="searchform" method="post" >
    <div class="uc_cat_search">
        <table width="100%" border="0" cellspacing="5">
            <tr>
                <td width="160" valign="top"><?php echo $_LANG['TITLE']; ?>: </td>
                <td valign="top"><input name="title" type="text" id="title" size="35" value="" /></td>
            </tr>
        </table>
        <?php foreach($fstruct as $value) { ?>
            <table width="100%" border="0" cellspacing="5">
                <tr>
                    <td width="160" valign="top"><?php echo $value; ?>: </td>
                    <td valign="top"><input name="fdata[<?php echo $tid; ?>]" type="text" id="fdata[]" size="35" value="" /> </td>
                </tr>
            </table>
        <?php } ?>
        <table width="100%" border="0" cellspacing="5">
            <tr>
                <td width="160" valign="top"><?php echo $_LANG['TAGS']; ?>: </td>
                <td valign="top"><input name="tags" type="text" id="tags" size="35" value="" /><br/><?php echo tagsList($id);?></td>
            </tr>
        </table>
    </div>
	<p>
            <input type="submit" name="gosearch" value="<?php echo $_LANG['SEARCH_IN_CAT']; ?>" />
            <input type="button" onclick="window.history.go(-1);" name="cancel" value="<?php echo $_LANG['CANCEL']; ?>" />
	</p>
</form>