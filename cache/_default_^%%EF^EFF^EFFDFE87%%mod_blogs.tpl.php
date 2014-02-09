<?php /* Smarty version 2.6.28, created on 2013-11-24 10:18:57
         compiled from mod_blogs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'profile_url', 'mod_blogs.tpl', 5, false),array('modifier', 'escape', 'mod_blogs.tpl', 5, false),array('modifier', 'truncate', 'mod_blogs.tpl', 8, false),array('modifier', 'spellcount', 'mod_blogs.tpl', 11, false),array('modifier', 'rating', 'mod_blogs.tpl', 11, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['posts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tid'] => $this->_tpl_vars['post']):
?>
    <div class="mod_latest_entry">

        <div class="mod_latest_image">
            <a href="<?php echo cmsSmartyProfileURL(array('login' => $this->_tpl_vars['post']['login']), $this);?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['post']['author'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><img border="0" class="usr_img_small" src="<?php echo $this->_tpl_vars['post']['author_avatar']; ?>
" /></a>
        </div>

        <a class="mod_latest_blog_title" href="<?php echo $this->_tpl_vars['post']['url']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['post']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['post']['title'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 70) : smarty_modifier_truncate($_tmp, 70)); ?>
</a>

        <div class="mod_latest_date">
            <?php echo $this->_tpl_vars['post']['fpubdate']; ?>
 - <a href="<?php echo $this->_tpl_vars['post']['blog_url']; ?>
"><?php echo $this->_tpl_vars['post']['blog_title']; ?>
</a> - <a href="<?php echo $this->_tpl_vars['post']['url']; ?>
#c" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['post']['comments_count'])) ? $this->_run_mod_handler('spellcount', true, $_tmp, $this->_tpl_vars['LANG']['COMMENT1'], $this->_tpl_vars['LANG']['COMMENT2'], $this->_tpl_vars['LANG']['COMMENT10']) : smarty_modifier_spellcount($_tmp, $this->_tpl_vars['LANG']['COMMENT1'], $this->_tpl_vars['LANG']['COMMENT2'], $this->_tpl_vars['LANG']['COMMENT10'])); ?>
" class="mod_latest_comments"><?php echo $this->_tpl_vars['post']['comments_count']; ?>
</a> - <span class="mod_latest_rating"><?php echo ((is_array($_tmp=$this->_tpl_vars['post']['rating'])) ? $this->_run_mod_handler('rating', true, $_tmp) : smarty_modifier_rating($_tmp)); ?>
</span>
        </div>

    </div>
<?php endforeach; endif; unset($_from); ?>

<?php if ($this->_tpl_vars['cfg']['showrss']): ?>
    <div class="mod_latest_rss">
        <a href="/rss/blogs/all/feed.rss"><?php echo $this->_tpl_vars['LANG']['RSS']; ?>
</a>
    </div>
<?php endif; ?>