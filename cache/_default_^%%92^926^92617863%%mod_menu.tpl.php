<?php /* Smarty version 2.6.28, created on 2013-11-24 10:18:58
         compiled from mod_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'mod_menu.tpl', 17, false),array('modifier', 'escape', 'mod_menu.tpl', 29, false),)), $this); ?>
<ul id="<?php echo $this->_tpl_vars['menu']; ?>
" class="menu">

    <?php if ($this->_tpl_vars['cfg']['show_home']): ?>

        <li <?php if ($this->_tpl_vars['menuid'] == 1): ?>class="selected"<?php endif; ?>>

            <a href="/" <?php if ($this->_tpl_vars['menuid'] == 1): ?>class="selected"<?php endif; ?>><span><?php echo $this->_tpl_vars['LANG']['PATH_HOME']; ?>
</span></a>

        </li>

    <?php endif; ?>

    <?php $_from = $this->_tpl_vars['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>

        <?php if ($this->_tpl_vars['item']['NSLevel'] == $this->_tpl_vars['last_level']): ?></li><?php endif; ?>

        <?php echo smarty_function_math(array('equation' => "x - y",'x' => $this->_tpl_vars['last_level'],'y' => $this->_tpl_vars['item']['NSLevel'],'assign' => 'tail'), $this);?>


        <?php unset($this->_sections['foo']);
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['start'] = (int)0;
$this->_sections['foo']['loop'] = is_array($_loop=$this->_tpl_vars['tail']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
if ($this->_sections['foo']['start'] < 0)
    $this->_sections['foo']['start'] = max($this->_sections['foo']['step'] > 0 ? 0 : -1, $this->_sections['foo']['loop'] + $this->_sections['foo']['start']);
else
    $this->_sections['foo']['start'] = min($this->_sections['foo']['start'], $this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] : $this->_sections['foo']['loop']-1);
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = min(ceil(($this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] - $this->_sections['foo']['start'] : $this->_sections['foo']['start']+1)/abs($this->_sections['foo']['step'])), $this->_sections['foo']['max']);
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
?>

            </li></ul></li>

        <?php endfor; endif; ?>

        <?php if ($this->_tpl_vars['item']['NSLevel'] > 1 && $this->_tpl_vars['item']['NSLevel'] > $this->_tpl_vars['last_level']): ?><ul><?php endif; ?>

            <li <?php if ($this->_tpl_vars['menuid'] == $this->_tpl_vars['item']['id'] || ( $this->_tpl_vars['currentmenu']['NSLeft'] > $this->_tpl_vars['item']['NSLeft'] && $this->_tpl_vars['currentmenu']['NSRight'] < $this->_tpl_vars['item']['NSRight'] )): ?>class="selected"<?php endif; ?>>

                <a href="<?php echo $this->_tpl_vars['item']['link']; ?>
" target="<?php echo $this->_tpl_vars['item']['target']; ?>
" <?php if ($this->_tpl_vars['menuid'] == $this->_tpl_vars['item']['id']): ?>class="selected"<?php endif; ?> title="<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">

                    <span>

                        <?php if ($this->_tpl_vars['item']['iconurl']): ?><img src="/images/menuicons/<?php echo $this->_tpl_vars['item']['iconurl']; ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /><?php endif; ?>

                        <?php echo $this->_tpl_vars['item']['title']; ?>


                    </span>

                </a>

        <?php $this->assign('last_level', $this->_tpl_vars['item']['NSLevel']); ?>

    <?php endforeach; endif; unset($_from); ?>

    <?php unset($this->_sections['foo']);
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['start'] = (int)0;
$this->_sections['foo']['loop'] = is_array($_loop=$this->_tpl_vars['last_level']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
if ($this->_sections['foo']['start'] < 0)
    $this->_sections['foo']['start'] = max($this->_sections['foo']['step'] > 0 ? 0 : -1, $this->_sections['foo']['loop'] + $this->_sections['foo']['start']);
else
    $this->_sections['foo']['start'] = min($this->_sections['foo']['start'], $this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] : $this->_sections['foo']['loop']-1);
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = min(ceil(($this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] - $this->_sections['foo']['start'] : $this->_sections['foo']['start']+1)/abs($this->_sections['foo']['step'])), $this->_sections['foo']['max']);
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
?>

        </li>

    <?php endfor; endif; ?>

</ul>