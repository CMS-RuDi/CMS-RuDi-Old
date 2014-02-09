<?php /* Smarty version 2.6.28, created on 2013-11-24 10:18:57
         compiled from mod_usermenu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'profile_url', 'mod_usermenu.tpl', 4, false),)), $this); ?>
<div class="mod_user_menu">
<?php if ($this->_tpl_vars['id']): ?>
    <span class="my_profile">
        <a href="<?php echo cmsSmartyProfileURL(array('login' => $this->_tpl_vars['login']), $this);?>
"><?php echo $this->_tpl_vars['nickname']; ?>
</a>
    </span>

    <?php if ($this->_tpl_vars['is_billing']): ?>
        <span class="my_balance">
            <a href="<?php echo cmsSmartyProfileURL(array('login' => $this->_tpl_vars['login']), $this);?>
#upr_p_balance" title="<?php echo $this->_tpl_vars['LANG']['USERMENU_BALANCE']; ?>
"><?php if ($this->_tpl_vars['balance']): ?><?php echo $this->_tpl_vars['balance']; ?>
<?php else: ?>0<?php endif; ?></a>
        </span>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['users_cfg']['sw_msg']): ?>
    <span class="my_messages">
        <?php if ($this->_tpl_vars['newmsg']['total']): ?>
            <a class="has_new" href="/users/<?php echo $this->_tpl_vars['id']; ?>
/messages<?php if (! $this->_tpl_vars['newmsg']['messages']): ?>-notices<?php endif; ?>.html" title="<?php echo $this->_tpl_vars['LANG']['NEW_MESSAGES']; ?>
: <?php echo $this->_tpl_vars['newmsg']['messages']; ?>
, <?php echo $this->_tpl_vars['LANG']['NEW_NOTICES']; ?>
: <?php echo $this->_tpl_vars['newmsg']['notices']; ?>
"><?php echo $this->_tpl_vars['LANG']['USERMENU_MESS']; ?>
 (<?php echo $this->_tpl_vars['newmsg']['total']; ?>
)</a>
        <?php else: ?>
            <a href="/users/<?php echo $this->_tpl_vars['id']; ?>
/messages.html"><?php echo $this->_tpl_vars['LANG']['USERMENU_MESS']; ?>
</a>
        <?php endif; ?>
    </span>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['users_cfg']['sw_blogs']): ?>
    <span class="my_blog">
        <a href="/blogs/my_blog.html"><?php echo $this->_tpl_vars['LANG']['USERMENU_MY_BLOG']; ?>
</a>
    </span>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['users_cfg']['sw_photo']): ?>
    <span class="my_photos">
        <a href="/users/<?php echo $this->_tpl_vars['id']; ?>
/photoalbum.html"><?php echo $this->_tpl_vars['LANG']['USERMENU_PHOTOS']; ?>
</a>
    </span>
    <?php endif; ?>

    <?php if (! $this->_tpl_vars['is_audio'] && ! $this->_tpl_vars['is_video']): ?>

        <?php if ($this->_tpl_vars['is_can_add'] && ! $this->_tpl_vars['is_admin']): ?>
        <span class="my_content">
            <a href="/content/my.html"><?php echo $this->_tpl_vars['LANG']['USERMENU_ARTICLES']; ?>
</a>
        </span>

        <span class="add_content">
            <a href="/content/add.html"><?php echo $this->_tpl_vars['LANG']['USERMENU_ADD_ARTICLE']; ?>
</a>
        </span>
        <?php endif; ?>

    <?php endif; ?>

    <?php if ($this->_tpl_vars['is_audio']): ?>
        <span class="my_audio">
            <a href="/audio/library"><?php echo $this->_tpl_vars['LANG']['USERMENU_AUDIO']; ?>
 (<?php echo $this->_tpl_vars['audio_count']; ?>
)</a>
        </span>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['is_video']): ?>
        <span class="my_channel">
            <a href="/video/channel/<?php echo $this->_tpl_vars['login']; ?>
.html"><?php echo $this->_tpl_vars['LANG']['USERMENU_CHANNEL']; ?>
 (<?php echo $this->_tpl_vars['video_count']; ?>
)</a>
        </span>
        <span class="add_video">
            <a href="/video/add.html"><?php echo $this->_tpl_vars['LANG']['USERMENU_ADD_VIDEO']; ?>
</a>
        </span>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['is_admin']): ?>
    <span class="admin">
        <a href="/admin" target="_blank"><?php echo $this->_tpl_vars['LANG']['USERMENU_ADMININTER']; ?>
</a>
    </span>
    <?php endif; ?>

    <span class="logout">
        <a href="/logout"><?php echo $this->_tpl_vars['LANG']['USERMENU_EXIT']; ?>
</a>
    </span>
<?php else: ?>
    <span class="register"><a href="/registration"><?php echo $this->_tpl_vars['LANG']['TEMPLATE_REGISTRATION']; ?>
</a></span>
    <span class="login"><a href="/login"><?php echo $this->_tpl_vars['LANG']['TEMPLATE_ENTER']; ?>
</a></span>
<?php endif; ?>
</div>