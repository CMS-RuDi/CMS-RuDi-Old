<?php if (!$is_ajax) { ?><div id="poll_module_<?php echo $module_id; ?>"><?php } ?>

<?php if (!$is_voted) { ?>
    <p class="mod_poll_title"><strong><?php echo $poll['title']; ?></strong></p>
    <form action="/polls/vote" method="post" id="mod_poll_submit_form">
    <input type="hidden" name="poll_id" value="<?php echo $poll['id']; ?>" />
    <input type="hidden" name="module_id" value="<?php echo $module_id; ?>" />
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <table class="mod_poll_answers">
    <?php foreach($poll['answers'] as $num) { ?>
        <tr>
          <td class="mod_poll_answer">
              <label>
                  <input name="answer" type="radio" value="<?php echo $this->escape($answer); ?>" /> <?php echo $answer; ?><?php if ($cfg['shownum']) { ?> (<?php echo $num; ?>)<?php } ?>
              </label>
          </td>
        </tr>
     <?php } ?>
     </table>
     <div align="center"><input type="button" id="mod_poll_submit" class="mod_poll_submit" onclick="pollSubmit();" value="<?php echo $_LANG['POLLS_VOTE']; ?> <?php if ($cfg['shownum']) { ?>(<?php echo $poll['total_answers']; ?>)<?php } ?>"></div>
    </form>

<?php } else { ?>

    <p class="mod_poll_title"><strong><?php echo $poll['title']; ?></strong></p>

    <?php foreach($poll['answers'] as $num) { ?>
        <?php $percent = $num/$poll['total_answers']*100; ?>
        <span class="mod_poll_gauge_title"><?php echo $answer; ?> (<?php echo $num; ?>)</span>
        <?php if ($percent > 0) { ?>
            <table class="mod_poll_gauge" width="<?php echo ceil($percent); ?>%"><tr><td></td></tr></table>
        <?php } else { ?>
            <table class="mod_poll_gauge" width="5"><tr><td></td></tr></table>
        <?php } ?>

    <?php } ?>

<?php } ?>

<?php if (!$is_ajax) { ?>
</div>
<script type="text/javascript" src="/includes/jquery/jquery.form.js"></script>

<script type="text/javascript">
function pollSubmit(){
    $('#mod_poll_submit').prop('disabled', true);
    var options = {
        success: loadPoll
    };
    $('#mod_poll_submit_form').ajaxSubmit(options);
}
function loadPoll(result, statusText, xhr, $form){
    var module_id = <?php echo $module_id; ?>;
	if(statusText == 'success'){
		if(result.error == false){
            core.alert(result.text, '<?php echo $_LANG['NOTICE']; ?>!');
            $.post('/modules/mod_polls/load.php', {module_id: module_id}, function(data){
                $('#poll_module_'+module_id).html(data);
            });
            setTimeout('core.box_close()', 900);
		} else {
            core.alert(result.text, '<?php echo $_LANG['ATTENTION']; ?>!');
            $('#mod_poll_submit').prop('disabled', false);
        }
	}

}
</script>

<?php } ?>