<?php if ($is_allow) { ?>

    <?php if ($myprofile || $is_admin) { ?>
        <div class="float_bar" style="background: none">
            <a class="usr_photo_link_edit" href="/users/<?php echo $usr['id']; ?>/editphoto<?php echo $photo['id']; ?>.html"><?php echo $_LANG['EDIT']; ?></a>
            <a class="usr_photo_link_delete"  href="/users/<?php echo $usr['id']; ?>/delphoto<?php echo $photo['id']; ?>.html"><?php echo $_LANG['DELETE']; ?></a>
        </div>
    <?php } ?>

    <div class="con_heading"><?php echo $photo['title']; ?></div>

    <div class="bar">
        <?php echo $photo['genderlink']; ?> &mdash; <?php echo $photo['pubdate']; ?> &mdash; <strong><?php echo $_LANG['HITS']; ?>:</strong> <?php echo $photo['hits']; ?>
    </div>

    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>

            <td width="50%">
                <?php if ($previd) { ?>
                    <a class="usr_photo_prev_link" href="/users/<?php echo $usr['id']; ?>/photo<?php echo $previd['id']; ?>.html" title="<?php echo $this->escape($previd['title']); ?>"></a>
                <?php } else { ?>
                    &nbsp;
                <?php } ?>
            </td>

            <td>
                <div class="usr_photo_view">
                    <?php if ($nextid) { ?><a href="/users/<?php echo $usr['id']; ?>/photo<?php echo $nextid['id']; ?>.html"><?php } ?>
                        <img border="0" src="/images/users/photos/medium/<?php echo $photo['imageurl']; ?>" alt="<?php echo $this->escape($photo['title']); ?>" />
                    <?php if ($nextid) { ?></a><?php } ?>
                </div>
            </td>

            <td width="50%">
                <?php if ($nextid) { ?>
                    <a class="usr_photo_next_link" href="/users/<?php echo $usr['id']; ?>/photo<?php echo $nextid['id']; ?>.html" title="<?php echo $this->escape($nextid['title']); ?>"></a>
                <?php } else { ?>
                    &nbsp;
                <?php } ?>
            </td>

        </tr>
    </table>

    <?php if ($photo['description']) { ?>
        <div class="photo_desc"><?php echo $photo['description']; ?></div>
    <?php } ?>

    <?php echo $tagbar; ?>

<?php } else { ?>
    <div class="con_heading"><?php echo $photo['title']; ?></div>

    <div class="bar">
        <?php echo $photo['genderlink']; ?> &mdash; <?php echo $photo['pubdate']; ?> &mdash; <strong><?php echo $_LANG['HITS']; ?>:</strong> <?php echo $photo['hits']; ?>
    </div>

    <table cellpadding="0" cellspacing="0" border="0" width="100%" height="300">
        <tr>

            <td width="30%">
                <?php if ($previd) { ?>
                    <a class="usr_photo_prev_link" href="/users/<?php echo $usr['id']; ?>/photo<?php echo $previd['id']; ?>.html" title="<?php echo $this->escape($previd['title']); ?>"></a>
                <?php } else { ?>
                    &nbsp;
                <?php } ?>
            </td>

            <td width="40%">
                <div class="usr_photo_view">
                    <?php if ($nextid) { ?><a href="/users/<?php echo $usr['id']; ?>/photo<?php echo $nextid['id']; ?>.html"><?php } ?>
                        <span><?php echo $_LANG['PHOTO_NOT_FOUND_TEXT']; ?></span>
                    <?php if ($nextid) { ?></a><?php } ?>
                </div>
            </td>

            <td width="30%">
                <?php if ($nextid) { ?>
                    <a class="usr_photo_next_link" href="/users/<?php echo $usr['id']; ?>/photo<?php echo $nextid['id']; ?>.html" title="<?php echo $this->escape($nextid['title']); ?>"></a>
                <?php } else { ?>
                    &nbsp;
                <?php } ?>
            </td>

        </tr>
    </table>

<?php } ?>