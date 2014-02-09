<?php /* Smarty version 2.6.28, created on 2013-11-24 10:18:58
         compiled from mod_latest.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'mod_latest.tpl', 21, false),array('modifier', 'spellcount', 'mod_latest.tpl', 27, false),array('modifier', 'strip_tags', 'mod_latest.tpl', 32, false),array('modifier', 'truncate', 'mod_latest.tpl', 32, false),array('function', 'profile_url', 'mod_latest.tpl', 27, false),)), $this); ?>
<?php if ($this->_tpl_vars['cfg']['is_pag']): ?>
	<?php echo '
	<script type="text/javascript">
		function conPage(page, module_id){
            $(\'div#module_ajax_\'+module_id).css({opacity:0.4, filter:\'alpha(opacity=40)\'});
			$.post(\'/modules/mod_latest/ajax/latest.php\', {\'module_id\': module_id, \'page\':page}, function(data){
				$(\'div#module_ajax_\'+module_id).html(data);
                $(\'div#module_ajax_\'+module_id).css({opacity:1.0, filter:\'alpha(opacity=100)\'});
			});

		}
    </script>
	'; ?>

<?php endif; ?>
<?php if (! $this->_tpl_vars['is_ajax']): ?><div id="module_ajax_<?php echo $this->_tpl_vars['module_id']; ?>
"><?php endif; ?>

<?php $_from = $this->_tpl_vars['articles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aid'] => $this->_tpl_vars['article']):
?>
	<div class="mod_latest_entry">
        <?php if ($this->_tpl_vars['article']['image']): ?>
            <div class="mod_latest_image">
                <img src="/images/photos/small/<?php echo $this->_tpl_vars['article']['image']; ?>
" border="0" width="32" height="32" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['article']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"/>
            </div>
        <?php endif; ?>
	    <a class="mod_latest_title" href="<?php echo $this->_tpl_vars['article']['url']; ?>
"><?php echo $this->_tpl_vars['article']['title']; ?>
</a>
		<?php if ($this->_tpl_vars['cfg']['showdate']): ?>
            <div class="mod_latest_date">
                <?php echo $this->_tpl_vars['article']['fpubdate']; ?>
 - <a href="<?php echo cmsSmartyProfileURL(array('login' => $this->_tpl_vars['article']['user_login']), $this);?>
"><?php echo $this->_tpl_vars['article']['author']; ?>
</a><?php if ($this->_tpl_vars['cfg']['showcom']): ?> - <a href="<?php echo $this->_tpl_vars['article']['url']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['article']['comments'])) ? $this->_run_mod_handler('spellcount', true, $_tmp, $this->_tpl_vars['LANG']['COMMENT1'], $this->_tpl_vars['LANG']['COMMENT2'], $this->_tpl_vars['LANG']['COMMENT10']) : smarty_modifier_spellcount($_tmp, $this->_tpl_vars['LANG']['COMMENT1'], $this->_tpl_vars['LANG']['COMMENT2'], $this->_tpl_vars['LANG']['COMMENT10'])); ?>
" class="mod_latest_comments"><?php echo $this->_tpl_vars['article']['comments']; ?>
</a><?php endif; ?> - <span class="mod_latest_hits"><?php echo $this->_tpl_vars['article']['hits']; ?>
</span>
            </div>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['cfg']['showdesc']): ?>
            <div class="mod_latest_desc" style="overflow:hidden">
                <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['article']['description'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 200) : smarty_modifier_truncate($_tmp, 200)); ?>

            </div>
        <?php endif; ?>
	</div>
<?php endforeach; endif; unset($_from); ?>
<?php if ($this->_tpl_vars['cfg']['showrss']): ?>
	<div class="mod_latest_rss">
		<a href="/rss/content/<?php if ($this->_tpl_vars['cfg']['cat_id']): ?><?php echo $this->_tpl_vars['cfg']['cat_id']; ?>
<?php else: ?>all<?php endif; ?>/feed.rss"><?php echo $this->_tpl_vars['LANG']['LATEST_RSS']; ?>
</a>
	</div>
<?php endif; ?>
<?php if ($this->_tpl_vars['cfg']['is_pag'] && $this->_tpl_vars['pagebar_module']): ?>
    <div class="mod_latest_pagebar"><?php echo $this->_tpl_vars['pagebar_module']; ?>
</div>
<?php endif; ?>
<?php if (! $this->_tpl_vars['is_ajax']): ?></div><?php endif; ?>