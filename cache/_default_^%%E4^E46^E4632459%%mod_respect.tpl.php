<?php /* Smarty version 2.6.28, created on 2013-11-24 10:18:57
         compiled from mod_respect.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'profile_url', 'mod_respect.tpl', 5, false),array('modifier', 'escape', 'mod_respect.tpl', 5, false),)), $this); ?>
<table width="100%" border="0" cellpadding="2" cellspacing="1">
    <?php $_from = $this->_tpl_vars['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['user']):
?>
        <tr>
            <td width="30">
                <a href="<?php echo cmsSmartyProfileURL(array('login' => $this->_tpl_vars['user']['login']), $this);?>
#upr_awards" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['user']['nickname'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><img src="<?php echo $this->_tpl_vars['user']['avatar']; ?>
" border="0" /></a>
            </td>
            <td valign="top">
                <div style="margin-left:15px;">
                    <a style="font-size:16px;font-weight:bold;" href="<?php echo cmsSmartyProfileURL(array('login' => $this->_tpl_vars['user']['login']), $this);?>
#upr_awards" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['user']['nickname'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><?php echo $this->_tpl_vars['user']['nickname']; ?>
</a>
                    <?php if ($this->_tpl_vars['cfg']['show_awards']): ?>
                        <div style="margin-top:6px">
                            <?php $_from = $this->_tpl_vars['user']['awards']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['award']):
?>
                                <img src="/images/icons/award.gif" border="0" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['award']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
                            <?php endforeach; endif; unset($_from); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    <?php endforeach; endif; unset($_from); ?>
</table>