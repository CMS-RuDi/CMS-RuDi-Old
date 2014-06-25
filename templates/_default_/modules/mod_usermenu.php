<div class="mod_user_menu">
<?php if ($id) { ?>
    <span class="my_profile">
        <a href="<?php echo cmsUser::getProfileURL($login); ?>"><?php echo $nickname; ?></a>
    </span>

    <?php if ($is_billing) { ?>
        <span class="my_balance">
            <a href="<?php echo cmsUser::getProfileURL($login); ?>#upr_p_balance" title="<?php echo $_LANG['USERMENU_BALANCE']; ?>"><?php if ($balance) { ?><?php echo $balance; ?><?php } else { ?>0<?php } ?></a>
        </span>
    <?php } ?>

    <?php if ($users_cfg['sw_msg']) { ?>
    <span class="my_messages">
        <?php if ($newmsg['total']) { ?>
            <a class="has_new" href="/users/<?php echo $id; ?>/messages<?php if (!$newmsg['messages']) { ?>-notices<?php } ?>.html" title="<?php echo $_LANG['NEW_MESSAGES']; ?>: <?php echo $newmsg['messages']; ?>, <?php echo $_LANG['NEW_NOTICES']; ?>: <?php echo $newmsg['notices']; ?>"><?php echo $_LANG['USERMENU_MESS']; ?> (<?php echo $newmsg['total']; ?>)</a>
        <?php } else { ?>
            <a href="/users/<?php echo $id; ?>/messages.html"><?php echo $_LANG['USERMENU_MESS']; ?></a>
        <?php } ?>
    </span>
    <?php } ?>

    <?php if ($users_cfg['sw_blogs']) { ?>
    <span class="my_blog">
        <a href="/blogs/my_blog.html"><?php echo $_LANG['USERMENU_MY_BLOG']; ?></a>
    </span>
    <?php } ?>

    <?php if ($users_cfg['sw_photo']) { ?>
    <span class="my_photos">
        <a href="/users/<?php echo $id; ?>/photoalbum.html"><?php echo $_LANG['USERMENU_PHOTOS']; ?></a>
    </span>
    <?php } ?>

    <?php if (!$is_audio && !$is_video) { ?>

        <?php if ($is_can_add && !$is_admin) { ?>
        <span class="my_content">
            <a href="/content/my.html"><?php echo $_LANG['USERMENU_ARTICLES']; ?></a>
        </span>

        <span class="add_content">
            <a href="/content/add.html"><?php echo $_LANG['USERMENU_ADD_ARTICLE']; ?></a>
        </span>
        <?php } ?>

    <?php } ?>
    
    <?php if ($is_music) { ?>
        <span class="my_music">
            <a href="/music/channel/<?php echo $id; ?>"><?php echo $_LANG['USERMENU_MUSIC']; ?> (<?php echo $music_count; ?>)</a>
        </span>
    <?php } ?>

    <?php if ($is_audio) { ?>
        <span class="my_audio">
            <a href="/audio/library"><?php echo $_LANG['USERMENU_AUDIO']; ?> (<?php echo $audio_count; ?>)</a>
        </span>
    <?php } ?>
    
    <?php if ($is_video) { ?>
        <span class="my_channel">
            <a href="/video/channel/<?php echo $login; ?>.html"><?php echo $_LANG['USERMENU_CHANNEL']; ?> (<?php echo $video_count; ?>)</a>
        </span>
        <span class="add_video">
            <a href="/video/add.html"><?php echo $_LANG['USERMENU_ADD_VIDEO']; ?></a>
        </span>
    <?php } ?>

    <?php if ($is_admin) { ?>
    <span class="admin">
        <a href="/admin" target="_blank"><?php echo $_LANG['USERMENU_ADMININTER']; ?></a>
    </span>
    <?php } ?>

    <span class="logout">
        <a href="/logout"><?php echo $_LANG['USERMENU_EXIT']; ?></a>
    </span>
<?php } else { ?>
    <span class="register"><a href="/registration"><?php echo $_LANG['TEMPLATE_REGISTRATION']; ?></a></span>
    <span class="login"><a href="/login"><?php echo $_LANG['TEMPLATE_ENTER']; ?></a></span>
<?php } ?>
</div>