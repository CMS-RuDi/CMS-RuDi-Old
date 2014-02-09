<?php /* Smarty version 2.6.28, created on 2013-11-24 10:18:57
         compiled from mod_polls.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'csrf_token', 'mod_polls.tpl', 9, false),array('modifier', 'escape', 'mod_polls.tpl', 15, false),array('modifier', 'ceil', 'mod_polls.tpl', 34, false),)), $this); ?>
<?php if (! $this->_tpl_vars['is_ajax']): ?><div id="poll_module_<?php echo $this->_tpl_vars['module_id']; ?>
"><?php endif; ?>

<?php if (! $this->_tpl_vars['is_voted']): ?>

    <p class="mod_poll_title"><strong><?php echo $this->_tpl_vars['poll']['title']; ?>
</strong></p>
    <form action="/polls/vote" method="post" id="mod_poll_submit_form">
    <input type="hidden" name="poll_id" value="<?php echo $this->_tpl_vars['poll']['id']; ?>
" />
    <input type="hidden" name="module_id" value="<?php echo $this->_tpl_vars['module_id']; ?>
" />
    <input type="hidden" name="csrf_token" value="<?php echo smarty_function_csrf_token(array(), $this);?>
" />
    <table class="mod_poll_answers">
    <?php $_from = $this->_tpl_vars['poll']['answers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['answer'] => $this->_tpl_vars['num']):
?>
        <tr>
          <td class="mod_poll_answer">
              <label>
                  <input name="answer" type="radio" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['answer'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /> <?php echo $this->_tpl_vars['answer']; ?>
<?php if ($this->_tpl_vars['cfg']['shownum']): ?> (<?php echo $this->_tpl_vars['num']; ?>
)<?php endif; ?>
              </label>
          </td>
        </tr>
     <?php endforeach; endif; unset($_from); ?>
     </table>
     <div align="center"><input type="button" id="mod_poll_submit" class="mod_poll_submit" onclick="pollSubmit();" value="<?php echo $this->_tpl_vars['LANG']['POLLS_VOTE']; ?>
 <?php if ($this->_tpl_vars['cfg']['shownum']): ?>(<?php echo $this->_tpl_vars['poll']['total_answers']; ?>
)<?php endif; ?>"></div>
    </form>

<?php else: ?>

    <p class="mod_poll_title"><strong><?php echo $this->_tpl_vars['poll']['title']; ?>
</strong></p>

    <?php $_from = $this->_tpl_vars['poll']['answers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['answer'] => $this->_tpl_vars['num']):
?>

        <?php $this->assign('percent', ($this->_tpl_vars['num']/$this->_tpl_vars['poll']['total_answers']*100)); ?>

        <span class="mod_poll_gauge_title"><?php echo $this->_tpl_vars['answer']; ?>
 (<?php echo $this->_tpl_vars['num']; ?>
)</span>
        <?php if ($this->_tpl_vars['percent'] > 0): ?>
            <table class="mod_poll_gauge" width="<?php echo ((is_array($_tmp=$this->_tpl_vars['percent'])) ? $this->_run_mod_handler('ceil', true, $_tmp) : ceil($_tmp)); ?>
%"><tr><td></td></tr></table>
        <?php else: ?>
            <table class="mod_poll_gauge" width="5"><tr><td></td></tr></table>
        <?php endif; ?>

    <?php endforeach; endif; unset($_from); ?>

<?php endif; ?>

<?php if (! $this->_tpl_vars['is_ajax']): ?>
</div>
<script type="text/javascript" src="/includes/jquery/jquery.form.js"></script>
<?php echo '
<script type="text/javascript">
function pollSubmit(){
    $(\'#mod_poll_submit\').prop(\'disabled\', true);
    var options = {
        success: loadPoll
    };
    $(\'#mod_poll_submit_form\').ajaxSubmit(options);
}
function loadPoll(result, statusText, xhr, $form){
    var module_id = '; ?>
<?php echo $this->_tpl_vars['module_id']; ?>
<?php echo ';
	if(statusText == \'success\'){
		if(result.error == false){
            core.alert(result.text, \''; ?>
<?php echo $this->_tpl_vars['LANG']['NOTICE']; ?>
<?php echo '!\');
            $.post(\'/modules/mod_polls/load.php\', {module_id: module_id}, function(data){
                $(\'#poll_module_\'+module_id).html(data);
            });
            setTimeout(\'core.box_close()\', 900);
		} else {
            core.alert(result.text, \''; ?>
<?php echo $this->_tpl_vars['LANG']['ATTENTION']; ?>
<?php echo '!\');
            $(\'#mod_poll_submit\').prop(\'disabled\', false);
        }
	}

}
</script>
'; ?>

<?php endif; ?>