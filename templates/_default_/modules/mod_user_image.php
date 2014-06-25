<?php foreach ($users as $usr) { ?>
    <div align="center">
        <a href="/users/<?php echo $usr['uid']; ?>/photo<?php echo $usr['id']; ?>.html">
            <img src="/images/users/photos/small/<?php echo $usr['imageurl']; ?>" border="0" />
        </a>
    </div>
    <?php if ($cfg['showtitle']) { ?>
        <div style="margin-top:5px" align="center"><strong><?php echo $usr['title']; ?></strong></div>
        <div align="center"><?php echo $usr['genderlink']; ?></a></div>
    <?php } ?>
<?php } ?>