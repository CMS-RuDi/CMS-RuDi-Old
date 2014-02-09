<?php /* Smarty version 2.6.28, created on 2013-11-24 10:18:57
         compiled from module.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'template', 'module.tpl', 8, false),)), $this); ?>
<div class="<?php echo $this->_tpl_vars['mod']['css_prefix']; ?>
module">
    <?php if ($this->_tpl_vars['mod']['showtitle'] != 0): ?>
        <div class="<?php echo $this->_tpl_vars['mod']['css_prefix']; ?>
moduletitle">
            <?php echo $this->_tpl_vars['mod']['title']; ?>

            <?php if ($this->_tpl_vars['cfglink']): ?>
                <span class="fast_cfg_link">
                    <a href="javascript:moduleConfig(<?php echo $this->_tpl_vars['mod']['id']; ?>
)" title="<?php echo $this->_tpl_vars['LANG']['CONFIG_MODULE']; ?>
">
                        <img src="/templates/<?php echo cmsSmartyCurrentTemplate(array(), $this);?>
/images/icons/settings.png"/>
                    </a>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="<?php echo $this->_tpl_vars['mod']['css_prefix']; ?>
modulebody"><?php echo $this->_tpl_vars['mod']['body']; ?>
</div>

</div>