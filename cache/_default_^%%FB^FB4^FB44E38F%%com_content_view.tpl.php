<?php /* Smarty version 2.6.28, created on 2013-11-24 10:19:02
         compiled from com_content_view.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'template', 'com_content_view.tpl', 8, false),array('function', 'math', 'com_content_view.tpl', 40, false),array('function', 'profile_url', 'com_content_view.tpl', 82, false),array('modifier', 'escape', 'com_content_view.tpl', 42, false),array('modifier', 'truncate', 'com_content_view.tpl', 45, false),array('modifier', 'spellcount', 'com_content_view.tpl', 87, false),)), $this); ?>
<?php if (! $this->_tpl_vars['is_homepage']): ?>
    <?php if ($this->_tpl_vars['cat']['showrss']): ?>
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
			<td><h1 class="con_heading"><?php echo $this->_tpl_vars['pagetitle']; ?>
</h1></td>
			<td valign="top" style="padding-left:6px">
                        <div class="con_rss_icon">
                            <a href="/rss/content/<?php echo $this->_tpl_vars['cat']['id']; ?>
/feed.rss" title="<?php echo $this->_tpl_vars['LANG']['RSS']; ?>
"><img src="/templates/<?php echo cmsSmartyCurrentTemplate(array(), $this);?>
/images/icons/rss.png" border="0" alt="<?php echo $this->_tpl_vars['LANG']['RSS']; ?>
"/></a>
                        </div>
                </td>
        </table>
    <?php else: ?>
        <h1 class="con_heading"><?php echo $this->_tpl_vars['pagetitle']; ?>
</h1>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['cat']['description']): ?>
        <div class="con_description"><?php echo $this->_tpl_vars['cat']['description']; ?>
</div>
    <?php endif; ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['subcats']): ?>
	<div class="categorylist">
		<?php $_from = $this->_tpl_vars['subcats']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tid'] => $this->_tpl_vars['subcat']):
?>
            <div class="subcat">
                <a href="<?php echo $this->_tpl_vars['subcat']['url']; ?>
" class="con_subcat"><?php echo $this->_tpl_vars['subcat']['title']; ?>
</a> (<?php echo $this->_tpl_vars['subcat']['content_count']; ?>
)
                <div class="con_description"><?php echo $this->_tpl_vars['subcat']['description']; ?>
</div>
            </div>
		<?php endforeach; endif; unset($_from); ?>
	</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['cat_photos']): ?>
    <?php if ($this->_tpl_vars['cat_photos']['album']['title']): ?>
        <h3><?php echo $this->_tpl_vars['cat_photos']['album']['title']; ?>
</h3>
    <?php endif; ?>
    <?php $this->assign('fcol', '1'); ?>
    <table cellpadding="0" cellspacing="0" border="0">
        <?php $_from = $this->_tpl_vars['cat_photos']['photos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tid'] => $this->_tpl_vars['con']):
?>
            <?php if ($this->_tpl_vars['fcol'] == 1): ?> <tr> <?php endif; ?>
            <td align="center" valign="middle" width="<?php echo smarty_function_math(array('equation' => "100/x",'x' => $this->_tpl_vars['cat_photos']['album']['maxcols']), $this);?>
%">
                <div class="photo_thumb" align="center">
                    <a class="lightbox-enabled" rel="lightbox-galery" href="/images/photos/medium/<?php echo $this->_tpl_vars['con']['file']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['con']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
                        <img class="photo_thumb_img" src="/images/photos/small/<?php echo $this->_tpl_vars['con']['file']; ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['con']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" border="0" />
                    </a><br />
                    <a href="/photos/photo<?php echo $this->_tpl_vars['con']['id']; ?>
.html" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['con']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['con']['title'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</a>
                </div>
            </td>
        <?php if ($this->_tpl_vars['fcol'] == $this->_tpl_vars['cat_photos']['album']['maxcols']): ?> </tr> <?php $this->assign('fcol', '1'); ?> <?php else: ?> <?php echo smarty_function_math(array('equation' => "x + 1",'x' => $this->_tpl_vars['fcol'],'assign' => 'fcol'), $this);?>
 <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
        <?php if ($this->_tpl_vars['fcol'] > 1): ?>
            <td colspan="<?php echo smarty_function_math(array('equation' => "y - x + 1",'x' => $this->_tpl_vars['fcol'],'y' => $this->_tpl_vars['cat_photos']['album']['maxcols']), $this);?>
">&nbsp;</td></tr>
        <?php endif; ?>
   </table>
<?php endif; ?>

<?php if ($this->_tpl_vars['articles']): ?>
	<?php $this->assign('col', '1'); ?>
	<table class="contentlist" cellspacing="2" border="0" width="100%">
		<?php $_from = $this->_tpl_vars['articles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tid'] => $this->_tpl_vars['article']):
?>
            <?php if ($this->_tpl_vars['col'] == 1): ?> <tr> <?php endif; ?>
                <td width="20" valign="top">
                    <img src="/templates/<?php echo cmsSmartyCurrentTemplate(array(), $this);?>
/images/icons/article.png" border="0" class="con_icon"/>
                </td>
                <td width="" valign="top">
                    <div class="con_title">
                        <a href="<?php echo $this->_tpl_vars['article']['url']; ?>
" class="con_titlelink"><?php echo $this->_tpl_vars['article']['title']; ?>
</a>
                    </div>
                    <?php if ($this->_tpl_vars['cat']['showdesc']): ?>
                        <div class="con_desc">
                            <?php if ($this->_tpl_vars['article']['image']): ?>
                                <div class="con_image">
                                    <img src="/images/photos/small/<?php echo $this->_tpl_vars['article']['image']; ?>
" border="0" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['article']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"/>
                                </div>
                            <?php endif; ?>
                            <?php echo $this->_tpl_vars['article']['description']; ?>

                        </div>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['cat']['showcomm'] || $this->_tpl_vars['showdate'] || ( $this->_tpl_vars['cat']['showtags'] && $this->_tpl_vars['article']['tagline'] )): ?>
                        <div class="con_details">
                            <?php if ($this->_tpl_vars['showdate']): ?>
                                <?php echo $this->_tpl_vars['article']['fpubdate']; ?>
 - <a href="<?php echo cmsSmartyProfileURL(array('login' => $this->_tpl_vars['article']['user_login']), $this);?>
" style="color:#666"><?php echo $this->_tpl_vars['article']['author']; ?>
</a>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['cat']['showcomm']): ?>
                                <?php if ($this->_tpl_vars['showdate']): ?> | <?php endif; ?>
                                <a href="<?php echo $this->_tpl_vars['article']['url']; ?>
" title="<?php echo $this->_tpl_vars['LANG']['DETAIL']; ?>
"><?php echo $this->_tpl_vars['LANG']['DETAIL']; ?>
</a>
                                | <a href="<?php echo $this->_tpl_vars['article']['url']; ?>
#c" title="<?php echo $this->_tpl_vars['LANG']['COMMENTS']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['article']['comments'])) ? $this->_run_mod_handler('spellcount', true, $_tmp, $this->_tpl_vars['LANG']['COMMENT1'], $this->_tpl_vars['LANG']['COMMENT2'], $this->_tpl_vars['LANG']['COMMENT10']) : smarty_modifier_spellcount($_tmp, $this->_tpl_vars['LANG']['COMMENT1'], $this->_tpl_vars['LANG']['COMMENT2'], $this->_tpl_vars['LANG']['COMMENT10'])); ?>
</a>
                            <?php endif; ?>
                             | <?php echo ((is_array($_tmp=$this->_tpl_vars['article']['hits'])) ? $this->_run_mod_handler('spellcount', true, $_tmp, $this->_tpl_vars['LANG']['HIT'], $this->_tpl_vars['LANG']['HIT2'], $this->_tpl_vars['LANG']['HIT10']) : smarty_modifier_spellcount($_tmp, $this->_tpl_vars['LANG']['HIT'], $this->_tpl_vars['LANG']['HIT2'], $this->_tpl_vars['LANG']['HIT10'])); ?>

                            <?php if ($this->_tpl_vars['cat']['showtags'] && $this->_tpl_vars['article']['tagline']): ?>
                                <?php if ($this->_tpl_vars['showdate'] || $this->_tpl_vars['cat']['showcomm']): ?> <br/> <?php endif; ?>
                                <?php if ($this->_tpl_vars['article']['tagline']): ?> <strong><?php echo $this->_tpl_vars['LANG']['TAGS']; ?>
:</strong> <?php echo $this->_tpl_vars['article']['tagline']; ?>
 <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </td>
                <?php if ($this->_tpl_vars['col'] == $this->_tpl_vars['cat']['maxcols']): ?> </tr> <?php $this->assign('col', '1'); ?> <?php else: ?> <?php echo smarty_function_math(array('equation' => "x + 1",'x' => $this->_tpl_vars['col'],'assign' => 'col'), $this);?>
 <?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php if ($this->_tpl_vars['col'] > 1): ?>
			<td colspan="<?php echo smarty_function_math(array('equation' => "y - x + 1",'x' => $this->_tpl_vars['col'],'y' => $this->_tpl_vars['cat']['maxcols']), $this);?>
">&nbsp;</td></tr>
		<?php endif; ?>
	</table>
	<?php echo $this->_tpl_vars['pagebar']; ?>

<?php endif; ?>