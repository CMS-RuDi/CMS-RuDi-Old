<?php /* Smarty version 2.6.28, created on 2013-11-24 10:18:58
         compiled from mod_forum.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'template', 'mod_forum.tpl', 6, false),array('modifier', 'strip_tags', 'mod_forum.tpl', 14, false),array('modifier', 'truncate', 'mod_forum.tpl', 14, false),)), $this); ?>
<table width="100%" cellspacing="0" cellpadding="5" border="0" >
    <?php $_from = $this->_tpl_vars['threads']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tid'] => $this->_tpl_vars['thread']):
?>
        <tr>
            <td align="left" width="100"><div class="<?php if ($this->_tpl_vars['thread']['is_new']): ?>mod_fweb2_date_new<?php endif; ?> mod_fweb2_date" <?php if ($this->_tpl_vars['thread']['is_new']): ?>title="<?php echo $this->_tpl_vars['LANG']['HAVE_NEW_MESS']; ?>
"<?php endif; ?>><?php echo $this->_tpl_vars['thread']['last_msg_array']['fpubdate']; ?>
</div></td>
            <td width="13">
                <img src="/templates/<?php echo cmsSmartyCurrentTemplate(array(), $this);?>
/images/icons/user_comment.png" border="0" />
            </td>
            <td style="padding-left:0px"><?php echo $this->_tpl_vars['thread']['last_msg_array']['user_link']; ?>
 <?php if ($this->_tpl_vars['thread']['last_msg_array']['post_count'] == 1): ?><?php echo $this->_tpl_vars['LANG']['FORUM_START_THREAD']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG']['FORUM_REPLY_THREAD']; ?>
<?php endif; ?> &laquo;<?php echo $this->_tpl_vars['thread']['last_msg_array']['thread_link']; ?>
&raquo;
            <?php if ($this->_tpl_vars['cfg']['showforum']): ?> <?php echo $this->_tpl_vars['LANG']['FORUM_ON_FORUM']; ?>
 &laquo;<a href="/forum/<?php echo $this->_tpl_vars['thread']['forum_id']; ?>
"><?php echo $this->_tpl_vars['thread']['forum_title']; ?>
</a>&raquo;<?php endif; ?></td>
        </tr>

        <?php if ($this->_tpl_vars['cfg']['showtext']): ?>
        <tr>
            <td colspan="3"><div class="mod_fweb2_shorttext"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['thread']['last_msg_array']['content_html'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 200) : smarty_modifier_truncate($_tmp, 200)); ?>
</div></td>
        </tr>
        <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
</table>