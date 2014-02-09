<?php /* Smarty version 2.6.28, created on 2013-11-24 10:18:57
         compiled from mod_lastreg.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'profile_url', 'mod_lastreg.tpl', 4, false),array('function', 'math', 'mod_lastreg.tpl', 17, false),array('modifier', 'escape', 'mod_lastreg.tpl', 15, false),)), $this); ?>
<?php if ($this->_tpl_vars['cfg']['view_type'] == 'table'): ?>
  <?php $_from = $this->_tpl_vars['usrs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aid'] => $this->_tpl_vars['usr']):
?>
    <div class="mod_new_user">
        <div class="mod_new_user_avatar"><a href="<?php echo cmsSmartyProfileURL(array('login' => $this->_tpl_vars['usr']['login']), $this);?>
"><img border="0" class="usr_img_small" src="<?php echo $this->_tpl_vars['usr']['avatar']; ?>
" /></a></div>
        <div class="mod_new_user_link"><a href="<?php echo cmsSmartyProfileURL(array('login' => $this->_tpl_vars['usr']['login']), $this);?>
"><?php echo $this->_tpl_vars['usr']['nickname']; ?>
</a></div>
    </div>
  <?php endforeach; endif; unset($_from); ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['cfg']['view_type'] == 'hr_table'): ?>
    <?php $this->assign('col', '1'); ?>
    <table cellspacing="5" border="0" width="100%">
          <?php $_from = $this->_tpl_vars['usrs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aid'] => $this->_tpl_vars['usr']):
?>
            <?php if ($this->_tpl_vars['col'] == 1): ?> <tr> <?php endif; ?>
                    <td width="" class="new_user_avatar" align="center" valign="middle"><a href="<?php echo cmsSmartyProfileURL(array('login' => $this->_tpl_vars['usr']['login']), $this);?>
" class="new_user_link" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['usr']['nickname'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><img border="0" class="usr_img_small" src="<?php echo $this->_tpl_vars['usr']['avatar']; ?>
" /></a><div class="mod_new_user_link"><a href="<?php echo cmsSmartyProfileURL(array('login' => $this->_tpl_vars['usr']['login']), $this);?>
"><?php echo $this->_tpl_vars['usr']['nickname']; ?>
</a></div>
                    </td>
            <?php if ($this->_tpl_vars['col'] == $this->_tpl_vars['cfg']['maxcool']): ?> </tr> <?php $this->assign('col', '1'); ?> <?php else: ?> <?php echo smarty_function_math(array('equation' => "x + 1",'x' => $this->_tpl_vars['col'],'assign' => 'col'), $this);?>
 <?php endif; ?>
          <?php endforeach; endif; unset($_from); ?>
    </table>
<?php endif; ?>

<?php if ($this->_tpl_vars['cfg']['view_type'] == 'list'): ?>
    <?php $this->assign('now', '0'); ?>
        <?php $_from = $this->_tpl_vars['usrs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aid'] => $this->_tpl_vars['usr']):
?>
            <a href="<?php echo cmsSmartyProfileURL(array('login' => $this->_tpl_vars['usr']['login']), $this);?>
" class="new_user_link"><?php echo $this->_tpl_vars['usr']['nickname']; ?>
</a>
            <?php echo smarty_function_math(array('equation' => "x + 1",'x' => $this->_tpl_vars['now'],'assign' => 'now'), $this);?>

            <?php if ($this->_tpl_vars['now'] == $this->_tpl_vars['total']): ?><?php else: ?> ,<?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
        <p><strong><?php echo $this->_tpl_vars['LANG']['LASTREG_TOTAL']; ?>
:</strong> <?php echo $this->_tpl_vars['total_all']; ?>
</p>
<?php endif; ?>