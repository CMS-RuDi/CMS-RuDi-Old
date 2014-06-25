<div class="con_heading"><?php echo $_LANG['AWARD_USER']; ?></div>
<form action="" method="POST" name="addform" id="addform">
  <table width="100%" cellpadding="4" cellspacing="5">
    <tr>
      <td width="150" valign="middle"><?php echo $_LANG['AWARD_IMG']; ?>:</td>
      <td valign="middle"><div style="overflow:hidden;_height:1%">

	<?php foreach($awardslist as $img) { ?>
        <div style="float:left;margin:4px">
        <table border="0" cellspacing="0" cellpadding="4"><tr>
                <td align="center" valign="middle"><label><img src="/images/users/awards/<?php echo $img; ?>" /><br/><input type="radio" name="imageurl" value="<?php echo $img; ?>"/></label></td>
        </tr></table></div>
	<?php } ?>

      </div></td>
    </tr>
    <tr>
      <td width="150"><?php echo $_LANG['AWARD_NAME']; ?>:</td>
      <td><input type="text" name="title" class="text-input" style="width:300px" /></td>
    </tr>
    <tr>
      <td width="150"><?php echo $_LANG['AWARD_DESC']; ?>:</td>
      <td><textarea name="description" class="text-input" style="width:300px" rows="4"></textarea></td>
    </tr>
  </table>
  <div style="margin-top:6px;">
    <input type="submit" name="gosend" value="<?php echo $_LANG['TO_AWARD']; ?>" style="font-size:16px"/>
    <input type="button" name="gosend" value="<?php echo $_LANG['CANCEL']; ?>" style="font-size:16px" onclick="window.history.go(-1)"/>
  </div>
</form>
