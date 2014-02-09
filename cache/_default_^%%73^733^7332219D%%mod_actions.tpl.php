<?php /* Smarty version 2.6.28, created on 2013-11-24 10:18:57
         compiled from mod_actions.tpl */ ?>
<?php if ($this->_tpl_vars['actions']): ?>
    <div class="actions_list">
        <?php $_from = $this->_tpl_vars['actions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aid'] => $this->_tpl_vars['action']):
?>
            <div class="action_entry act_<?php echo $this->_tpl_vars['action']['name']; ?>
">
                <div class="action_date<?php if ($this->_tpl_vars['action']['is_new'] && $this->_tpl_vars['user_id'] != $this->_tpl_vars['action']['user_id']): ?> is_new<?php endif; ?>"><?php echo $this->_tpl_vars['action']['pubdate']; ?>
 <?php echo $this->_tpl_vars['LANG']['BACK']; ?>
</div>
                <div class="action_title">
                    <a href="<?php echo $this->_tpl_vars['action']['user_url']; ?>
" class="action_user"><?php echo $this->_tpl_vars['action']['user_nickname']; ?>
</a>
                    <?php if ($this->_tpl_vars['action']['message']): ?>
                        <?php echo $this->_tpl_vars['action']['message']; ?>
<?php if ($this->_tpl_vars['action']['description']): ?>:<?php endif; ?>
                    <?php else: ?>
                        <?php if ($this->_tpl_vars['action']['description']): ?>
                            &rarr; <?php echo $this->_tpl_vars['action']['description']; ?>

                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php if ($this->_tpl_vars['action']['message']): ?>
                    <?php if ($this->_tpl_vars['action']['description']): ?>
                        <div class="action_details"><?php echo $this->_tpl_vars['action']['description']; ?>
</div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; endif; unset($_from); ?>
    </div>
    <?php if ($this->_tpl_vars['cfg']['show_link']): ?>
    <p>
        <a href="/actions" class="mod_act_all"><?php echo $this->_tpl_vars['LANG']['ALL_ACTIONS']; ?>
</a>
    </p>
    <?php endif; ?>
<?php endif; ?>