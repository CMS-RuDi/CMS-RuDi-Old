<table cellspacing="5" border="0" cellpadding="3" class="mod_user_rating">
<?php foreach ($users as $usr) { ?>
    <tr>
        <td width="20" class="avatar"><a href="<?php echo cmsUser::getProfileURL($usr['login']); ?>"><img border="0" class="usr_img_small" src="<?php echo $usr['avatar']; ?>" /></a></td>
        <td width="">
            <?php echo $usr['user_link']; ?>
            
            <?php if ($cfg['view_type'] == 'rating') { ?>
                <div class="rating"><?php echo $this->rating($usr['rating']); ?></div>
            <?php } else { ?>
                <div class="karma"><?php echo $this->rating($usr['karma']); ?></div>
            <?php } ?>
            
            <?php if ($usr['microstatus']) { ?>
                <div class="microstatus"><?php echo $usr['microstatus']; ?></div>
            <?php } ?>
        </td>
    </tr>
<?php } ?>
</table>