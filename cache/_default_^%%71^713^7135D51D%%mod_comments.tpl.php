<?php /* Smarty version 2.6.28, created on 2013-11-24 10:18:58
         compiled from mod_comments.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'mod_comments.tpl', 3, false),array('modifier', 'truncate', 'mod_comments.tpl', 3, false),array('modifier', 'rating', 'mod_comments.tpl', 3, false),array('function', 'profile_url', 'mod_comments.tpl', 6, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['comments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aid'] => $this->_tpl_vars['comment']):
?>
    <div class="mod_com_line">
    	<a class="mod_com_link" href="<?php echo $this->_tpl_vars['comment']['target_link']; ?>
#c<?php echo $this->_tpl_vars['comment']['id']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['comment']['content'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 90) : smarty_modifier_truncate($_tmp, 90)); ?>
</a> <?php if ($this->_tpl_vars['cfg']['showtarg']): ?> <?php echo ((is_array($_tmp=$this->_tpl_vars['comment']['rating'])) ? $this->_run_mod_handler('rating', true, $_tmp) : smarty_modifier_rating($_tmp)); ?>
<?php endif; ?>
    </div>
    <div class="mod_com_details">
		<?php if (! $this->_tpl_vars['comment']['is_profile']): ?><?php echo $this->_tpl_vars['comment']['author']; ?>
<?php else: ?><a class="mod_com_userlink" href="<?php echo cmsSmartyProfileURL(array('login' => $this->_tpl_vars['comment']['author']['login']), $this);?>
"><?php echo $this->_tpl_vars['comment']['author']['nickname']; ?>
</a><?php endif; ?>
    	 <?php echo $this->_tpl_vars['comment']['fpubdate']; ?>
<br/>
		<?php if ($this->_tpl_vars['cfg']['showtarg']): ?>
			<a class="mod_com_targetlink" href="<?php echo $this->_tpl_vars['comment']['target_link']; ?>
"><?php echo $this->_tpl_vars['comment']['target_title']; ?>
</a>
        <?php endif; ?>
    </div>
<?php endforeach; endif; unset($_from); ?>
<?php if ($this->_tpl_vars['cfg']['showrss']): ?>
	<div style="margin-top:15px"> <a href="/rss/comments/all/feed.rss" class="mod_latest_rss"><?php echo $this->_tpl_vars['LANG']['COMMENTS_RSS']; ?>
</a> </div>
<?php endif; ?>
<div style="margin-top:5px"> <a href="/comments" class="mod_com_all"><?php echo $this->_tpl_vars['LANG']['COMMENTS_ALL']; ?>
</a> </div>