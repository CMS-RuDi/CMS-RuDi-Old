<form id="addform" name="addform" method="post" action="index.php?view=components&do=config&link=polls">
    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_QUESTION']; ?>:</label>
            <input type="text" class="form-control" name="title" size="30" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'title', '')); ?>" />
        </div>
        
        <?php for ($v=1; $v<=12; $v++) { ?>
            <div class="form-group">
                <label><?php echo $_LANG['AD_ANSWER']; ?> №<?php echo $v ?> <?php if (isset($answers_num[$v])) { echo '('. $_LANG['AD_VOTES'] .': '. $answers_num[$v] .')'; } ?>:</label>
                <input type="text" class="form-control" name="answers[<?php echo $v; ?>]" size="30" value="<?php echo cmsCore::getArrVal($answers_title, $v, ''); ?>" />
                <?php if (isset($answers_num[$v])) { echo '<input type="hidden" name="num['. $v .']" value="'. $answers_num[$v] .'" />';  } ?>
            </div>
        <?php } ?>
    </div>
    
    <div>
      <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
      
      <input type="hidden" name="opt" <?php if ($opt=='add') { echo 'value="submit"'; } else { echo 'value="update"'; } ?> />
      
      <?php
        if ($opt == 'edit') {
            echo '<input type="hidden" name="poll_id" value="'. $mod['id'] .'" /> ';
            echo ' <label><input type="checkbox" name="is_clear" value="1" /> '. $_LANG['AD_CLEAN_LOG'] .'</label>';
        }
        ?>
    </div>
</form>