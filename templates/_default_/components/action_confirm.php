<div class="con_heading"><?php echo $confirm['title']; ?></div>
<p style="font-size:18px"><?php echo $confirm['text']; ?></p>
<div style="margin-top:20px">
    <form action="<?php echo $this->escape(!empty($confirm['action']) ? $confirm['action'] : ''); ?>" method="<?php echo (!empty($confirm['method']) ? $confirm['method'] : 'POST'); ?>">
        <?php echo $confirm['other']; ?>
        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
        <input style="font-size:24px; width:100px"
               type="<?php echo (!empty($confirm['yes_button']['type']) ? $confirm['yes_button']['type'] : 'submit'); ?>"
               name="<?php echo (!empty($confirm['yes_button']['name']) ? $confirm['yes_button']['name'] : 'go'); ?>"
               value="<?php echo (!empty($confirm['yes_button']['title']) ? $confirm['yes_button']['title'] : $_LANG['YES']); ?>"
               onclick="<?php echo (!empty($confirm['yes_button']['onclick']) ? $confirm['yes_button']['onclick'] : true); ?>"
        />
        <input style="font-size:24px; width:100px"
               type="<?php echo (!empty($confirm['no_button']['type']) ? $confirm['no_button']['type'] : 'button'); ?>"
               name="<?php echo (!empty($confirm['no_button']['name']) ? $confirm['no_button']['name'] : 'cancel'); ?>"
               value="<?php echo (!empty($confirm['no_button']['title']) ? $confirm['no_button']['title'] : $_LANG['NO']); ?>"
               onclick="<?php echo (!empty($confirm['no_button']['onclick']) ? $confirm['no_button']['onclick'] : 'window.history.go(-1)'); ?>"
        />
    </form>
</div>