<?php /* Smarty version 2.6.28, created on 2013-11-24 10:18:58
         compiled from mod_whoonline.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'implode', 'mod_whoonline.tpl', 2, false),)), $this); ?>
<?php if ($this->_tpl_vars['users']): ?>
    <?php echo ((is_array($_tmp=", ")) ? $this->_run_mod_handler('implode', true, $_tmp, $this->_tpl_vars['users']) : implode($_tmp, $this->_tpl_vars['users'])); ?>

<?php else: ?>
    <div><strong><?php echo $this->_tpl_vars['LANG']['WHOONLINE_USERS']; ?>
:</strong> 0</div>
<?php endif; ?>
<div style="margin-top:10px"><strong><?php echo $this->_tpl_vars['LANG']['WHOONLINE_GUESTS']; ?>
:</strong> <?php echo $this->_tpl_vars['guests']; ?>
</div>

<?php if ($this->_tpl_vars['cfg']['show_today']): ?>
    <div style="margin-top:10px;margin-bottom:8px"><strong><?php echo $this->_tpl_vars['LANG']['WAS_TODAY']; ?>
:</strong></div>
    <?php if ($this->_tpl_vars['today_users']): ?>
        <?php echo ((is_array($_tmp=", ")) ? $this->_run_mod_handler('implode', true, $_tmp, $this->_tpl_vars['today_users']) : implode($_tmp, $this->_tpl_vars['today_users'])); ?>

    <?php else: ?>
        <div><?php echo $this->_tpl_vars['LANG']['NOBODY_TODAY']; ?>
</div>
    <?php endif; ?>
<?php endif; ?>